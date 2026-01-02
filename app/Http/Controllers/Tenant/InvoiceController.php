<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Services\TenantPortalService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function __construct(
        private TenantPortalService $portalService
    ) {}

    /**
     * Display a listing of invoices
     */
    public function index(Request $request): View|JsonResponse
    {
        $tenant = Auth::guard('tenant')->user();
        $filters = $request->only(['status', 'year', 'per_page']);
        $invoices = $this->portalService->getInvoices($tenant, $filters);
        $statuses = Invoice::statuses();

        if ($request->wantsJson()) {
            return response()->json($invoices);
        }

        return view('tenant.invoices.index', compact('invoices', 'statuses', 'filters'));
    }

    /**
     * Display the specified invoice
     */
    public function show(int $id): View|JsonResponse
    {
        $tenant = Auth::guard('tenant')->user();
        $invoice = $this->portalService->getInvoice($tenant, $id);

        if (!$invoice) {
            abort(404, 'Invoice tidak ditemukan.');
        }

        if (request()->wantsJson()) {
            return response()->json($invoice);
        }

        return view('tenant.invoices.show', compact('invoice'));
    }

    /**
     * Download invoice as PDF
     */
    public function download(int $id): Response
    {
        $tenant = Auth::guard('tenant')->user();
        $invoice = $this->portalService->getInvoice($tenant, $id);

        if (!$invoice) {
            abort(404, 'Invoice tidak ditemukan.');
        }

        $pdf = Pdf::loadView('tenant.invoices.pdf', compact('invoice'));

        return $pdf->download("invoice-{$invoice->invoice_number}.pdf");
    }
}
