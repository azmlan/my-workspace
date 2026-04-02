<?php

namespace App\Http\Controllers;

use App\Models\PortfolioProject;
use App\Models\Service;
use App\Models\SiteMedia;
use App\Models\Testimonial;
use App\Settings\AboutSettings;
use App\Settings\HeroSettings;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function index(HeroSettings $heroSettings, AboutSettings $aboutSettings): View
    {
        $siteMedia = SiteMedia::instance('main');

        $portfolioProjects = PortfolioProject::visible()
            ->ordered()
            ->with('media')
            ->get();

        $services = Service::visible()
            ->ordered()
            ->get();

        $testimonials = Testimonial::visible()
            ->ordered()
            ->with('media')
            ->get();

        return view('landing.index', [
            'heroSettings' => $heroSettings,
            'aboutSettings' => $aboutSettings,
            'siteMedia' => $siteMedia,
            'portfolioProjects' => $portfolioProjects,
            'services' => $services,
            'testimonials' => $testimonials,
        ]);
    }
}
