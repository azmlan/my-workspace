<!DOCTYPE html>
<html lang="ar" dir="rtl" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ $heroSettings->tagline ?? config('app.name') }}">
    <title>{{ $heroSettings->full_name ?? config('app.name') }} </title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body, * {
            font-family: 'Tajawal', sans-serif;
        }
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }
        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body class="bg-gray-950 text-gray-100 antialiased" x-data="{ mobileMenuOpen: false }">
    <nav class="fixed top-0 left-0 right-0 z-50 bg-gray-950/90 backdrop-blur-md border-b border-gray-800/50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="#hero" class="text-xl font-semibold text-white" style="font-family: var(--font-heading);">
                    {{ $heroSettings->full_name ?? config('app.name') }}
                </a>

                <div class="hidden md:flex items-center gap-8">
                    <a href="#about" class="text-gray-300 hover:text-white transition-colors">من انا</a>
                    @if($portfolioProjects->isNotEmpty())
                        <a href="#projects" class="text-gray-300 hover:text-white transition-colors">المشاريع</a>
                    @endif
                    @if($services->isNotEmpty())
                        <a href="#services" class="text-gray-300 hover:text-white transition-colors">الخدمات</a>
                    @endif
                    @if($testimonials->isNotEmpty())
                        <a href="#testimonials" class="text-gray-300 hover:text-white transition-colors">آراء العملاء</a>
                    @endif
                    <a href="#contact" class="text-gray-300 hover:text-white transition-colors">تواصل معي</a>
                </div>

                <button
                    @click="mobileMenuOpen = !mobileMenuOpen"
                    class="md:hidden text-gray-300 hover:text-white"
                    aria-label="Toggle menu"
                >
                    <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <div
            x-show="mobileMenuOpen"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            x-cloak
            class="md:hidden bg-gray-900 border-b border-gray-800"
        >
            <div class="px-4 py-4 space-y-3">
                <a href="#about" @click="mobileMenuOpen = false" class="block text-gray-300 hover:text-white transition-colors">من انا</a>
                @if($portfolioProjects->isNotEmpty())
                    <a href="#projects" @click="mobileMenuOpen = false" class="block text-gray-300 hover:text-white transition-colors">المشاريع</a>
                @endif
                @if($services->isNotEmpty())
                    <a href="#services" @click="mobileMenuOpen = false" class="block text-gray-300 hover:text-white transition-colors">الخدمات</a>
                @endif
                @if($testimonials->isNotEmpty())
                    <a href="#testimonials" @click="mobileMenuOpen = false" class="block text-gray-300 hover:text-white transition-colors">آراء العملاء</a>
                @endif
                <a href="#contact" @click="mobileMenuOpen = false" class="block text-gray-300 hover:text-white transition-colors">تواصل معي</a>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="bg-gray-900 border-t border-gray-800 py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-gray-400">
            <p>جميع الحقوق محفوظة &copy; {{ date('Y') }} {{ $heroSettings->full_name ?? config('app.name') }}</p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const reveals = document.querySelectorAll('.reveal');

            function revealOnScroll() {
                reveals.forEach(function(element) {
                    const elementTop = element.getBoundingClientRect().top;
                    const windowHeight = window.innerHeight;
                    if (elementTop < windowHeight - 100) {
                        element.classList.add('active');
                    }
                });
            }

            revealOnScroll();
            window.addEventListener('scroll', revealOnScroll);
        });
    </script>
</body>
</html>
