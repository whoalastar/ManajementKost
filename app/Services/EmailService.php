<?php

namespace App\Services;

use App\Mail\InvoiceMail;
use App\Models\EmailLog;
use App\Models\Invoice;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    /**
     * Send invoice email
     */
    public function sendInvoice(Invoice $invoice): EmailLog
    {
        $tenant = $invoice->tenant;

        if (!$tenant->email) {
            return $this->createLog($invoice, null, 'Invoice #' . $invoice->invoice_number, EmailLog::STATUS_FAILED, 'Email penghuni tidak tersedia');
        }

        $log = $this->createLog(
            $invoice,
            $tenant->email,
            'Invoice #' . $invoice->invoice_number,
            EmailLog::STATUS_PENDING
        );

        try {
            Mail::to($tenant->email)->send(new InvoiceMail($invoice));

            $log->update([
                'status' => EmailLog::STATUS_SENT,
                'sent_at' => now(),
            ]);

            // Update invoice status
            $invoice->update([
                'status' => Invoice::STATUS_SENT,
                'sent_at' => now(),
            ]);

        } catch (\Exception $e) {
            $log->update([
                'status' => EmailLog::STATUS_FAILED,
                'error_message' => $e->getMessage(),
            ]);
        }

        return $log;
    }

    /**
     * Resend invoice email
     */
    public function resendInvoice(Invoice $invoice): EmailLog
    {
        return $this->sendInvoice($invoice);
    }

    /**
     * Get email logs for invoice
     */
    public function getLogsForInvoice(Invoice $invoice)
    {
        return EmailLog::where('invoice_id', $invoice->id)
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * Get all email logs with filters
     */
    public function getFilteredLogs(array $filters = [])
    {
        $query = EmailLog::with('invoice');

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['invoice_id'])) {
            $query->where('invoice_id', $filters['invoice_id']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query->orderByDesc('created_at')
            ->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Create email log
     */
    private function createLog(
        Invoice $invoice,
        ?string $email,
        string $subject,
        string $status,
        ?string $errorMessage = null
    ): EmailLog {
        return EmailLog::create([
            'invoice_id' => $invoice->id,
            'email_to' => $email ?? '-',
            'subject' => $subject,
            'status' => $status,
            'error_message' => $errorMessage,
        ]);
    }
}
