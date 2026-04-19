<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Note;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function store(Request $request, Customer $customer): RedirectResponse
    {
        $validated = $request->validate([
            'body' => ['required', 'string'],
        ]);

        $validated['customer_id'] = $customer->id;

        Note::create($validated);

        return redirect()
            ->route('backstage.customers.show', $customer)
            ->with('success', 'Note added successfully.');
    }

    public function destroy(Customer $customer, Note $note): RedirectResponse
    {
        $note->delete();

        return redirect()
            ->route('backstage.customers.show', $customer)
            ->with('success', 'Note deleted successfully.');
    }
}
