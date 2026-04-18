<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
body {
    color: #1c1c1e;
    font-family: 'cairo', sans-serif;
    font-size: 11px;
    line-height: 1.5;
}

.header-table { width: 100%; margin-bottom: 44px; }
.header-table td { vertical-align: top; }
.workspace-name {
    font-size: 16px;
    font-weight: bold;
    color: #1c1c1e;
}
.workspace-email {
    font-size: 10px;
    color: #888888;
    margin-top: 4px;
}
.invoice-title-ar {
    font-size: 32px;
    font-weight: bold;
    color: #1c1c1e;
    text-align: right;
}
.invoice-label-en {
    font-size: 11px;
    color: #888888;
    text-align: right;
    margin-top: 4px;
}
.invoice-number {
    font-size: 16px;
    font-weight: bold;
    color: #1c1c1e;
    text-align: right;
    margin-top: 10px;
}

.meta-table { width: 100%; margin-bottom: 40px; }
.meta-table td { vertical-align: top; padding: 12px 0; }
.meta-label {
    font-size: 10px;
    color: #888888;
    letter-spacing: 0.3px;
}
.meta-value {
    font-size: 14px;
    font-weight: bold;
    color: #1c1c1e;
    margin-top: 6px;
}
.meta-sub {
    font-size: 11px;
    color: #555555;
    margin-top: 2px;
}

