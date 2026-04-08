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
<body class="bg-gray-50 text-gray-900 antialiased min-h-screen flex flex-col" x-data="{ mobileMenu: false }">

    <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-100 py-3 px-6 shadow-sm">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-10 flex justify-between items-center h-16">
            
            <!-- Main Navigation Links (Start Side) -->
            <div class="hidden md:flex items-center gap-8">
                @auth
                    @if(Auth::user()->isSuperAdmin())
                        <a href="/admin/clients" class="text-sm font-bold text-secondary hover:text-primary transition-all">{{ __('messages.clients') }}</a>
                    @elseif(Auth::user()->isClient())
                        <a href="/client/dashboard" class="text-sm font-bold {{ request()->is('client/dashboard') ? 'text-primary border-b-2 border-primary pb-1' : 'text-secondary hover:text-primary transition-all' }}">{{ __('messages.dashboard') }}</a>
                        <a href="{{ route('client.attendance.index') }}" class="text-sm font-bold {{ request()->routeIs('client.attendance.*') ? 'text-primary border-b-2 border-primary pb-1' : 'text-secondary hover:text-primary transition-all' }}">{{ __('messages.attendance') }}</a>
                        <a href="{{ route('client.tasks.index') }}" class="text-sm font-bold {{ request()->routeIs('client.tasks.*') ? 'text-primary border-b-2 border-primary pb-1' : 'text-secondary hover:text-primary transition-all' }}">{{ __('messages.tasks') }}</a>
                        <a href="{{ route('client.assets.index') }}" class="text-sm font-bold {{ request()->routeIs('client.assets.*') ? 'text-primary border-b-2 border-primary pb-1' : 'text-secondary hover:text-primary transition-all' }}">{{ __('messages.assets') }}</a>
                        <a href="{{ route('client.leaves.index') }}" class="text-sm font-bold {{ request()->routeIs('client.leaves.*') ? 'text-primary border-b-2 border-primary pb-1' : 'text-secondary hover:text-primary transition-all' }}">{{ __('messages.leaves_management') }}</a>
                        <a href="{{ route('client.announcements.index') }}" class="text-sm font-bold {{ request()->routeIs('client.announcements.*') ? 'text-primary border-b-2 border-primary pb-1' : 'text-secondary hover:text-primary transition-all' }}">{{ __('messages.announcements') }}</a>
                        <a href="{{ route('client.action-required.index') }}" class="text-sm font-bold {{ request()->routeIs('client.action-required.index') ? 'text-rose-500 border-b-2 border-rose-500 pb-1' : 'text-rose-500 hover:text-rose-600 transition-all font-black' }}">{{ __('messages.action_required') }}</a>
                    @elseif(Auth::user()->isEmployee())
                        <a href="/employee/dashboard" class="text-sm font-bold {{ request()->is('employee/dashboard') ? 'text-primary border-b-2 border-primary pb-1' : 'text-secondary hover:text-primary transition-all' }}">{{ __('messages.dashboard') }}</a>
                    @endif
                @endauth
            </div>

            <!-- Account Actions & Lang (End Side) -->
            <div class="flex items-center gap-6">
                @auth
                    @if(Auth::user()->isClient())
                        <x-notification-bell :count="$client_notifications_count ?? 0" />
                    @endif
                    <form method="POST" action="/logout" class="inline">
                        @csrf
                        <button type="submit" class="text-xs font-black uppercase text-red-500 hover:text-red-600 transition-colors">
                            {{ __('messages.logout') }}
                        </button>
                    </form>
                @endauth

                <!-- Lang Switcher -->
                <div class="ps-6 border-s border-gray-100 flex items-center">
                    @if(app()->getLocale() == 'ar')
                        <a href="/lang/en" class="text-xs font-bold text-gray-400 hover:text-secondary transition-colors">{{ __('messages.english') }}</a>
                    @else
                        <a href="/lang/ar" class="text-xs font-bold text-gray-400 hover:text-secondary transition-colors">{{ __('messages.arabic') }}</a>
                    @endif
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

    @if(auth()->check() && auth()->user()->isClient())
        <x-notification-panel 
            :apiUrl="route('client.notifications.api', ['client_slug' => auth()->user()->client->slug])" 
            :readUrl="url('/' . auth()->user()->client->slug . '/notifications')" 
        />
    @endif

    @stack('scripts')
</body>
</html>
