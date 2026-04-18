<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\InvoiceStatus;
use App\Http\Controllers\Controller;
use App\Models\ClientProject;
use App\Models\Invoice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function create(ClientProject $clientProject): View|RedirectResponse
    {
        if ($clientProject->status === \App\Enums\ClientProjectStatus::Cancelled) {
            return redirect()
                ->route('dashboard.client-projects.show', $clientProject)
                ->with('info', 'لا يمكن إضافة فواتير لمشروع ملغي.');
        }

        $statuses = InvoiceStatus::cases();

        return view('dashboard.invoices.create', compact('clientProject', 'statuses'));
    }

    public function store(Request $request, ClientProject $clientProject): RedirectResponse
    {
        if ($clientProject->status === \App\Enums\ClientProjectStatus::Cancelled) {
            return redirect()
                ->route('dashboard.client-projects.show', $clientProject)
                ->with('info', 'لا يمكن إضافة فواتير لمشروع ملغي.');
        }

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'max:10'],
            'status' => ['required', Rule::enum(InvoiceStatus::class)],
            'due_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $validated['client_project_id'] = $clientProject->id;

        Invoice::create($validated);

        return redirect()
            ->route('dashboard.client-projects.edit', $clientProject)
            ->with('success', 'Invoice created successfully.');
    }

    public function edit(ClientProject $clientProject, Invoice $invoice): View
    {
        $statuses = InvoiceStatus::cases();

        return view('dashboard.invoices.edit', compact('clientProject', 'invoice', 'statuses'));
    }

    public function update(Request $request, ClientProject $clientProject, Invoice $invoice): RedirectResponse
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'max:10'],
            'status' => ['required', Rule::enum(InvoiceStatus::class)],
            'due_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $invoice->update($validated);

        return redirect()
            ->route('dashboard.client-projects.edit', $clientProject)
            ->with('success', 'Invoice updated successfully.');
    }

    public function destroy(ClientProject $clientProject, Invoice $invoice): RedirectResponse
    {
        $invoice->delete();

        return redirect()
            ->route('dashboard.client-projects.edit', $clientProject)
            ->with('success', 'Invoice deleted successfully.');
    }
}