.items-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 28px;
    border: 1px solid #e0e0e0;
}
.items-table thead tr { background-color: #f5f5f7; }
.items-table th {
    padding: 14px 16px;
    font-size: 10px;
    font-weight: bold;
    color: #555555;
    letter-spacing: 0.3px;
    border-bottom: 1px solid #e0e0e0;
}
.items-table td {
    padding: 20px 16px;
    color: #222222;
    vertical-align: top;
    font-size: 11px;
}
.items-table td.amount {
    text-align: right;
    font-weight: bold;
    color: #1c1c1e;
}
.items-table td.currency { text-align: center; color: #666666; }

.summary-table {
    width: 70%;
    margin-left: 30%;
    border-collapse: collapse;
    margin-bottom: 44px;
}
.summary-table td { padding: 10px 0; font-size: 11px; }
.summary-table td.label {
    color: #666666;
    text-align: right;
    padding-right: 20px;
}
.summary-table td.value {
    text-align: right;
    color: #1c1c1e;
    font-weight: bold;
}
.summary-total td {
    padding-top: 14px;
    border-top: 1px solid #e0e0e0;
    font-size: 15px;
    font-weight: bold;
    color: #1c1c1e;
}

.footer-divider {
    border: none;
    border-top: 1px solid #e0e0e0;
    margin: 36px 0 24px 0;
}
.footer-table {
    width: 100%;
}
.footer-table td { vertical-align: top; }
.footer-label {
    font-size: 10px;
    color: #888888;
    letter-spacing: 0.3px;
}
.footer-value {
    font-size: 11px;
    color: #333333;
    margin-top: 6px;
    font-style: italic;
}
.status-badge {
    display: inline-block;
    padding: 6px 18px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: bold;
}
.status-unpaid  { background-color: #fee2e2; color: #b91c1c; }
.status-paid    { background-color: #dcfce7; color: #166534; }
.status-partial { background-color: #fef3c7; color: #92400e; }
.status-overdue { background-color: #fee2e2; color: #b91c1c; }
</style>
</head>
<body>

{{-- ── HEADER ── --}}
<table class="header-table">
    <tr>
        <td width="50%">
            <div class="workspace-name">{{ $hero->full_name ?? 'Your Workspace' }}</div>
            <div class="workspace-email">{{ $hero->email_display ?? '' }}</div>
        </td>
        <td width="50%">
            <div class="invoice-title-ar">فاتورة</div>
            <div class="invoice-label-en">Invoice</div>
            <div class="invoice-number">#{{ $invoice->invoice_number ?? 'INV-' . $invoice->id }}</div>
        </td>
    </tr>
</table>

{{-- ── META: PROJECT + DATES ── --}}
<table class="meta-table">
    <tr>
        <td width="50%">
            <div class="meta-label">للمشروع / PROJECT</div>
            <div class="meta-value">{{ $clientProject->title }}</div>
            <div class="meta-sub">{{ $customer->name }}</div>
        </td>
        <td width="50%" style="text-align:right;">
            <div class="meta-label">تاريخ الإصدار / ISSUE DATE</div>
            <div class="meta-value">{{ $invoice->created_at->format('d/m/Y') }}</div>
        </td>
    </tr>
    <tr>
        <td></td>
        <td style="text-align:right;">
            <div class="meta-label">تاريخ الاستحقاق / DUE DATE</div>
            <div class="meta-value">{{ $invoice->due_date ? $invoice->due_date->format('d/m/Y') : 'dd/mm/yyyy' }}</div>
        </td>
    </tr>
</table>

{{-- ── LINE ITEMS ── --}}
<table class="items-table">
    <thead>
        <tr>
            <th style="text-align:left;" width="60%">الوصف / Description</th>
            <th style="text-align:center;" width="20%">العملة / Currency</th>
            <th style="text-align:right;" width="20%">المبلغ / Amount</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ $invoice->notes ?: $clientProject->title }}</td>
            <td class="currency">{{ $invoice->currency }}</td>
            <td class="amount">{{ number_format((float) $invoice->amount, 2) }}</td>
        </tr>
    </tbody>
</table>

{{-- ── SUMMARY ── --}}
<table class="summary-table">
    <tr>
        <td class="label">المجموع الفرعي / Subtotal</td>
        <td class="value">{{ number_format((float) $invoice->amount, 2) }} {{ $invoice->currency }}</td>
    </tr>
    <tr>
        <td class="label">ضريبة / VAT {{ rtrim(rtrim(number_format((float) $invoice->vat_rate, 2), '0'), '.') }}%</td>
        <td class="value">{{ number_format($invoice->vat_amount, 2) }} {{ $invoice->currency }}</td>
    </tr>
    <tr class="summary-total">
        <td class="label">الإجمالي / Total</td>
        <td class="value">{{ number_format($invoice->total, 2) }} {{ $invoice->currency }}</td>
    </tr>
</table>

{{-- ── FOOTER ── --}}
<hr class="footer-divider">
<table class="footer-table">
    <tr>
        <td width="60%">
            <p class="footer-label" style="margin:0 0 8px 0;">ملاحظات / NOTES</p>
            <p class="footer-value" style="margin:0;">{{ $invoice->notes ?: '—' }}</p>
        </td>
        <td width="40%" style="text-align:right;">
            @php
                $statusClass = match($invoice->status) {
                    \App\Enums\InvoiceStatus::Paid    => 'status-paid',
                    \App\Enums\InvoiceStatus::Partial => 'status-partial',
                    default => $invoice->isOverdue() ? 'status-overdue' : 'status-unpaid',
                };
                $statusLabel = $invoice->status === \App\Enums\InvoiceStatus::Paid
                    ? 'Paid / مدفوعة'
                    : ($invoice->status === \App\Enums\InvoiceStatus::Partial
                        ? 'Partial / جزئي'
                        : ($invoice->isOverdue() ? 'Overdue / متأخرة' : 'Unpaid / غير مدفوعة'));
            @endphp
            <table style="width:100%; border-collapse:collapse;">
                <tr><td style="text-align:right; padding:0 0 12px 0;"><span class="footer-label">الحالة / STATUS</span></td></tr>
                <tr><td style="text-align:right; padding:0;"><span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span></td></tr>
                @if($invoice->paid_at)
                <tr><td style="text-align:right; padding:10px 0 0 0; font-size:9px; color:#888888;">Paid on {{ $invoice->paid_at->format('d/m/Y') }}</td></tr>
                @endif
            </table>
        </td>
    </tr>
</table>

</body>
</html>
