<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\SiteMedia;
use App\Settings\AboutSettings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AboutSettingController extends Controller
{
    public function edit(AboutSettings $settings): View
    {
        $siteMedia = SiteMedia::instance('about');

        return view('dashboard.settings.about', [
            'settings' => $settings,
            'aboutPhoto' => $siteMedia->getFirstMediaUrl('about_photo'),
        ]);
    }

    public function update(Request $request, AboutSettings $settings): RedirectResponse
    {
        $validated = $request->validate([
            'bio_full' => ['required', 'string', 'max:10000'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        $settings->bio_full = $validated['bio_full'];
        $settings->save();

        $siteMedia = SiteMedia::instance('about');

        if ($request->hasFile('photo')) {
            $siteMedia->addMediaFromRequest('photo')
                ->toMediaCollection('about_photo');
        }

        return redirect()
            ->route('backstage.settings.about.edit')
            ->with('success', 'About settings updated successfully.');
    }
}
