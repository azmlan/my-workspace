<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $query = Customer::withCount('clientProjects');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $customers = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('dashboard.customers.index', compact('customers'));
    }

    public function show(Customer $customer): View
    {
        $customer->load([
            'clientProjects' => fn ($q) => $q->with('invoices')->orderBy('created_at', 'desc'),
            'notes' => fn ($q) => $q->orderBy('created_at', 'desc'),
        ]);

        $invoicesSummary = [
            'total' => $customer->clientProjects->flatMap->invoices->sum('amount'),
            'paid' => $customer->clientProjects->flatMap->invoices->where('status', \App\Enums\InvoiceStatus::Paid)->sum('amount'),
            'unpaid' => $customer->clientProjects->flatMap->invoices->where('status', '!=', \App\Enums\InvoiceStatus::Paid)->sum('amount'),
        ];

        return view('dashboard.customers.show', compact('customer', 'invoicesSummary'));
    }

    public function create(): View
    {
        return view('dashboard.customers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'source' => ['nullable', 'string', 'max:255'],
            'notes_general' => ['nullable', 'string'],
        ]);

        $customer = Customer::create($validated);

        return redirect()
            ->route('backstage.customers.show', $customer)
            ->with('success', 'Customer created successfully.');
    }

    public function edit(Customer $customer): View
    {
        return view('dashboard.customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'source' => ['nullable', 'string', 'max:255'],
            'notes_general' => ['nullable', 'string'],
        ]);

        $customer->update($validated);

        return redirect()
            ->route('backstage.customers.show', $customer)
            ->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        $customer->delete();

        return redirect()
            ->route('backstage.customers.index')
            ->with('success', 'Customer deleted successfully.');
    }
}
