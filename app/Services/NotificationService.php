<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\TenantNotification;

class NotificationService
{
    /**
     * Create notification for tenant
     */
    public function notify(
        Tenant $tenant,
        string $type,
        string $title,
        string $message,
        ?string $link = null
    ): TenantNotification {
        return TenantNotification::createForTenant(
            $tenant,
            $type,
            $title,
            $message,
            $link
        );
    }

    /**
     * Notify invoice created
     */
    public function notifyInvoiceCreated(Tenant $tenant, $invoice): TenantNotification
    {
        return $this->notify(
            $tenant,
            TenantNotification::TYPE_INVOICE_CREATED,
            'Invoice Baru Dibuat',
            "Invoice #{$invoice->invoice_number} periode {$invoice->period_month}/{$invoice->period_year} telah dibuat. Total: Rp " . number_format($invoice->total_amount, 0, ',', '.'),
            "/invoices/{$invoice->id}"
        );
    }

    /**
     * Notify invoice due soon
     */
    public function notifyInvoiceDueSoon(Tenant $tenant, $invoice): TenantNotification
    {
        return $this->notify(
            $tenant,
            TenantNotification::TYPE_INVOICE_DUE,
            'Invoice Segera Jatuh Tempo',
            "Invoice #{$invoice->invoice_number} akan jatuh tempo pada " . $invoice->due_date->format('d M Y') . ". Segera lakukan pembayaran.",
            "/invoices/{$invoice->id}"
        );
    }

    /**
     * Notify invoice overdue
     */
    public function notifyInvoiceOverdue(Tenant $tenant, $invoice): TenantNotification
    {
        return $this->notify(
            $tenant,
            TenantNotification::TYPE_INVOICE_OVERDUE,
            'Invoice Terlambat',
            "Invoice #{$invoice->invoice_number} sudah melewati jatuh tempo. Mohon segera lakukan pembayaran untuk menghindari denda.",
            "/invoices/{$invoice->id}"
        );
    }

    /**
     * Notify payment verified
     */
    public function notifyPaymentVerified(Tenant $tenant, $payment): TenantNotification
    {
        return $this->notify(
            $tenant,
            TenantNotification::TYPE_PAYMENT_VERIFIED,
            'Pembayaran Diverifikasi',
            "Pembayaran Anda sebesar Rp " . number_format($payment->amount, 0, ',', '.') . " untuk invoice #{$payment->invoice->invoice_number} telah diverifikasi.",
            "/payments/{$payment->id}"
        );
    }

    /**
     * Notify payment rejected
     */
    public function notifyPaymentRejected(Tenant $tenant, $payment, string $reason = null): TenantNotification
    {
        $message = "Pembayaran Anda untuk invoice #{$payment->invoice->invoice_number} ditolak.";
        if ($reason) {
            $message .= " Alasan: {$reason}";
        }

        return $this->notify(
            $tenant,
            TenantNotification::TYPE_PAYMENT_REJECTED,
            'Pembayaran Ditolak',
            $message,
            "/payments/{$payment->id}"
        );
    }

    /**
     * Notify maintenance updated
     */
    public function notifyMaintenanceUpdated(Tenant $tenant, $report): TenantNotification
    {
        $statusLabel = \App\Models\MaintenanceReport::statuses()[$report->status] ?? $report->status;

        return $this->notify(
            $tenant,
            TenantNotification::TYPE_MAINTENANCE_UPDATED,
            'Status Pengaduan Diperbarui',
            "Pengaduan \"{$report->title}\" telah diperbarui ke status: {$statusLabel}",
            "/maintenance/{$report->id}"
        );
    }

    /**
     * Notify maintenance resolved
     */
    public function notifyMaintenanceResolved(Tenant $tenant, $report): TenantNotification
    {
        return $this->notify(
            $tenant,
            TenantNotification::TYPE_MAINTENANCE_RESOLVED,
            'Pengaduan Selesai',
            "Pengaduan \"{$report->title}\" telah diselesaikan.",
            "/maintenance/{$report->id}"
        );
    }

    /**
     * Send general notification
     */
    public function notifyGeneral(Tenant $tenant, string $title, string $message, ?string $link = null): TenantNotification
    {
        return $this->notify(
            $tenant,
            TenantNotification::TYPE_GENERAL,
            $title,
            $message,
            $link
        );
    }
}
