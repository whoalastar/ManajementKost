<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #1f2937;
        }
        .container {
            padding: 40px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
        }
        .company-info h1 {
            font-size: 24px;
            color: #2563eb;
            margin-bottom: 8px;
        }
        .company-info p {
            color: #6b7280;
            font-size: 11px;
        }
        .invoice-info {
            text-align: right;
        }
        .invoice-info h2 {
            font-size: 28px;
            color: #1f2937;
            margin-bottom: 8px;
        }
        .invoice-info p {
            color: #6b7280;
            font-size: 11px;
        }
        .invoice-number {
            font-size: 14px;
            font-weight: bold;
            color: #2563eb;
        }
        .bill-to {
            background: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .bill-to h3 {
            font-size: 11px;
            text-transform: uppercase;
            color: #6b7280;
            margin-bottom: 8px;
        }
        .bill-to .name {
            font-size: 16px;
            font-weight: bold;
            color: #1f2937;
        }
        .bill-to .details {
            color: #4b5563;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th {
            background: #2563eb;
            color: white;
            padding: 12px 16px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
        }
        th:last-child {
            text-align: right;
        }
        td {
            padding: 12px 16px;
            border-bottom: 1px solid #e5e7eb;
        }
        td:last-child {
            text-align: right;
        }
        .total-row {
            background: #f9fafb;
        }
        .total-row td {
            font-weight: bold;
            font-size: 14px;
        }
        .grand-total {
            background: #2563eb;
            color: white;
        }
        .grand-total td {
            font-size: 16px;
        }
        .notes {
            background: #fef3c7;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .notes h4 {
            font-size: 11px;
            text-transform: uppercase;
            color: #92400e;
            margin-bottom: 4px;
        }
        .notes p {
            color: #78350f;
        }
        .payment-info {
            margin-bottom: 30px;
        }
        .payment-info h4 {
            font-size: 11px;
            text-transform: uppercase;
            color: #6b7280;
            margin-bottom: 8px;
        }
        .payment-info p {
            color: #1f2937;
        }
        .footer {
            text-align: center;
            color: #6b7280;
            font-size: 10px;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-paid {
            background: #dcfce7;
            color: #15803d;
        }
        .status-unpaid {
            background: #fef2f2;
            color: #dc2626;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="company-info">
                <h1>{{ config('app.name', 'Kost Sejahtera') }}</h1>
                <p>Jl. Contoh Alamat No. 123</p>
                <p>Telp: 08123456789</p>
                <p>Email: info@kost.com</p>
            </div>
            <div class="invoice-info">
                <h2>INVOICE</h2>
                <p class="invoice-number">{{ $invoice->invoice_number }}</p>
                <p style="margin-top: 8px;">
                    <span class="status-badge {{ $invoice->status === 'paid' ? 'status-paid' : 'status-unpaid' }}">
                        {{ $invoice->status === 'paid' ? 'LUNAS' : 'BELUM LUNAS' }}
                    </span>
                </p>
            </div>
        </div>
        
        <table style="width: auto; margin-bottom: 30px;">
            <tr>
                <td style="border: none; padding: 4px 0;"><strong>Tanggal:</strong></td>
                <td style="border: none; padding: 4px 0 4px 16px;">{{ $invoice->created_at->format('d M Y') }}</td>
            </tr>
            <tr>
                <td style="border: none; padding: 4px 0;"><strong>Periode:</strong></td>
                <td style="border: none; padding: 4px 0 4px 16px;">{{ DateTime::createFromFormat('!m', $invoice->period_month)->format('F') }} {{ $invoice->period_year }}</td>
            </tr>
            <tr>
                <td style="border: none; padding: 4px 0;"><strong>Jatuh Tempo:</strong></td>
                <td style="border: none; padding: 4px 0 4px 16px;">{{ $invoice->due_date?->format('d M Y') }}</td>
            </tr>
        </table>
        
        <div class="bill-to">
            <h3>Tagihan Untuk</h3>
            <p class="name">{{ $invoice->tenant->name }}</p>
            <p class="details">{{ $invoice->room->name ?? '-' }}</p>
            <p class="details">{{ $invoice->tenant->phone }}</p>
            <p class="details">{{ $invoice->tenant->email }}</p>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>Deskripsi</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Sewa Kamar ({{ $invoice->room->name ?? '-' }})</td>
                    <td>Rp {{ number_format($invoice->room_price, 0, ',', '.') }}</td>
                </tr>
                @if($invoice->electricity_fee > 0)
                <tr>
                    <td>Listrik</td>
                    <td>Rp {{ number_format($invoice->electricity_fee, 0, ',', '.') }}</td>
                </tr>
                @endif
                @if($invoice->water_fee > 0)
                <tr>
                    <td>Air</td>
                    <td>Rp {{ number_format($invoice->water_fee, 0, ',', '.') }}</td>
                </tr>
                @endif
                @if($invoice->internet_fee > 0)
                <tr>
                    <td>Internet</td>
                    <td>Rp {{ number_format($invoice->internet_fee, 0, ',', '.') }}</td>
                </tr>
                @endif
                @if($invoice->penalty_fee > 0)
                <tr>
                    <td>Denda</td>
                    <td>Rp {{ number_format($invoice->penalty_fee, 0, ',', '.') }}</td>
                </tr>
                @endif
                @if($invoice->other_fee > 0)
                <tr>
                    <td>{{ $invoice->other_fee_description ?? 'Biaya Lain' }}</td>
                    <td>Rp {{ number_format($invoice->other_fee, 0, ',', '.') }}</td>
                </tr>
                @endif
                <tr class="grand-total">
                    <td>TOTAL</td>
                    <td>Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</td>
                </tr>
                @if($invoice->paid_amount > 0)
                <tr>
                    <td>Dibayar</td>
                    <td>Rp {{ number_format($invoice->paid_amount, 0, ',', '.') }}</td>
                </tr>
                <tr class="total-row">
                    <td>Sisa</td>
                    <td>Rp {{ number_format($invoice->total_amount - $invoice->paid_amount, 0, ',', '.') }}</td>
                </tr>
                @endif
            </tbody>
        </table>
        
        @if(isset($paymentMethods) && $paymentMethods->count() > 0)
        <div class="payment-info">
            <h4>Informasi Pembayaran</h4>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                @foreach($paymentMethods as $pm)
                <div style="margin-bottom: 10px;">
                    <p><strong>{{ $pm->name }}</strong></p>
                    <p style="font-size: 11px;">{{ $pm->account_number }} ({{ $pm->account_holder }})</p>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="payment-info">
             <h4>Informasi Pembayaran</h4>
             <p>Silakan hubungi admin untuk informasi rekening pembayaran.</p>
        </div>
        @endif
        
        @if($invoice->notes)
        <div class="notes">
            <h4>Catatan</h4>
            <p>{{ $invoice->notes }}</p>
        </div>
        @endif
        
        <div class="footer">
            <p>Terima kasih atas kepercayaan Anda</p>
            <p>Invoice ini digenerate secara otomatis pada {{ now()->format('d M Y H:i') }}</p>
        </div>
    </div>
</body>
</html>
