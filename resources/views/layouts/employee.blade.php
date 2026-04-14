<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'HR System') }} - {{ __('messages.employee_portal') }}</title>

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
            font-size: 1.05rem;
            line-height: 1.8;
        }
        h1, h2, h3, h4, h5, h6, .font-bold, .font-black {
            font-weight: 700 !important;
        }
        @endif
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased min-h-screen flex flex-col" x-data="{ mobileMenu: false }">
    @if(Session::has('impersonated_by_admin'))
        <div class="bg-secondary text-white py-3 px-6 shadow-xl relative z-[60] flex items-center justify-between border-b border-white/10">
            <div class="flex items-center gap-4">
                <div class="w-2 h-2 rounded-full bg-primary animate-ping"></div>
                <span class="text-xs font-black uppercase tracking-widest">{{ __('messages.impersonating_mode') ?? 'وضع المحاكاة' }}</span>
                <span class="hidden md:inline-block h-4 w-px bg-white/20 mx-2"></span>
                <p class="text-[10px] font-bold text-white/70">{{ __('messages.session_as') ?? 'دخول كمسؤول لـ' }} <span class="text-primary font-black">{{ Auth::user()->name }}</span></p>
            </div>
            
            <form action="{{ route('impersonate.leave') }}" method="POST">
                @csrf
                <button type="submit" class="bg-primary text-secondary px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-white transition-all shadow-lg active:scale-95">
                    {{ __('messages.return_to_admin') ?? 'العودة للوحة التحكم' }}
                </button>
            </form>
        </div>
    @endif

    <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-100 py-3 px-6 shadow-sm">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-10 flex justify-between items-center h-16">
            
            <!-- Main Navigation Links (Start Side) -->
            <div class="hidden xl:flex items-center gap-8">
                <a href="{{ route('employee.dashboard') }}" class="text-sm font-bold {{ request()->routeIs('employee.dashboard') ? 'text-primary border-b-2 border-primary pb-1' : 'text-secondary hover:text-primary transition-all' }}">{{ __('messages.dashboard') }}</a>
                <a href="{{ route('employee.profile.index') }}" class="text-sm font-bold {{ request()->routeIs('employee.profile.*') ? 'text-primary border-b-2 border-primary pb-1' : 'text-secondary hover:text-primary transition-all' }}">{{ __('messages.profile') }}</a>
                <a href="{{ route('employee.payslips.index') }}" class="text-sm font-bold {{ request()->routeIs('employee.payslips.*') ? 'text-primary border-b-2 border-primary pb-1' : 'text-secondary hover:text-primary transition-all' }}">{{ __('messages.payslips') }}</a>
                <a href="{{ route('employee.deductions.index') }}" class="text-sm font-bold {{ request()->routeIs('employee.deductions.*') ? 'text-primary border-b-2 border-primary pb-1' : 'text-secondary hover:text-primary transition-all' }}">{{ __('messages.deductions') }}</a>
                <a href="{{ route('employee.tasks.index') }}" class="text-sm font-bold {{ request()->routeIs('employee.tasks.*') ? 'text-primary border-b-2 border-primary pb-1' : 'text-secondary hover:text-primary transition-all' }}">{{ __('messages.tasks') }}</a>
                <a href="{{ route('employee.assets.index') }}" class="text-sm font-bold {{ request()->routeIs('employee.assets.*') ? 'text-primary border-b-2 border-primary pb-1' : 'text-secondary hover:text-primary transition-all' }}">{{ __('messages.assets') }}</a>
                <a href="{{ route('employee.leaves.index') }}" class="text-sm font-bold {{ request()->routeIs('employee.leaves.*') ? 'text-primary border-b-2 border-primary pb-1' : 'text-secondary hover:text-primary transition-all' }}">{{ __('messages.leaves') }}</a>
                <a href="{{ route('employee.announcements.index') }}" class="text-sm font-bold {{ request()->routeIs('employee.announcements.*') ? 'text-primary border-b-2 border-primary pb-1' : 'text-secondary hover:text-primary transition-all' }}">{{ __('messages.announcements') }}</a>
            </div>

            <!-- Account Actions & Lang (End Side) -->
            <div class="flex items-center gap-6">
                <!-- Notification Bell -->
                <x-notification-bell :count="$employee_notifications_count ?? 0" />

                <!-- Logout Button (Desktop) -->
                <form method="POST" action="{{ route('logout') }}" class="hidden md:inline">
                    @csrf
                    <button type="submit" class="text-xs font-black uppercase text-red-500 hover:text-red-600 transition-colors">
                        {{ __('messages.logout') }}
                    </button>
                </form>

                <!-- Lang Switcher -->
                <div class="ps-6 border-s border-gray-100 flex items-center">
                    @if(app()->getLocale() == 'ar')
                        <a href="/lang/en" class="text-xs font-bold text-gray-400 hover:text-secondary transition-colors">{{ __('messages.english') }}</a>
                    @else
                        <a href="/lang/ar" class="text-xs font-bold text-gray-400 hover:text-secondary transition-colors">{{ __('messages.arabic') }}</a>
                    @endif
                </div>

                <!-- Mobile Menu Button -->
                <button @click="mobileMenu = !mobileMenu" class="xl:hidden p-2 text-gray-500 hover:text-secondary transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path x-show="!mobileMenu" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path><path x-show="mobileMenu" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div x-show="mobileMenu" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="xl:hidden bg-white border-t border-gray-50 py-4 px-6 space-y-2 shadow-xl">
            <a href="{{ route('employee.dashboard') }}" class="block py-3 text-base font-bold {{ request()->routeIs('employee.dashboard') ? 'text-primary' : 'text-gray-600' }}">{{ __('messages.dashboard') }}</a>
            <a href="{{ route('employee.profile.index') }}" class="block py-3 text-base font-bold {{ request()->routeIs('employee.profile.*') ? 'text-primary' : 'text-gray-600' }}">{{ __('messages.profile') }}</a>
            <a href="{{ route('employee.payslips.index') }}" class="block py-3 text-base font-bold {{ request()->routeIs('employee.payslips.*') ? 'text-primary' : 'text-gray-600' }}">{{ __('messages.payslips') }}</a>
            <a href="{{ route('employee.deductions.index') }}" class="block py-3 text-base font-bold {{ request()->routeIs('employee.deductions.*') ? 'text-primary' : 'text-gray-600' }}">{{ __('messages.deductions') }}</a>
            <a href="{{ route('employee.tasks.index') }}" class="block py-3 text-base font-bold {{ request()->routeIs('employee.tasks.*') ? 'text-primary' : 'text-gray-600' }}">{{ __('messages.tasks') }}</a>
            <a href="{{ route('employee.assets.index') }}" class="block py-3 text-base font-bold {{ request()->routeIs('employee.assets.*') ? 'text-primary' : 'text-gray-600' }}">{{ __('messages.assets') }}</a>
            <a href="{{ route('employee.leaves.index') }}" class="block py-3 text-base font-bold {{ request()->routeIs('employee.leaves.*') ? 'text-primary' : 'text-gray-600' }}">{{ __('messages.leaves') }}</a>
            <a href="{{ route('employee.announcements.index') }}" class="block py-3 text-base font-bold {{ request()->routeIs('employee.announcements.*') ? 'text-primary' : 'text-gray-600' }}">{{ __('messages.announcements') }}</a>
            
            <form method="POST" action="{{ route('logout') }}" class="pt-4 border-t border-gray-100">
                @csrf
                <button type="submit" class="w-full text-left py-3 text-base font-bold text-red-500">
                    {{ __('messages.logout') }}
                </button>
            </form>
        </div>
    </nav>

    <main class="flex-grow max-w-full mx-auto px-4 sm:px-6 lg:px-10 w-full mb-12">
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

    <x-notification-panel 
        :apiUrl="route('employee.notifications.api')" 
        :readUrl="url(request()->path() . '/notifications')" 
    />
    @stack('scripts')
</body>
</html>
