<?php

namespace App\Providers;

use App\Models\ClientProject;
use App\Models\Customer;
use App\Models\Invoice;
use App\Observers\ClientProjectObserver;
use App\Observers\CustomerObserver;
use App\Observers\InvoiceObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        ClientProject::observe(ClientProjectObserver::class);
        Invoice::observe(InvoiceObserver::class);
        Customer::observe(CustomerObserver::class);
    }
}
