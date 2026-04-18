<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\ClientProjectStatus;
use App\Http\Controllers\Controller;
use App\Models\ClientProject;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ClientProjectController extends Controller
{
    public function index(Request $request): View
    {
        $query = ClientProject::with('customer');

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $clientProjects = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        $statuses = ClientProjectStatus::cases();

        return view('dashboard.client-projects.index', compact('clientProjects', 'statuses'));
    }

    public function create(Request $request): View
    {
        $customers = Customer::orderBy('name')->get();
        $statuses = ClientProjectStatus::cases();
        $selectedCustomerId = $request->input('customer_id');

        return view('dashboard.client-projects.create', compact('customers', 'statuses', 'selectedCustomerId'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'title' => ['required', 'string', 'max:255'],
            'status' => ['required', Rule::enum(ClientProjectStatus::class)],
            'type' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_date' => ['nullable', 'date'],
            'deadline' => ['nullable', 'date'],
            'cancellation_reason' => ['nullable', 'required_if:status,cancelled', 'string'],
        ]);

        if ($validated['status'] !== 'cancelled') {
            $validated['cancellation_reason'] = null;
        }

        $clientProject = ClientProject::create($validated);

        return redirect()
            ->route('dashboard.client-projects.edit', $clientProject)
            ->with('success', 'Project created successfully.');
    }

    public function show(ClientProject $clientProject): View
    {
        $clientProject->load(['customer', 'invoices']);

        return view('dashboard.client-projects.show', compact('clientProject'));
    }

    public function edit(ClientProject $clientProject): View
    {
        $clientProject->load(['customer', 'invoices']);
        $customers = Customer::orderBy('name')->get();
        $statuses = ClientProjectStatus::cases();

        return view('dashboard.client-projects.edit', compact('clientProject', 'customers', 'statuses'));
    }

    public function update(Request $request, ClientProject $clientProject): RedirectResponse
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'title' => ['required', 'string', 'max:255'],
            'status' => ['required', Rule::enum(ClientProjectStatus::class)],
            'type' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_date' => ['nullable', 'date'],
            'deadline' => ['nullable', 'date'],
            'cancellation_reason' => ['nullable', 'required_if:status,cancelled', 'string'],
        ]);

        if ($validated['status'] !== 'cancelled') {
            $validated['cancellation_reason'] = null;
        }

        $clientProject->update($validated);

        return redirect()
            ->route('dashboard.client-projects.edit', $clientProject)
            ->with('success', 'Project updated successfully.');
    }

    public function destroy(ClientProject $clientProject): RedirectResponse
    {
        $customerId = $clientProject->customer_id;
        $clientProject->delete();

        return redirect()
            ->route('dashboard.customers.show', $customerId)
            ->with('success', 'Project deleted successfully.');
    }
}
