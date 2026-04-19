<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\ClientProjectStatus;
use App\Enums\InvoiceStatus;
use App\Http\Controllers\Controller;
use App\Models\ClientProject;
use App\Models\Invoice;
use App\Settings\HeroSettings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf as Pdf;

class InvoiceController extends Controller
{
    public function create(ClientProject $clientProject): View|RedirectResponse
    {
        if ($clientProject->status === ClientProjectStatus::Cancelled) {
            return redirect()
                ->route('backstage.client-projects.show', $clientProject)
                ->with('info', 'لا يمكن إضافة فواتير لمشروع ملغي.');
        }

        return view('dashboard.invoices.create', compact('clientProject'));
    }

    public function store(Request $request, ClientProject $clientProject): RedirectResponse
    {
        if ($clientProject->status === ClientProjectStatus::Cancelled) {
            return redirect()
                ->route('backstage.client-projects.show', $clientProject)
                ->with('info', 'لا يمكن إضافة فواتير لمشروع ملغي.');
        }

        $validated = $request->validate([
            'amount'   => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'max:10'],
            'vat_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'due_date' => ['nullable', 'date'],
            'notes'    => ['nullable', 'string'],
        ]);

        $validated['client_project_id'] = $clientProject->id;
        $validated['status'] = InvoiceStatus::Unpaid->value;

        DB::transaction(function () use ($validated) {
            $year = now()->year;
            $last = Invoice::whereYear('created_at', $year)
                ->lockForUpdate()
                ->orderByDesc('id')
                ->value('invoice_number');

            $seq = $last ? ((int) substr($last, -4)) + 1 : 1;
            $validated['invoice_number'] = sprintf('INV-%d-%04d', $year, $seq);

            Invoice::create($validated);
        });

        return redirect()
            ->route('backstage.client-projects.show', $clientProject)
            ->with('success', 'تم إنشاء الفاتورة بنجاح.');
    }

    public function edit(ClientProject $clientProject, Invoice $invoice): View
    {
        return view('dashboard.invoices.edit', compact('clientProject', 'invoice'));
    }

    public function update(Request $request, ClientProject $clientProject, Invoice $invoice): RedirectResponse
    {
        $validated = $request->validate([
            'amount'   => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'max:10'],
            'vat_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'status'   => ['required', Rule::enum(InvoiceStatus::class)],
            'due_date' => ['nullable', 'date'],
            'notes'    => ['nullable', 'string'],
        ]);

        $invoice->update($validated);

        return redirect()
            ->route('backstage.client-projects.show', $clientProject)
            ->with('success', 'تم تحديث الفاتورة بنجاح.');
    }

    public function markAsPaid(ClientProject $clientProject, Invoice $invoice): RedirectResponse
    {
        $invoice->update(['status' => InvoiceStatus::Paid->value]);

        return redirect()
            ->route('backstage.client-projects.show', $clientProject)
            ->with('success', 'تم تحديد الفاتورة كمدفوعة.');
    }

    public function exportPdf(ClientProject $clientProject, Invoice $invoice): Response
    {
        $pdf = Pdf::loadView('dashboard.invoices.pdf', [
            'invoice'       => $invoice,
            'clientProject' => $clientProject,
            'customer'      => $clientProject->customer,
            'hero'          => app(HeroSettings::class),
        ], [], [
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font' => 'cairo',
        ]);

        $filename = ($invoice->invoice_number ?? 'invoice-' . $invoice->id) . '.pdf';

        return $pdf->download($filename);
    }

    public function destroy(ClientProject $clientProject, Invoice $invoice): RedirectResponse
    {
        $invoice->delete();

        return redirect()
            ->route('backstage.client-projects.show', $clientProject)
            ->with('success', 'تم حذف الفاتورة بنجاح.');
    }
}
