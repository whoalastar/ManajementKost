<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\TenantPaymentService;
use App\Services\TenantPortalService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(
        private TenantPortalService $portalService,
        private TenantPaymentService $paymentService
    ) {}

    /**
     * Display a listing of payments
     */
    public function index(Request $request): View|JsonResponse
    {
        $tenant = Auth::guard('tenant')->user();
        $filters = $request->only(['status', 'per_page']);
        $payments = $this->portalService->getPayments($tenant, $filters);

        if ($request->wantsJson()) {
            return response()->json($payments);
        }

        return view('tenant.payments.index', compact('payments', 'filters'));
    }

    /**
     * Show the form for creating a new payment confirmation
     */
    public function create(Request $request): View
    {
        $tenant = Auth::guard('tenant')->user();
        $invoiceId = $request->get('invoice_id');
        $unpaidInvoices = $this->paymentService->getUnpaidInvoices($tenant);
        $methods = Payment::methods();

        return view('tenant.payments.create', compact('unpaidInvoices', 'methods', 'invoiceId'));
    }

    /**
     * Store a newly created payment confirmation
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $tenant = Auth::guard('tenant')->user();

        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|in:cash,transfer',
            'payment_date' => 'required|date|before_or_equal:today',
            'proof_image' => 'required|image|max:2048',
            'notes' => 'nullable|string|max:500',
        ], [
            'invoice_id.required' => 'Pilih invoice yang akan dibayar.',
            'invoice_id.exists' => 'Invoice tidak valid.',
            'amount.required' => 'Jumlah pembayaran wajib diisi.',
            'amount.min' => 'Jumlah pembayaran minimal Rp 1.',
            'payment_method.required' => 'Pilih metode pembayaran.',
            'payment_date.required' => 'Tanggal pembayaran wajib diisi.',
            'payment_date.before_or_equal' => 'Tanggal pembayaran tidak boleh lebih dari hari ini.',
            'proof_image.required' => 'Bukti pembayaran wajib diunggah.',
            'proof_image.image' => 'Bukti pembayaran harus berupa gambar.',
            'proof_image.max' => 'Ukuran file maksimal 2MB.',
        ]);

        // Verify invoice belongs to tenant
        $invoice = $tenant->invoices()->find($validated['invoice_id']);
        if (!$invoice) {
            return back()->withErrors(['invoice_id' => 'Invoice tidak ditemukan.']);
        }

        $payment = $this->paymentService->submitPayment(
            $tenant,
            $validated,
            $request->file('proof_image')
        );

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Konfirmasi pembayaran berhasil dikirim. Menunggu verifikasi admin.',
                'payment' => $payment
            ], 201);
        }

        return redirect()->route('tenant.payments.index')
            ->with('success', 'Konfirmasi pembayaran berhasil dikirim. Menunggu verifikasi admin.');
    }

    /**
     * Display the specified payment
     */
    public function show(int $id): View|JsonResponse
    {
        $tenant = Auth::guard('tenant')->user();
        $payment = $tenant->payments()->with('invoice')->find($id);

        if (!$payment) {
            abort(404, 'Pembayaran tidak ditemukan.');
        }

        if (request()->wantsJson()) {
            return response()->json($payment);
        }

        return view('tenant.payments.show', compact('payment'));
    }

    /**
     * Upload new proof image for pending payment
     */
    public function uploadProof(Request $request, int $id): RedirectResponse|JsonResponse
    {
        $tenant = Auth::guard('tenant')->user();

        $request->validate([
            'proof_image' => 'required|image|max:2048',
        ], [
            'proof_image.required' => 'Bukti pembayaran wajib diunggah.',
            'proof_image.image' => 'Bukti pembayaran harus berupa gambar.',
            'proof_image.max' => 'Ukuran file maksimal 2MB.',
        ]);

        try {
            $payment = $this->paymentService->uploadProof(
                $tenant,
                $id,
                $request->file('proof_image')
            );

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Bukti pembayaran berhasil diperbarui.',
                    'payment' => $payment
                ]);
            }

            return back()->with('success', 'Bukti pembayaran berhasil diperbarui.');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => $e->getMessage()], 422);
            }

            return back()->withErrors(['proof_image' => $e->getMessage()]);
        }
    }

    /**
     * Cancel pending payment
     */
    public function cancel(int $id): RedirectResponse|JsonResponse
    {
        $tenant = Auth::guard('tenant')->user();

        try {
            $this->paymentService->cancelPayment($tenant, $id);

            if (request()->wantsJson()) {
                return response()->json(['message' => 'Konfirmasi pembayaran berhasil dibatalkan.']);
            }

            return redirect()->route('tenant.payments.index')
                ->with('success', 'Konfirmasi pembayaran berhasil dibatalkan.');
        } catch (\Exception $e) {
            if (request()->wantsJson()) {
                return response()->json(['message' => 'Pembayaran tidak ditemukan atau sudah diverifikasi.'], 404);
            }

            return back()->withErrors(['error' => 'Pembayaran tidak ditemukan atau sudah diverifikasi.']);
        }
    }
}
