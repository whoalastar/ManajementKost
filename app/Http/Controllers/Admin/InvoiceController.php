<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Room;
use App\Models\Tenant;
use App\Services\EmailService;
use App\Services\InvoiceService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function __construct(
        private InvoiceService $invoiceService,
        private EmailService $emailService
    ) {}

    /**
     * Display a listing of invoices
     */
    public function index(Request $request): View|JsonResponse
    {
        $filters = $request->only(['status', 'tenant_id', 'room_id', 'period_month', 'period_year', 'search', 'sort_by', 'sort_dir', 'per_page']);
        $invoices = $this->invoiceService->getFilteredInvoices($filters);
        $tenants = Tenant::active()->get();
        $rooms = Room::all();
        $statuses = Invoice::statuses();

        if ($request->wantsJson()) {
            return response()->json($invoices);
        }

        return view('admin.invoices.index', compact('invoices', 'tenants', 'rooms', 'statuses', 'filters'));
    }

    /**
     * Show the form for creating a new invoice
     */
    public function create(): View
    {
        $tenants = Tenant::with('room')->active()->get();
        $rooms = Room::occupied()->get();
        return view('admin.invoices.create', compact('tenants', 'rooms'));
    }

    /**
     * Store a newly created invoice
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'room_id' => 'required|exists:rooms,id',
            'period_month' => 'required|integer|min:1|max:12',
            'period_year' => 'required|integer|min:2020|max:2100',
            'due_date' => 'required|date',
            'room_price' => 'nullable|numeric|min:0',
            'electricity_fee' => 'nullable|numeric|min:0',
            'water_fee' => 'nullable|numeric|min:0',
            'internet_fee' => 'nullable|numeric|min:0',
            'penalty_fee' => 'nullable|numeric|min:0',
            'other_fee' => 'nullable|numeric|min:0',
            'other_fee_description' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $invoice = $this->invoiceService->create($validated);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Invoice berhasil dibuat', 'invoice' => $invoice], 201);
        }

        return redirect()->route('admin.invoices.index')
            ->with('success', 'Invoice berhasil dibuat.');
    }

    /**
     * Display the specified invoice
     */
    public function show(Invoice $invoice): View|JsonResponse
    {
        $invoice->load(['tenant', 'room', 'payments', 'emailLogs']);

        if (request()->wantsJson()) {
            return response()->json($invoice);
        }

        return view('admin.invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified invoice
     */
    public function edit(Invoice $invoice): View
    {
        return view('admin.invoices.edit', compact('invoice'));
    }

    /**
     * Update the specified invoice
     */
    public function update(Request $request, Invoice $invoice): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'due_date' => 'nullable|date',
            'electricity_fee' => 'nullable|numeric|min:0',
            'water_fee' => 'nullable|numeric|min:0',
            'internet_fee' => 'nullable|numeric|min:0',
            'penalty_fee' => 'nullable|numeric|min:0',
            'other_fee' => 'nullable|numeric|min:0',
            'other_fee_description' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $invoice = $this->invoiceService->update($invoice, $validated);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Invoice berhasil diperbarui', 'invoice' => $invoice]);
        }

        return redirect()->route('admin.invoices.index')
            ->with('success', 'Invoice berhasil diperbarui.');
    }

    /**
     * Send invoice via email
     */
    public function send(Invoice $invoice): RedirectResponse|JsonResponse
    {
        $log = $this->emailService->sendInvoice($invoice);

        $message = $log->status === 'sent' 
            ? 'Invoice berhasil dikirim via email.' 
            : 'Gagal mengirim invoice: ' . $log->error_message;

        if (request()->wantsJson()) {
            return response()->json(['message' => $message, 'log' => $log]);
        }

        return back()->with($log->status === 'sent' ? 'success' : 'error', $message);
    }

    /**
     * Resend invoice via email
     */
    public function resend(Invoice $invoice): RedirectResponse|JsonResponse
    {
        return $this->send($invoice);
    }

    /**
     * Generate monthly invoices
     */
    public function generateMonthly(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2100',
            'due_days' => 'nullable|integer|min:1|max:28',
        ]);

        $invoices = $this->invoiceService->generateMonthlyInvoices(
            $validated['month'],
            $validated['year'],
            $validated['due_days'] ?? 10
        );

        $count = count($invoices);
        $message = "Berhasil membuat {$count} invoice bulanan.";

        if ($request->wantsJson()) {
            return response()->json(['message' => $message, 'count' => $count, 'invoices' => $invoices]);
        }

        return redirect()->route('admin.invoices.index')
            ->with('success', $message);
    }

    /**
     * Download invoice as PDF
     */
    public function download(Invoice $invoice): Response
    {
        $invoice->load(['tenant', 'room', 'payments']);

        $pdf = Pdf::loadView('admin.invoices.pdf', compact('invoice'));

        return $pdf->download("invoice-{$invoice->invoice_number}.pdf");
    }

    /**
     * Mark invoice as paid
     */
    public function markAsPaid(Invoice $invoice): RedirectResponse|JsonResponse
    {
        $invoice = $this->invoiceService->markAsPaid($invoice);

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Invoice ditandai lunas', 'invoice' => $invoice]);
        }

        return back()->with('success', 'Invoice ditandai lunas.');
    }

    /**
     * Delete invoice
     */
    public function destroy(Invoice $invoice): RedirectResponse|JsonResponse
    {
        $invoice->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Invoice berhasil dihapus']);
        }

        return redirect()->route('admin.invoices.index')
            ->with('success', 'Invoice berhasil dihapus.');
    }
}
