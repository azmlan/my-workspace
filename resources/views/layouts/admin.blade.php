<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة التحكم')</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body, * {
            font-family: 'Tajawal', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100" x-data="{ sidebarOpen: false }">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside
            class="fixed inset-y-0 right-0 z-50 w-64 bg-gray-800 transform transition-transform duration-200 ease-in-out lg:translate-x-0 lg:static lg:inset-0"
            :class="sidebarOpen ? 'translate-x-0' : 'translate-x-full'"
        >
            <div class="flex items-center justify-center h-16 bg-gray-900">
                <span class="text-white text-xl font-bold">اعمالي </span>
            </div>

            <nav class="mt-6">
                <a href="{{ route('backstage.home') }}" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 hover:text-white {{ request()->routeIs('backstage.home') ? 'bg-gray-700 text-white' : '' }}">
                    <x-heroicon-o-home class="w-5 h-5 ml-3" />
                    الرئيسية
                </a>

                <div class="mt-6 px-6">
                    <p class="text-xs font-semibold text-gray-400 tracking-wider">معرض الأعمال</p>
                </div>

                <a href="{{ route('backstage.settings.hero.edit') }}" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 hover:text-white {{ request()->routeIs('backstage.settings.hero.*') ? 'bg-gray-700 text-white' : '' }}">
                    <x-heroicon-o-star class="w-5 h-5 ml-3" />
                    إعدادات الواجهة
                </a>

                <a href="{{ route('backstage.settings.about.edit') }}" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 hover:text-white {{ request()->routeIs('backstage.settings.about.*') ? 'bg-gray-700 text-white' : '' }}">
                    <x-heroicon-o-user class="w-5 h-5 ml-3" />
                    إعدادات من انا
                </a>

                <a href="{{ route('backstage.portfolio-projects.index') }}" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 hover:text-white {{ request()->routeIs('backstage.portfolio-projects.*') ? 'bg-gray-700 text-white' : '' }}">
                    <x-heroicon-o-rectangle-stack class="w-5 h-5 ml-3" />
                    المشاريع
                </a>

                <a href="{{ route('backstage.services.index') }}" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 hover:text-white {{ request()->routeIs('backstage.services.*') ? 'bg-gray-700 text-white' : '' }}">
                    <x-heroicon-o-briefcase class="w-5 h-5 ml-3" />
                    الخدمات
                </a>

                <a href="{{ route('backstage.testimonials.index') }}" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 hover:text-white {{ request()->routeIs('backstage.testimonials.*') ? 'bg-gray-700 text-white' : '' }}">
                    <x-heroicon-o-chat-bubble-left-right class="w-5 h-5 ml-3" />
                    آراء العملاء
                </a>

                <div class="mt-6 px-6">
                    <p class="text-xs font-semibold text-gray-400 tracking-wider">إدارة العملاء</p>
                </div>

                <a href="{{ route('backstage.customers.index') }}" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 hover:text-white {{ request()->routeIs('backstage.customers.*') ? 'bg-gray-700 text-white' : '' }}">
                    <x-heroicon-o-users class="w-5 h-5 ml-3" />
                    العملاء
                </a>

                <a href="{{ route('backstage.client-projects.index') }}" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 hover:text-white {{ request()->routeIs('backstage.client-projects.*') ? 'bg-gray-700 text-white' : '' }}">
                    <x-heroicon-o-clipboard-document-list class="w-5 h-5 ml-3" />
                    مشاريع العملاء
                </a>

                <div class="mt-6 px-6">
                    <p class="text-xs font-semibold text-gray-400 tracking-wider">النظام</p>
                </div>

                <a href="{{ route('backstage.audit-logs.index') }}" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 hover:text-white {{ request()->routeIs('backstage.audit-logs.*') ? 'bg-gray-700 text-white' : '' }}">
                    <x-heroicon-o-clipboard-document-check class="w-5 h-5 ml-3" />
                    سجل الأحداث
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col lg:ml-0">
            <!-- Topbar -->
            <header class="bg-white shadow-sm h-16 flex items-center justify-between px-6">
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none lg:hidden">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>

                <div class="flex-1 lg:flex-none"></div>

                <div class="flex items-center gap-4">
                    <span class="text-gray-700">{{ auth()->user()->name ?? auth()->user()->email }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-600 hover:text-gray-900 focus:outline-none" title="تسجيل الخروج">
                            <x-heroicon-o-arrow-right-on-rectangle class="w-5 h-5" />
                        </button>
                    </form>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-6">
                @if (session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6">
                        {{ session('error') }}
                    </div>
                @endif

                @if (session('info'))
                    <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded mb-6">
                        {{ session('info') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Overlay for mobile -->
    <div
        x-show="sidebarOpen"
        @click="sidebarOpen = false"
        class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
        x-transition:enter="transition-opacity ease-linear duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    ></div>
</body>
</html>
