<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Models\TenantNotification;
use App\Services\NotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateInvoiceStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update status invoice yang jatuh tempo atau terlambat';

    /**
     * Execute the console command.
     */
    public function handle(NotificationService $notificationService)
    {
        $this->info('Memulai update status invoice...');

        // 1. Update ke DUE (Jatuh Tempo) - misalnya H-3
        // Ini opsional, tergantung logika bisnis. Di sini kita fokus ke OVERDUE.

        // 2. Update ke OVERDUE (Terlambat)
        // Cari invoice yang statusnya SENT atau DUE, tapi due_date < hari ini
        $overdueInvoices = Invoice::whereIn('status', [Invoice::STATUS_SENT, Invoice::STATUS_DUE])
            ->whereDate('due_date', '<', now())
            ->get();

        $count = 0;

        foreach ($overdueInvoices as $invoice) {
            DB::transaction(function () use ($invoice, $notificationService) {
                // Update status
                $invoice->update(['status' => Invoice::STATUS_OVERDUE]);

                // Kirim notifikasi ke tenant via sistem
                if ($invoice->tenant) {
                    $notificationService->notifyInvoiceOverdue($invoice->tenant, $invoice);
                }
            });
            
            $count++;
            $this->line("Invoice #{$invoice->invoice_number} diupdate menjadi OVERDUE.");
        }

        $this->info("Selesai. {$count} invoice berhasil diupdate.");
    }
}
