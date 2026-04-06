<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'HR System') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    
    @if(app()->getLocale() == 'ar')
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap" rel="stylesheet">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        [x-cloak] { display: none !important; }
        
        @if(app()->getLocale() == 'ar')
        :root {
            --font-sans: "Cairo", sans-serif;
        }
        body {
            font-family: "Cairo", sans-serif !important;
            font-size: 1.05rem; /* Slight increase for Arabic readability */
            line-height: 1.8;   /* Better breathing room for Arabic scripts */
        }
        h1, h2, h3, h4, h5, h6, .font-bold, .font-black {
            font-weight: 700 !important;
        }
        @endif
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased min-h-screen flex flex-col">

    <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-100 py-3 px-6 shadow-sm">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-10 flex justify-between items-center">
            <!-- Brand / Logo -->
            <div class="flex items-center">
                <a href="/" class="group flex items-center gap-3">
                    <div class="bg-primary/10 p-2 rounded-lg group-hover:bg-primary/20 transition-colors">
                        <x-application-logo class="w-7 h-7 text-secondary" />
                    </div>
                </a>
            </div>

            <!-- Nav Links & Switcher -->
            <div class="flex items-center gap-8">
                
                <!-- Main Navigation -->
                <div class="hidden md:flex items-center gap-6">
                    @auth
                        @if(Auth::user()->isSuperAdmin())
                            <a href="/admin/clients" class="text-sm font-semibold text-gray-600 hover:text-secondary hover:underline decoration-primary decoration-2 underline-offset-8 transition-all">{{ __('messages.clients') }}</a>
                        @elseif(Auth::user()->isClient())
                            <a href="/client/dashboard" class="text-sm font-semibold {{ request()->is('client/dashboard') ? 'text-secondary underline decoration-primary decoration-2 underline-offset-8' : 'text-gray-500 hover:text-secondary' }} transition-all">{{ __('messages.dashboard') }}</a>
                            <a href="{{ route('client.attendance.index') }}" class="text-sm font-semibold {{ request()->routeIs('client.attendance.*') ? 'text-secondary underline decoration-primary decoration-2 underline-offset-8' : 'text-gray-500 hover:text-secondary' }} transition-all">{{ __('messages.attendance') }}</a>
                            <a href="{{ route('client.tasks.index') }}" class="text-sm font-semibold {{ request()->routeIs('client.tasks.*') ? 'text-secondary underline decoration-primary decoration-2 underline-offset-8' : 'text-gray-500 hover:text-secondary' }} transition-all">{{ __('messages.tasks') }}</a>
                            <a href="{{ route('client.assets.index') }}" class="text-sm font-semibold {{ request()->routeIs('client.assets.*') ? 'text-secondary underline decoration-primary decoration-2 underline-offset-8' : 'text-gray-500 hover:text-secondary' }} transition-all">{{ __('messages.assets') }}</a>
                            <a href="{{ route('client.leaves.index') }}" class="text-sm font-semibold {{ request()->routeIs('client.leaves.*') ? 'text-secondary underline decoration-primary decoration-2 underline-offset-8' : 'text-gray-500 hover:text-secondary' }} transition-all">{{ __('messages.leaves_management') }}</a>
                        @elseif(Auth::user()->isEmployee())
                            <a href="/employee/dashboard" class="text-sm font-semibold {{ request()->is('employee/dashboard') ? 'text-secondary underline decoration-primary decoration-2 underline-offset-8' : 'text-gray-500 hover:text-secondary' }} transition-all">{{ __('messages.dashboard') }}</a>
                        @endif
                    @endauth
                </div>

                <div class="flex items-center gap-4 border-s border-gray-100 ps-4">
                    @auth
                        <form method="POST" action="/logout" class="inline">
                            @csrf
                            <button type="submit" class="text-xs font-bold uppercase tracking-wider text-red-500 hover:text-red-600 transition-colors px-3 py-1.5 rounded-full hover:bg-red-50">
                                {{ __('messages.logout') }}
                            </button>
                        </form>
                    @endauth

                    <!-- Lang Switcher -->
                    <div class="px-2">
                        @if(app()->getLocale() == 'ar')
                            <a href="/lang/en" class="text-xs font-bold text-gray-400 hover:text-secondary transition-colors">EN</a>
                        @else
                            <a href="/lang/ar" class="text-xs font-bold text-gray-400 hover:text-secondary transition-colors">عربي</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow max-w-full mx-auto px-4 sm:px-6 lg:px-10 w-full">
        @yield('content')
    </main>

    <footer class="mt-auto py-8 bg-white border-t border-gray-50 text-center text-sm text-gray-400">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-10 flex flex-col md:flex-row justify-between items-center gap-4">
            <p>&copy; {{ date('Y') }} {{ __('messages.company_name_localized') }}. {{ __('messages.all_rights_reserved') }}</p>
            <div class="flex items-center gap-4">
                <a href="#" class="hover:text-secondary transition-colors">{{ __('messages.privacy_policy') }}</a>
                <a href="#" class="hover:text-secondary transition-colors">{{ __('messages.terms_of_service') }}</a>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
