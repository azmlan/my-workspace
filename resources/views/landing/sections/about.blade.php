<section id="about" class="py-20 lg:py-32 bg-gray-900">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-16">
            @php
                $aboutPhoto = $siteMedia->getFirstMediaUrl('about_photo');
            @endphp
            @if($aboutPhoto)
                <div class="reveal flex-shrink-0 lg:order-2">
                    <div class="w-64 h-64 sm:w-80 sm:h-80 rounded-2xl overflow-hidden shadow-2xl">
                        <img
                            src="{{ $aboutPhoto }}"
                            alt="About {{ $heroSettings->full_name }}"
                            class="w-full h-full object-cover"
                        >
                    </div>
                </div>
            @endif

            <div class="flex-1 lg:order-1">
                <h2 class="reveal text-3xl sm:text-4xl font-bold text-white mb-6">
                    من انا
                </h2>

                <div class="reveal prose prose-lg prose-invert max-w-none" style="transition-delay: 0.1s;">
                    {!! nl2br(e($aboutSettings->bio_full)) !!}
                </div>
            </div>
        </div>
    </div>
</section>
