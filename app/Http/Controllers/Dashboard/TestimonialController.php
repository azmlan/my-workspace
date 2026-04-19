<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    public function index(): View
    {
        $testimonials = Testimonial::orderBy('sort_order')->get();

        return view('dashboard.testimonials.index', compact('testimonials'));
    }

    public function create(): View
    {
        return view('dashboard.testimonials.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'client_name' => ['required', 'string', 'max:255'],
            'client_role' => ['nullable', 'string', 'max:255'],
            'client_company' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'body' => ['required', 'string'],
            'rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'is_visible' => ['boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        $validated['is_visible'] = $request->boolean('is_visible');

        $testimonial = Testimonial::create($validated);

        if ($request->hasFile('photo')) {
            $testimonial->addMediaFromRequest('photo')
                ->toMediaCollection('photo');
        }

        return redirect()
            ->route('backstage.testimonials.index')
            ->with('success', 'Testimonial created successfully.');
    }

    public function edit(Testimonial $testimonial): View
    {
        return view('dashboard.testimonials.edit', [
            'testimonial' => $testimonial,
            'testimonialPhoto' => $testimonial->getFirstMediaUrl('photo'),
        ]);
    }

    public function update(Request $request, Testimonial $testimonial): RedirectResponse
    {
        $validated = $request->validate([
            'client_name' => ['required', 'string', 'max:255'],
            'client_role' => ['nullable', 'string', 'max:255'],
            'client_company' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'body' => ['required', 'string'],
            'rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'is_visible' => ['boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        $validated['is_visible'] = $request->boolean('is_visible');

        $testimonial->update($validated);

        if ($request->hasFile('photo')) {
            $testimonial->addMediaFromRequest('photo')
                ->toMediaCollection('photo');
        }

        return redirect()
            ->route('backstage.testimonials.index')
            ->with('success', 'Testimonial updated successfully.');
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->delete();

        return redirect()
            ->route('backstage.testimonials.index')
            ->with('success', 'Testimonial deleted successfully.');
    }
}
