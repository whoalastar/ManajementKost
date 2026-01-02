<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentService $paymentService
    ) {}

    /**
     * Display a listing of payments
     */
    public function index(Request $request): View|JsonResponse
    {
        $filters = $request->only(['invoice_id', 'tenant_id', 'payment_method', 'verified', 'date_from', 'date_to', 'sort_by', 'sort_dir', 'per_page']);
        $payments = $this->paymentService->getFilteredPayments($filters);
        $invoices = Invoice::unpaid()->with('tenant')->get();
        $methods = Payment::methods();

        if ($request->wantsJson()) {
            return response()->json($payments);
        }

        return view('admin.payments.index', compact('payments', 'invoices', 'methods', 'filters'));
    }

    /**
     * Show the form for creating a new payment
     */
    public function create(Request $request): View
    {
        $invoiceId = $request->get('invoice_id');
        $invoice = $invoiceId ? Invoice::with('tenant')->find($invoiceId) : null;
        $invoices = Invoice::unpaid()->with('tenant')->get();
        $methods = Payment::methods();
        
        return view('admin.payments.create', compact('invoice', 'invoices', 'methods'));
    }

    /**
     * Store a newly created payment
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|in:cash,transfer',
            'payment_date' => 'required|date',
            'proof_image' => 'nullable|image|max:2048',
            'notes' => 'nullable|string',
        ]);

        $payment = $this->paymentService->create(
            $validated,
            $request->file('proof_image')
        );

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Pembayaran berhasil ditambahkan', 'payment' => $payment], 201);
        }

        return redirect()->route('admin.payments.index')
            ->with('success', 'Pembayaran berhasil ditambahkan.');
    }

    /**
     * Display the specified payment
     */
    public function show(Payment $payment): View|JsonResponse
    {
        $payment->load(['invoice', 'tenant', 'verifiedBy']);

        if (request()->wantsJson()) {
            return response()->json($payment);
        }

        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Verify payment
     */
    public function verify(Payment $payment): RedirectResponse|JsonResponse
    {
        $payment = $this->paymentService->verify($payment);

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Pembayaran berhasil diverifikasi', 'payment' => $payment]);
        }

        return back()->with('success', 'Pembayaran berhasil diverifikasi.');
    }

    /**
     * Upload proof image
     */
    public function uploadProof(Request $request, Payment $payment): RedirectResponse|JsonResponse
    {
        $request->validate([
            'proof_image' => 'required|image|max:2048',
        ]);

        $payment = $this->paymentService->uploadProof($payment, $request->file('proof_image'));

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Bukti pembayaran berhasil diunggah', 'payment' => $payment]);
        }

        return back()->with('success', 'Bukti pembayaran berhasil diunggah.');
    }

    /**
     * Delete payment
     */
    public function destroy(Payment $payment): RedirectResponse|JsonResponse
    {
        $this->paymentService->delete($payment);

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Pembayaran berhasil dihapus']);
        }

        return redirect()->route('admin.payments.index')
            ->with('success', 'Pembayaran berhasil dihapus.');
    }

    /**
     * Get payment summary
     */
    public function summary(Request $request): JsonResponse
    {
        $filters = $request->only(['date_from', 'date_to']);
        $summary = $this->paymentService->getPaymentSummary($filters);

        return response()->json($summary);
    }
}
