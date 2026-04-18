<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\ClientProjectStatus;
use App\Http\Controllers\Controller;
use App\Models\ClientProject;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        ]);

        $clientProject = ClientProject::create($validated);

        return redirect()
            ->route('dashboard.client-projects.edit', $clientProject)
            ->with('success', 'Project created successfully.');
    }

    public function show(ClientProject $clientProject): View
    {
        $clientProject->load(['customer', 'invoices', 'files']);

        return view('dashboard.client-projects.show', compact('clientProject'));
    }

    public function edit(ClientProject $clientProject): View|RedirectResponse
    {
        if ($clientProject->status === ClientProjectStatus::Cancelled) {
            return redirect()
                ->route('dashboard.client-projects.show', $clientProject)
                ->with('info', 'لا يمكن تعديل مشروع ملغي.');
        }

        $clientProject->load(['customer', 'invoices']);
        $customers = Customer::orderBy('name')->get();
        $statuses = ClientProjectStatus::cases();

        return view('dashboard.client-projects.edit', compact('clientProject', 'customers', 'statuses'));
    }

    public function update(Request $request, ClientProject $clientProject): RedirectResponse
    {
        if ($clientProject->status === ClientProjectStatus::Cancelled) {
            return redirect()
                ->route('dashboard.client-projects.show', $clientProject)
                ->with('info', 'لا يمكن تعديل مشروع ملغي.');
        }

        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'title' => ['required', 'string', 'max:255'],
            'status' => ['required', Rule::enum(ClientProjectStatus::class)],
            'type' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_date' => ['nullable', 'date'],
            'deadline' => ['nullable', 'date'],
        ]);

        $clientProject->update($validated);

        return redirect()
            ->route('dashboard.client-projects.edit', $clientProject)
            ->with('success', 'Project updated successfully.');
    }

    public function cancel(Request $request, ClientProject $clientProject): RedirectResponse
    {
        $request->validate([
            'cancellation_reason' => ['required', 'string'],
            'cancellation_document' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        $documentPath = null;
        if ($request->hasFile('cancellation_document')) {
            $documentPath = $request->file('cancellation_document')
                ->store('cancellation-documents/' . $clientProject->id, 'public');
        }

        $clientProject->update([
            'status' => ClientProjectStatus::Cancelled,
            'cancellation_reason' => $request->input('cancellation_reason'),
            'cancellation_document_path' => $documentPath,
        ]);

        return redirect()
            ->route('dashboard.client-projects.show', $clientProject)
            ->with('success', 'تم إلغاء المشروع.');
    }

    public function cancellationDocument(ClientProject $clientProject): mixed
    {
        if (!$clientProject->cancellation_document_path) {
            abort(404);
        }

        if (!Storage::disk('public')->exists($clientProject->cancellation_document_path)) {
            abort(404);
        }

        return Storage::disk('public')->response($clientProject->cancellation_document_path);
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
