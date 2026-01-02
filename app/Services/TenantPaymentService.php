<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Tenant;
use App\Models\TenantNotification;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class TenantPaymentService
{
    /**
     * Submit payment confirmation
     */
    public function submitPayment(Tenant $tenant, array $data, ?UploadedFile $proofImage = null): Payment
    {
        $invoice = $tenant->invoices()->findOrFail($data['invoice_id']);

        $proofPath = null;
        if ($proofImage) {
            $proofPath = $proofImage->store('payments/' . $invoice->id, 'public');
        }

        $payment = Payment::create([
            'invoice_id' => $invoice->id,
            'tenant_id' => $tenant->id,
            'amount' => $data['amount'],
            'payment_method' => $data['payment_method'],
            'payment_date' => $data['payment_date'] ?? now(),
            'proof_image' => $proofPath,
            'notes' => $data['notes'] ?? null,
            // verified_by dan verified_at akan diisi oleh admin
        ]);

        return $payment;
    }

    /**
     * Upload proof for existing payment
     */
    public function uploadProof(Tenant $tenant, int $paymentId, UploadedFile $file): Payment
    {
        $payment = $tenant->payments()->findOrFail($paymentId);

        // Can only upload proof if not yet verified
        if ($payment->verified_at) {
            throw new \Exception('Pembayaran sudah diverifikasi, tidak dapat mengubah bukti.');
        }

        // Delete old proof if exists
        if ($payment->proof_image) {
            Storage::disk('public')->delete($payment->proof_image);
        }

        $path = $file->store('payments/' . $payment->invoice_id, 'public');
        $payment->update(['proof_image' => $path]);

        return $payment->fresh();
    }

    /**
     * Get unpaid invoices for tenant
     */
    public function getUnpaidInvoices(Tenant $tenant)
    {
        return $tenant->invoices()
            ->whereIn('status', [
                Invoice::STATUS_SENT,
                Invoice::STATUS_DUE,
                Invoice::STATUS_OVERDUE
            ])
            ->where(function ($query) {
                $query->whereColumn('paid_amount', '<', 'total_amount');
            })
            ->orderBy('due_date')
            ->get();
    }

    /**
     * Get payment status description
     */
    public function getPaymentStatus(Payment $payment): string
    {
        if ($payment->verified_at) {
            return 'verified';
        }

        return 'pending';
    }

    /**
     * Cancel pending payment (before verification)
     */
    public function cancelPayment(Tenant $tenant, int $paymentId): bool
    {
        $payment = $tenant->payments()
            ->whereNull('verified_at')
            ->findOrFail($paymentId);

        // Delete proof image
        if ($payment->proof_image) {
            Storage::disk('public')->delete($payment->proof_image);
        }

        return $payment->delete();
    }
}
