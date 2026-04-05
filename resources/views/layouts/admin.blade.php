<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'HR System') }} - {{ __('Super Admin') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@500;600;700&display=swap" rel="stylesheet">
    @if(app()->getLocale() == 'ar')
        <link href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet">
        <style>body { font-family: 'Noto Kufi Arabic', sans-serif; }</style>
    @else
        <style>body { font-family: 'Inter', sans-serif; }</style>
    @endif
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-surface text-text-main antialiased min-h-screen flex flex-row" x-data="{ mobileOpen: false }">
    <div class="md:hidden p-4 bg-white border-b border-gray-100 flex items-center justify-between w-full fixed top-0 z-20">
        <a href="{{ route('admin.dashboard') }}" class="text-xl font-outfit font-bold text-primary tracking-tight">{{ config('app.name', 'HR System') }}</a>
        <button @click="mobileOpen = !mobileOpen" class="p-2 text-gray-500 hover:text-gray-700 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>
    </div>
    <x-sidebar />
    <div x-show="mobileOpen" class="fixed inset-0 z-40 bg-gray-900 bg-opacity-50 md:hidden" @click="mobileOpen = false" x-transition.opacity x-cloak></div>
    <div x-show="mobileOpen" x-transition:enter="transition ease-out duration-300" 
         x-transition:enter-start="{{ app()->getLocale() == 'ar' ? 'translate-x-full' : '-translate-x-full' }}" 
         x-transition:enter-end="translate-x-0" 
         x-data="{ collapsed: false }"
         class="fixed top-0 inset-y-0 z-50 w-64 bg-white md:hidden flex flex-col {{ app()->getLocale() == 'ar' ? 'right-0' : 'left-0' }}" x-cloak>
        <div class="h-16 flex items-center justify-start px-4 border-b border-gray-50">
            <span class="text-xl font-outfit font-bold text-primary">{{ config('app.name') }}</span>
            <button @click="mobileOpen = false" class="ms-auto p-2 text-gray-500">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <nav class="flex-1 px-3 py-6 space-y-1 overflow-y-auto">
            <x-sidebar-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard*')">
                <x-slot name="icon">D</x-slot>
                {{ __('Dashboard') }}
            </x-sidebar-link>
            <x-sidebar-link href="{{ route('admin.clients.index') }}" :active="request()->routeIs('admin.clients.*')">
                <x-slot name="icon">C</x-slot>
                {{ __('Clients') }}
            </x-sidebar-link>
        </nav>
    </div>
    <main class="flex-1 w-full mt-16 md:mt-0 overflow-y-auto h-screen bg-gray-50/50">
        <div class="max-w-7xl mx-auto p-4 md:p-8">
            @yield('content')
        </div>
    </main>
</body>
</html>