<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PaymentService
{
    /**
     * Get all payments with filters
     */
    public function getFilteredPayments(array $filters = [])
    {
        $query = Payment::with(['invoice', 'tenant', 'verifiedBy']);

        if (!empty($filters['invoice_id'])) {
            $query->where('invoice_id', $filters['invoice_id']);
        }

        if (!empty($filters['tenant_id'])) {
            $query->where('tenant_id', $filters['tenant_id']);
        }

        if (!empty($filters['payment_method'])) {
            $query->where('payment_method', $filters['payment_method']);
        }

        if (!empty($filters['verified'])) {
            if ($filters['verified'] === 'yes') {
                $query->whereNotNull('verified_at');
            } else {
                $query->whereNull('verified_at');
            }
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('payment_date', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('payment_date', '<=', $filters['date_to']);
        }

        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDir = $filters['sort_dir'] ?? 'desc';
        $query->orderBy($sortBy, $sortDir);

        return $query->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Create a payment
     */
    public function create(array $data, ?UploadedFile $proofImage = null): Payment
    {
        return DB::transaction(function () use ($data, $proofImage) {
            $invoice = Invoice::findOrFail($data['invoice_id']);

            $proofPath = null;
            if ($proofImage) {
                $filename = time() . '_' . uniqid() . '.' . $proofImage->getClientOriginalExtension();
                $path = 'uploads/payments/' . $invoice->id;
                
                if (!file_exists(public_path($path))) {
                    mkdir(public_path($path), 0755, true);
                }
                
                $proofImage->move(public_path($path), $filename);
                $proofPath = $path . '/' . $filename;
            }

            $payment = Payment::create([
                'invoice_id' => $invoice->id,
                'tenant_id' => $invoice->tenant_id,
                'amount' => $data['amount'],
                'payment_method' => $data['payment_method'],
                'payment_date' => $data['payment_date'] ?? now(),
                'proof_image' => $proofPath ?? ($data['proof_image'] ?? null),
                'notes' => $data['notes'] ?? null,
            ]);

            // Update invoice paid amount
            $this->updateInvoicePaidAmount($invoice);

            ActivityLogService::logCreate($payment, "Menambahkan pembayaran untuk invoice: {$invoice->invoice_number}");

            return $payment;
        });
    }

    /**
     * Verify payment
     */
    public function verify(Payment $payment): Payment
    {
        $payment->update([
            'verified_by' => Auth::guard('admin')->id(),
            'verified_at' => now(),
        ]);

        // Update invoice status
        $this->updateInvoicePaidAmount($payment->invoice);

        ActivityLogService::log(
            'verify_payment',
            $payment,
            description: "Memverifikasi pembayaran"
        );

        return $payment->fresh();
    }

    /**
     * Upload proof image
     */
    public function uploadProof(Payment $payment, UploadedFile $file): Payment
    {
        // Delete old proof if exists
        if ($payment->proof_image && file_exists(public_path($payment->proof_image))) {
            unlink(public_path($payment->proof_image));
        }

        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = 'uploads/payments/' . $payment->invoice_id;

        if (!file_exists(public_path($path))) {
            mkdir(public_path($path), 0755, true);
        }

        $file->move(public_path($path), $filename);
        $payment->update(['proof_image' => $path . '/' . $filename]);

        return $payment->fresh();
    }

    /**
     * Delete payment
     */
    public function delete(Payment $payment): bool
    {
        return DB::transaction(function () use ($payment) {
            $invoice = $payment->invoice;

            // Delete proof image
            if ($payment->proof_image && file_exists(public_path($payment->proof_image))) {
                unlink(public_path($payment->proof_image));
            }

            ActivityLogService::logDelete($payment, "Menghapus pembayaran");

            $deleted = $payment->delete();

            // Update invoice paid amount
            $this->updateInvoicePaidAmount($invoice);

            return $deleted;
        });
    }

    /**
     * Update invoice paid amount and status
     */
    private function updateInvoicePaidAmount(Invoice $invoice): void
    {
        $totalPaid = $invoice->payments()
            ->whereNotNull('verified_at')
            ->sum('amount');

        $status = $invoice->status;

        if ($totalPaid >= $invoice->total_amount) {
            $status = Invoice::STATUS_PAID;
        } elseif ($invoice->due_date < now() && $totalPaid < $invoice->total_amount) {
            $status = Invoice::STATUS_OVERDUE;
        } elseif ($invoice->status !== Invoice::STATUS_DRAFT) {
            $status = Invoice::STATUS_SENT;
        }

        $invoice->update([
            'paid_amount' => $totalPaid,
            'status' => $status,
        ]);
    }

    /**
     * Get payment summary
     */
    public function getPaymentSummary(array $filters = []): array
    {
        $query = Payment::whereNotNull('verified_at');

        if (!empty($filters['date_from'])) {
            $query->whereDate('payment_date', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('payment_date', '<=', $filters['date_to']);
        }

        return [
            'total_verified' => $query->sum('amount'),
            'total_cash' => (clone $query)->where('payment_method', Payment::METHOD_CASH)->sum('amount'),
            'total_transfer' => (clone $query)->where('payment_method', Payment::METHOD_TRANSFER)->sum('amount'),
            'count' => $query->count(),
        ];
    }
}
