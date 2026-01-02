<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Room;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
    /**
     * Get all invoices with filters
     */
    public function getFilteredInvoices(array $filters = [])
    {
        $query = Invoice::with(['tenant', 'room', 'payments']);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['tenant_id'])) {
            $query->where('tenant_id', $filters['tenant_id']);
        }

        if (!empty($filters['room_id'])) {
            $query->where('room_id', $filters['room_id']);
        }

        if (!empty($filters['period_month'])) {
            $query->where('period_month', $filters['period_month']);
        }

        if (!empty($filters['period_year'])) {
            $query->where('period_year', $filters['period_year']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                    ->orWhereHas('tenant', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDir = $filters['sort_dir'] ?? 'desc';
        $query->orderBy($sortBy, $sortDir);

        return $query->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Generate invoice manually
     */
    public function create(array $data): Invoice
    {
        return DB::transaction(function () use ($data) {
            $tenant = Tenant::findOrFail($data['tenant_id']);
            $room = Room::findOrFail($data['room_id']);

            $invoice = Invoice::create([
                'invoice_number' => Invoice::generateInvoiceNumber(),
                'tenant_id' => $tenant->id,
                'room_id' => $room->id,
                'period_month' => $data['period_month'],
                'period_year' => $data['period_year'],
                'due_date' => $data['due_date'],
                'room_price' => $data['room_price'] ?? $room->price,
                'electricity_fee' => $data['electricity_fee'] ?? 0,
                'water_fee' => $data['water_fee'] ?? 0,
                'internet_fee' => $data['internet_fee'] ?? 0,
                'penalty_fee' => $data['penalty_fee'] ?? 0,
                'other_fee' => $data['other_fee'] ?? 0,
                'other_fee_description' => $data['other_fee_description'] ?? null,
                'total_amount' => 0, // Will be calculated
                'status' => Invoice::STATUS_DRAFT,
                'notes' => $data['notes'] ?? null,
            ]);

            // Calculate total
            $invoice->update([
                'total_amount' => $invoice->calculateTotal(),
            ]);

            ActivityLogService::logCreate($invoice, "Membuat invoice: {$invoice->invoice_number}");

            return $invoice;
        });
    }

    /**
     * Generate monthly invoices for all active tenants
     */
    public function generateMonthlyInvoices(int $month, int $year, int $dueDays = 10): array
    {
        $createdInvoices = [];
        $activeTenants = Tenant::with('room')->active()->whereNotNull('room_id')->get();

        foreach ($activeTenants as $tenant) {
            // Check if invoice already exists
            $exists = Invoice::where('tenant_id', $tenant->id)
                ->where('period_month', $month)
                ->where('period_year', $year)
                ->exists();

            if ($exists) {
                continue;
            }

            $invoice = $this->create([
                'tenant_id' => $tenant->id,
                'room_id' => $tenant->room_id,
                'period_month' => $month,
                'period_year' => $year,
                'due_date' => Carbon::create($year, $month, $dueDays),
                'room_price' => $tenant->room->price,
            ]);

            $createdInvoices[] = $invoice;
        }

        return $createdInvoices;
    }

    /**
     * Update invoice
     */
    public function update(Invoice $invoice, array $data): Invoice
    {
        $oldValues = $invoice->toArray();

        $invoice->update([
            'electricity_fee' => $data['electricity_fee'] ?? $invoice->electricity_fee,
            'water_fee' => $data['water_fee'] ?? $invoice->water_fee,
            'internet_fee' => $data['internet_fee'] ?? $invoice->internet_fee,
            'penalty_fee' => $data['penalty_fee'] ?? $invoice->penalty_fee,
            'other_fee' => $data['other_fee'] ?? $invoice->other_fee,
            'other_fee_description' => $data['other_fee_description'] ?? $invoice->other_fee_description,
            'due_date' => $data['due_date'] ?? $invoice->due_date,
            'notes' => $data['notes'] ?? $invoice->notes,
        ]);

        // Recalculate total
        $invoice->update([
            'total_amount' => $invoice->calculateTotal(),
        ]);

        ActivityLogService::logUpdate($invoice, $oldValues, "Mengubah invoice: {$invoice->invoice_number}");

        return $invoice->fresh();
    }

    /**
     * Send invoice
     */
    public function send(Invoice $invoice): Invoice
    {
        $invoice->update([
            'status' => Invoice::STATUS_SENT,
            'sent_at' => now(),
        ]);

        // Email will be handled by EmailService
        return $invoice;
    }

    /**
     * Mark as paid
     */
    public function markAsPaid(Invoice $invoice): Invoice
    {
        $invoice->update([
            'status' => Invoice::STATUS_PAID,
            'paid_amount' => $invoice->total_amount,
        ]);

        return $invoice;
    }

    /**
     * Update invoice statuses based on due date
     */
    public function updateOverdueStatuses(): int
    {
        $count = Invoice::whereIn('status', [Invoice::STATUS_SENT, Invoice::STATUS_DUE])
            ->where('due_date', '<', now())
            ->update(['status' => Invoice::STATUS_OVERDUE]);

        return $count;
    }

    /**
     * Get unpaid invoices for tenant
     */
    public function getUnpaidInvoicesForTenant(Tenant $tenant)
    {
        return Invoice::where('tenant_id', $tenant->id)
            ->unpaid()
            ->orderBy('due_date')
            ->get();
    }
}
