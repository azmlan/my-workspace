<section id="projects" class="py-20 lg:py-32 bg-gray-950">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 lg:mb-16">
            <h2 class="reveal text-3xl sm:text-4xl font-bold text-white mb-4">
                مشاريعي
            </h2>
            <p class="reveal text-gray-400 text-lg max-w-2xl mx-auto" style="transition-delay: 0.1s;">
                مجموعة مختارة من أعمالي ومشاريعي الشخصية
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
            @foreach($portfolioProjects as $index => $project)
                <article class="reveal group bg-gray-900 rounded-xl overflow-hidden border border-gray-800 hover:border-indigo-500/50 transition-all duration-300" style="transition-delay: {{ ($index % 3) * 0.1 }}s;">
                    @php
                        $projectImage = $project->getFirstMediaUrl('image');
                    @endphp
                    @if($projectImage)
                        <div class="aspect-video overflow-hidden">
                            <img
                                src="{{ $projectImage }}"
                                alt="{{ $project->title }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                            >
                        </div>
                    @else
                        <div class="aspect-video bg-gradient-to-br from-indigo-600 to-purple-700 flex items-center justify-center">
                            <svg class="w-12 h-12 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                            </svg>
                        </div>
                    @endif

                    <div class="p-6">
                        <div class="flex items-start justify-between gap-4 mb-3">
                            <h3 class="text-xl font-semibold text-white group-hover:text-indigo-400 transition-colors">
                                {{ $project->title }}
                            </h3>
                            @if($project->featured)
                                <span class="flex-shrink-0 px-2 py-1 text-xs font-medium bg-indigo-500/20 text-indigo-400 rounded">
                                    مميز
                                </span>
                            @endif
                        </div>

                        <p class="text-gray-400 text-sm mb-4 line-clamp-3">
                            {{ $project->description }}
                        </p>

                        @if($project->tech_tags && count($project->tech_tags) > 0)
                            <div class="flex flex-wrap gap-2 mb-4">
                                @foreach($project->tech_tags as $tag)
                                    <span class="px-2 py-1 text-xs font-medium bg-gray-800 text-gray-300 rounded">
                                        {{ $tag }}
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        <div class="flex items-center gap-4">
                            @if($project->live_url)
                                <a
                                    href="{{ $project->live_url }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="inline-flex items-center text-sm text-indigo-400 hover:text-indigo-300 transition-colors"
                                >
                                    <x-heroicon-o-arrow-top-right-on-square class="w-4 h-4 ml-1" />
                                    معاينة
                                </a>
                            @endif

                            @if($project->github_url)
                                <a
                                    href="{{ $project->github_url }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="inline-flex items-center text-sm text-gray-400 hover:text-white transition-colors"
                                >
                                    <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"></path>
                                    </svg>
                                    الكود
                                </a>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
