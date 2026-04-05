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
        <a href="{{ route('employee.dashboard') }}" class="text-xl font-bold text-blue-600 tracking-tight">{{ config('app.name') }}</a>
        <div class="flex items-center gap-2">
            <x-notification-bell :count="$employee_notifications_count ?? 0" />
            <button @click="open = !open" class="p-2 text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
        </div>
    </div>
    <aside :class="open ? 'translate-x-0' : 'ltr:-translate-x-full rtl:translate-x-full'" class="fixed md:sticky top-0 inset-y-0 z-30 w-64 bg-white md:translate-x-0 transition-transform duration-300 md:flex flex-col border-e border-gray-100 hidden">
        <div class="p-6">
            <span class="text-xl font-bold text-blue-600 tracking-tight">{{ config('app.name') }}</span>
        </div>
        <nav class="flex-grow px-4 space-y-1" x-data="{ collapsed: false }">
            <a href="{{ route('employee.dashboard') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('employee.dashboard*') ? 'bg-blue-50 text-blue-700' : '' }}">{{ __('Dashboard') }}</a>
            <a href="{{ route('employee.payslips.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('employee.payslips.*') ? 'bg-blue-50 text-blue-700' : '' }}">{{ __('Payslips') }}</a>
        </nav>
        <div class="p-4 border-t border-gray-50">
            <form method="POST" action="/logout">@csrf<button type="submit" class="w-full py-2 text-sm text-red-500 hover:bg-red-50 rounded-xl transition-colors">{{ __('Logout') }}</button></form>
        </div>
    </aside>
    <main class="flex-1 w-full mt-16 md:mt-0 p-6 pb-24 md:pb-6 overflow-y-auto h-screen">
        <div class="max-w-6xl mx-auto">
            <div class="hidden md:flex justify-end mb-6">
                <x-notification-bell :count="$employee_notifications_count ?? 0" />
            </div>
            @yield('content')
        </div>
    </main>
    <x-mobile-nav />
    <x-notification-panel />
</body>
</html>
