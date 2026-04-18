<?php

namespace App\Observers;

use App\Enums\ClientProjectStatus;
use App\Models\AuditLog;
use App\Models\ClientProject;

class ClientProjectObserver
{
    public function created(ClientProject $clientProject): void
    {
        AuditLog::log(
            action: 'created',
            subjectType: 'ClientProject',
            subjectId: $clientProject->id,
            subjectLabel: $clientProject->title,
        );
    }

    public function updated(ClientProject $clientProject): void
    {
        if ($clientProject->wasChanged('status')) {
            if ($clientProject->status === ClientProjectStatus::Cancelled) {
                AuditLog::log(
                    action: 'cancelled',
                    subjectType: 'ClientProject',
                    subjectId: $clientProject->id,
                    subjectLabel: $clientProject->title,
                    meta: ['reason' => $clientProject->cancellation_reason],
                );
            } else {
                $oldStatus = ClientProjectStatus::from($clientProject->getOriginal('status'));
                AuditLog::log(
                    action: 'status_changed',
                    subjectType: 'ClientProject',
                    subjectId: $clientProject->id,
                    subjectLabel: $clientProject->title,
                    meta: [
                        'from' => $oldStatus->label(),
                        'to' => $clientProject->status->label(),
                    ],
                );
            }
        } else {
            AuditLog::log(
                action: 'updated',
                subjectType: 'ClientProject',
                subjectId: $clientProject->id,
                subjectLabel: $clientProject->title,
            );
        }
    }

    public function deleted(ClientProject $clientProject): void
    {
        AuditLog::log(
            action: 'deleted',
            subjectType: 'ClientProject',
            subjectId: $clientProject->id,
            subjectLabel: $clientProject->title,
        );
    }
}
