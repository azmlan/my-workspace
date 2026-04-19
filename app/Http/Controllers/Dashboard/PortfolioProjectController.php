<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\PortfolioProject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PortfolioProjectController extends Controller
{
    public function index(): View
    {
        $projects = PortfolioProject::orderBy('sort_order')->get();

        return view('dashboard.portfolio-projects.index', compact('projects'));
    }

    public function create(): View
    {
        return view('dashboard.portfolio-projects.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'tech_tags' => ['required', 'string'],
            'live_url' => ['nullable', 'url', 'max:255'],
            'github_url' => ['nullable', 'url', 'max:255'],
            'featured' => ['boolean'],
            'is_visible' => ['boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        $validated['tech_tags'] = array_map('trim', explode(',', $validated['tech_tags']));
        $validated['featured'] = $request->boolean('featured');
        $validated['is_visible'] = $request->boolean('is_visible');

        $project = PortfolioProject::create($validated);

        if ($request->hasFile('image')) {
            $project->addMediaFromRequest('image')
                ->toMediaCollection('image');
        }

        return redirect()
            ->route('backstage.portfolio-projects.index')
            ->with('success', 'Project created successfully.');
    }

    public function edit(PortfolioProject $portfolioProject): View
    {
        return view('dashboard.portfolio-projects.edit', [
            'project' => $portfolioProject,
            'projectImage' => $portfolioProject->getFirstMediaUrl('image'),
        ]);
    }

    public function update(Request $request, PortfolioProject $portfolioProject): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'tech_tags' => ['required', 'string'],
            'live_url' => ['nullable', 'url', 'max:255'],
            'github_url' => ['nullable', 'url', 'max:255'],
            'featured' => ['boolean'],
            'is_visible' => ['boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        $validated['tech_tags'] = array_map('trim', explode(',', $validated['tech_tags']));
        $validated['featured'] = $request->boolean('featured');
        $validated['is_visible'] = $request->boolean('is_visible');

        $portfolioProject->update($validated);

        if ($request->hasFile('image')) {
            $portfolioProject->addMediaFromRequest('image')
                ->toMediaCollection('image');
        }

        return redirect()
            ->route('backstage.portfolio-projects.index')
            ->with('success', 'Project updated successfully.');
    }

    public function destroy(PortfolioProject $portfolioProject): RedirectResponse
    {
        $portfolioProject->delete();

        return redirect()
            ->route('backstage.portfolio-projects.index')
            ->with('success', 'Project deleted successfully.');
    }
}
