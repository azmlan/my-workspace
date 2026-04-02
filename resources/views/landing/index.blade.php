@extends('layouts.landing')

@section('content')
    @include('landing.sections.hero')

    @include('landing.sections.about')

    @if($portfolioProjects->isNotEmpty())
        @include('landing.sections.projects')
    @endif

    @if($services->isNotEmpty())
        @include('landing.sections.services')
    @endif

    @if($testimonials->isNotEmpty())
        @include('landing.sections.testimonials')
    @endif

    @include('landing.sections.contact')
@endsection
