<?php

namespace App\Observers;

use App\Models\AuditLog;
use App\Models\Invoice;

class InvoiceObserver
{
    public function created(Invoice $invoice): void
    {
        AuditLog::log(
            action: 'created',
            subjectType: 'Invoice',
            subjectId: $invoice->id,
            subjectLabel: "فاتورة #{$invoice->id} — {$invoice->clientProject->title}",
            meta: ['amount' => $invoice->amount, 'currency' => $invoice->currency],
        );
    }

    public function updated(Invoice $invoice): void
    {
        if ($invoice->wasChanged('status')) {
            $oldStatus = \App\Enums\InvoiceStatus::from($invoice->getOriginal('status'));
            AuditLog::log(
                action: 'status_changed',
                subjectType: 'Invoice',
                subjectId: $invoice->id,
                subjectLabel: "فاتورة #{$invoice->id} — {$invoice->clientProject->title}",
                meta: [
                    'from' => $oldStatus->label(),
                    'to' => $invoice->status->label(),
                ],
            );
        } else {
            AuditLog::log(
                action: 'updated',
                subjectType: 'Invoice',
                subjectId: $invoice->id,
                subjectLabel: "فاتورة #{$invoice->id} — {$invoice->clientProject->title}",
            );
        }
    }

    public function deleted(Invoice $invoice): void
    {
        AuditLog::log(
            action: 'deleted',
            subjectType: 'Invoice',
            subjectId: $invoice->id,
            subjectLabel: "فاتورة #{$invoice->id} — {$invoice->clientProject->title}",
        );
    }
}
