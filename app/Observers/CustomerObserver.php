<?php

namespace App\Observers;

use App\Models\AuditLog;
use App\Models\Customer;

class CustomerObserver
{
    public function created(Customer $customer): void
    {
        AuditLog::log(
            action: 'created',
            subjectType: 'Customer',
            subjectId: $customer->id,
            subjectLabel: $customer->name,
        );
    }

    public function updated(Customer $customer): void
    {
        AuditLog::log(
            action: 'updated',
            subjectType: 'Customer',
            subjectId: $customer->id,
            subjectLabel: $customer->name,
        );
    }

    public function deleted(Customer $customer): void
    {
        AuditLog::log(
            action: 'deleted',
            subjectType: 'Customer',
            subjectId: $customer->id,
            subjectLabel: $customer->name,
        );
    }
}
