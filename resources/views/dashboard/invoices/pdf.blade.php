<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
@page {
    background-color: #1c1c1e;
}
body {
    background-color: #1c1c1e;
    color: #ffffff;
    font-family: 'cairo', sans-serif;
    font-size: 11px;
    line-height: 1.5;
}

.header-table { width: 100%; margin-bottom: 28px; }
.header-table td { vertical-align: top; }
.workspace-name {
    font-size: 15px;
    font-weight: bold;
    color: #ffffff;
}
.workspace-email {
    font-size: 10px;
    color: #9a9a9f;
    margin-top: 3px;
}
.invoice-title-ar {
    font-size: 28px;
    font-weight: bold;
    color: #ffffff;
    text-align: right;
}
.invoice-label-en {
    font-size: 11px;
    color: #9a9a9f;
    text-align: right;
    margin-top: 2px;
}
.invoice-number {
    font-size: 15px;
    font-weight: bold;
    color: #ffffff;
    text-align: right;
    margin-top: 8px;
}

.meta-table { width: 100%; margin-bottom: 22px; }
.meta-table td { vertical-align: top; padding: 6px 0; }
.meta-label {
    font-size: 10px;
    color: #9a9a9f;
    letter-spacing: 0.3px;
}
.meta-value {
    font-size: 14px;
    font-weight: bold;
    color: #ffffff;
    margin-top: 4px;
}
.meta-sub {
    font-size: 11px;
    color: #c8c8cc;
    margin-top: 1px;
}

.items-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 18px;
    border: 1px solid #3a3a3d;
}
.items-table thead tr { background-color: #252528; }
.items-table th {
    padding: 10px 14px;
    font-size: 10px;
    font-weight: bold;
    color: #9a9a9f;
    letter-spacing: 0.3px;
    border-bottom: 1px solid #3a3a3d;
}
.items-table td {
    padding: 14px;
    color: #e5e5e7;
    vertical-align: top;
    font-size: 11px;
}
.items-table td.amount {
    text-align: right;
    font-weight: bold;
    color: #ffffff;
}
.items-table td.currency { text-align: center; color: #c8c8cc; }

.summary-table {
    width: 70%;
    margin-left: 30%;
    border-collapse: collapse;
    margin-bottom: 28px;
}
.summary-table td { padding: 6px 0; font-size: 11px; }
.summary-table td.label {
    color: #c8c8cc;
    text-align: right;
    padding-right: 18px;
}
.summary-table td.value {
    text-align: right;
    color: #ffffff;
    font-weight: bold;
}
.summary-total td {
    padding-top: 10px;
    border-top: 1px solid #3a3a3d;
    font-size: 14px;
    font-weight: bold;
    color: #ffffff;
}

.footer-divider {
    border: none;
    border-top: 1px solid #3a3a3d;
    margin: 18px 0 24px 0;
}
.footer-table {
    width: 100%;
}
.footer-table td { vertical-align: top; }
.footer-label {
    font-size: 10px;
    color: #9a9a9f;
    letter-spacing: 0.3px;
}
.footer-value {
    font-size: 11px;
    color: #e5e5e7;
    margin-top: 6px;
    font-style: italic;
}
.status-badge {
    display: inline-block;
    padding: 5px 16px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: bold;
    margin-top: 8px;
}
.status-unpaid  { background-color: #3a1f1f; color: #fca5a5; border: 1px solid #7f1d1d; }
.status-paid    { background-color: #15351f; color: #86efac; border: 1px solid #166534; }
.status-partial { background-color: #3a2a0a; color: #fcd34d; border: 1px solid #78350f; }
.status-overdue { background-color: #3a1f1f; color: #fca5a5; border: 1px solid #7f1d1d; }
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
            <p class="footer-label" style="margin:0 0 6px 0;">ملاحظات / NOTES</p>
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
                <tr><td style="text-align:right; padding:0 0 10px 0;"><span class="footer-label">الحالة / STATUS</span></td></tr>
                <tr><td style="text-align:right; padding:0;"><span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span></td></tr>
                @if($invoice->paid_at)
                <tr><td style="text-align:right; padding:8px 0 0 0; font-size:9px; color:#9a9a9f;">Paid on {{ $invoice->paid_at->format('d/m/Y') }}</td></tr>
                @endif
            </table>
        </td>
    </tr>
</table>

</body>
</html>
