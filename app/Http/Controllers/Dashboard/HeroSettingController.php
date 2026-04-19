<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\SiteMedia;
use App\Settings\HeroSettings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HeroSettingController extends Controller
{
    public function edit(HeroSettings $settings): View
    {
        $siteMedia = SiteMedia::instance('hero');

        return view('dashboard.settings.hero', [
            'settings' => $settings,
            'heroPhoto' => $siteMedia->getFirstMediaUrl('hero_photo'),
            'cvFile' => $siteMedia->getFirstMedia('hero_cv'),
        ]);
    }

    public function update(Request $request, HeroSettings $settings): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'tagline' => ['required', 'string', 'max:255'],
            'bio_short' => ['required', 'string', 'max:1000'],
            'github_url' => ['nullable', 'url', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'twitter_url' => ['nullable', 'url', 'max:255'],
            'email_display' => ['nullable', 'email', 'max:255'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'cv_file' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
        ]);

        $settings->full_name = $validated['full_name'];
        $settings->tagline = $validated['tagline'];
        $settings->bio_short = $validated['bio_short'];
        $settings->github_url = $validated['github_url'] ?? null;
        $settings->linkedin_url = $validated['linkedin_url'] ?? null;
        $settings->twitter_url = $validated['twitter_url'] ?? null;
        $settings->email_display = $validated['email_display'] ?? null;
        $settings->save();

        $siteMedia = SiteMedia::instance('hero');

        if ($request->hasFile('photo')) {
            $siteMedia->addMediaFromRequest('photo')
                ->toMediaCollection('hero_photo');
        }

        if ($request->hasFile('cv_file')) {
            $siteMedia->addMediaFromRequest('cv_file')
                ->toMediaCollection('hero_cv');
        }

        return redirect()
            ->route('backstage.settings.hero.edit')
            ->with('success', 'Hero settings updated successfully.');
    }
}
