<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'HR System') }} - {{ __('Employee Profile') }}</title>
    @if(app()->getLocale() == 'ar')
        <link href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@400;700&display=swap" rel="stylesheet">
        <style>body { font-family: 'Noto Kufi Arabic', sans-serif; }</style>
    @else
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
        <style>body { font-family: 'Inter', sans-serif; }</style>
    @endif
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased min-h-screen flex flex-row" x-data="{ open: false }">
    <div class="md:hidden p-4 bg-white border-b border-gray-100 flex items-center justify-between w-full fixed top-0 z-20">
        <a href="{{ route('employee.dashboard') }}" class="text-xl font-bold text-secondary tracking-tight">{{ config('app.name') }}</a>
        <div class="flex items-center gap-4">
            <x-notification-bell :count="$employee_notifications_count ?? 0" />
            
            <a href="/lang/{{ app()->getLocale() == 'ar' ? 'en' : 'ar' }}" 
               class="px-3 py-1.5 bg-gray-50 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-blue-600 border border-gray-100 transition-all">
                {{ app()->getLocale() == 'ar' ? 'English' : 'عربي' }}
            </a>

            <button @click="open = !open" class="p-2 text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
        </div>
    </div>
    <aside :class="open ? 'translate-x-0' : 'ltr:-translate-x-full rtl:translate-x-full'" class="fixed md:sticky top-0 inset-y-0 z-30 w-64 bg-white md:translate-x-0 transition-transform duration-300 md:flex flex-col border-e border-gray-100 hidden">
        <div class="p-6">
            <span class="text-xl font-bold text-secondary tracking-tight">{{ config('app.name') }}</span>
        </div>
        <nav class="flex-grow px-4 space-y-1" x-data="{ collapsed: false }">
            <a href="{{ route('employee.dashboard') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('employee.dashboard*') ? 'bg-primary text-secondary' : '' }}">{{ __('Dashboard') }}</a>
            <a href="{{ route('employee.payslips.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('employee.payslips.*') ? 'bg-primary text-secondary' : '' }}">{{ __('Payslips') }}</a>
        </nav>
        <div class="p-4 border-t border-gray-50 space-y-2">
            <a href="/lang/{{ app()->getLocale() == 'ar' ? 'en' : 'ar' }}" 
               class="flex items-center justify-between px-4 py-2.5 text-xs font-bold text-gray-500 hover:bg-gray-50 rounded-xl transition-colors border border-transparent hover:border-gray-100">
                <span class="uppercase tracking-widest">{{ app()->getLocale() == 'ar' ? 'English Version' : 'النسخة العربية' }}</span>
                <svg class="w-4 h-4 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 11.37 9.188 15.287 5.719 18M15 9h4.511C14.183 13.061 9.472 15.562 3 17.511"></path></svg>
            </a>
            <form method="POST" action="/logout">
                @csrf
                <button type="submit" class="w-full flex items-center justify-between px-4 py-2.5 text-xs font-bold text-red-500 hover:bg-red-50 rounded-xl transition-all border border-transparent hover:border-red-100 group">
                    <span class="uppercase tracking-widest">{{ __('Logout') }}</span>
                    <svg class="w-4 h-4 group-hover:translate-x-1 rtl:group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                </button>
            </form>
        </div>
    </aside>
    <main class="flex-1 w-full mt-16 md:mt-0 p-6 pb-24 md:pb-6 overflow-y-auto h-screen">
        <div class="max-w-full mx-auto px-4 md:px-10">
            <div class="hidden md:flex items-center justify-end gap-4 mb-6">
                <a href="/lang/{{ app()->getLocale() == 'ar' ? 'en' : 'ar' }}" 
                   class="px-4 py-2 bg-white rounded-[1.25rem] shadow-sm text-xs font-black uppercase tracking-widest text-gray-500 hover:text-secondary border border-gray-100 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 11.37 9.188 15.287 5.719 18M15 9h4.511C14.183 13.061 9.472 15.562 3 17.511"></path></svg>
                    {{ app()->getLocale() == 'ar' ? 'English' : 'اللغة العربية' }}
                </a>
                <x-notification-bell :count="$employee_notifications_count ?? 0" />
            </div>
            @yield('content')
        </div>
    </main>
    <x-mobile-nav />
    <x-notification-panel />
</body>
</html>
