<section id="hero" class="min-h-screen flex items-center justify-center pt-16 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-gray-950 via-gray-900 to-gray-950"></div>
    <div class="absolute inset-0 opacity-20">
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-indigo-500 rounded-full filter blur-3xl"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-purple-500 rounded-full filter blur-3xl"></div>
    </div>

    <div class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-16">
            @php
                $heroPhoto = $siteMedia->getFirstMediaUrl('hero_photo');
            @endphp
            @if($heroPhoto)
                <div class="reveal flex-shrink-0">
                    <div class="w-48 h-48 sm:w-64 sm:h-64 lg:w-80 lg:h-80 rounded-full overflow-hidden ring-4 ring-indigo-500/30 ring-offset-4 ring-offset-gray-950">
                        <img
                            src="{{ $heroPhoto }}"
                            alt="{{ $heroSettings->full_name }}"
                            class="w-full h-full object-cover"
                        >
                    </div>
                </div>
            @endif

            <div class="text-center lg:text-right">
                <h1 class="reveal text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-4">
                    {{ $heroSettings->full_name }}
                </h1>

                <p class="reveal text-xl sm:text-2xl text-indigo-400 mb-6" style="transition-delay: 0.1s;">
                    {{ $heroSettings->tagline }}
                </p>

                @if($heroSettings->bio_short)
                    <p class="reveal text-gray-400 text-lg max-w-xl mb-8" style="transition-delay: 0.2s;">
                        {{ $heroSettings->bio_short }}
                    </p>
                @endif

                <div class="reveal flex flex-wrap items-center justify-center lg:justify-end gap-4" style="transition-delay: 0.3s;">
                    @php
                        $cvFile = $siteMedia->getFirstMediaUrl('hero_cv');
                    @endphp
                    @if($cvFile)
                        <a
                            href="{{ $cvFile }}"
                            download
                            class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors"
                        >
                            <x-heroicon-o-document-arrow-down class="w-5 h-5 ml-2" />
                            تحميل السيرة الذاتية
                        </a>
                    @endif


                </div>

                <div class="reveal flex items-center justify-center lg:justify-end gap-5 mt-8" style="transition-delay: 0.4s;">
                    @if($heroSettings->github_url)
                        <a
                            href="{{ $heroSettings->github_url }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="text-gray-400 hover:text-white transition-colors"
                            aria-label="GitHub"
                        >
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"></path>
                            </svg>
                        </a>
                    @endif

                    @if($heroSettings->linkedin_url)
                        <a
                            href="{{ $heroSettings->linkedin_url }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="text-gray-400 hover:text-white transition-colors"
                            aria-label="LinkedIn"
                        >
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"></path>
                            </svg>
                        </a>
                    @endif

                    @if($heroSettings->twitter_url)
                        <a
                            href="{{ $heroSettings->twitter_url }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="text-gray-400 hover:text-white transition-colors"
                            aria-label="Twitter"
                        >
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"></path>
                            </svg>
                        </a>
                    @endif

                    @if($heroSettings->email_display)
                        <a
                            href="mailto:{{ $heroSettings->email_display }}"
                            class="text-gray-400 hover:text-white transition-colors"
                            aria-label="Email"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
        <a href="#about" class="text-gray-400 hover:text-white transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </a>
    </div>
</section>
