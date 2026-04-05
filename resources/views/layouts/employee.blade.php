<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'HR System') }} - {{ __('Employee Profile') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    @if(app()->getLocale() == 'ar')
        <link href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@100..900&display=swap" rel="stylesheet">
        <style>body { font-family: 'Noto Kufi Arabic', sans-serif; }</style>
    @else
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
        <style>body { font-family: 'Inter', sans-serif; }</style>
    @endif

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        [x-cloak] { display: none !important; }
        
        /* Logical property fallback/enhancement if Tailwind isn't enough */
        .ms-4 { margin-inline-start: 1rem; }
        .pe-4 { padding-inline-end: 1rem; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased min-h-screen flex flex-row" x-data="{ open: false }">

    <!-- Mobile Hamburger Menu -->
    <div class="md:hidden p-4 bg-white border-b border-gray-100 flex items-center justify-between w-full fixed top-0 z-20">
        <a href="{{ route('employee.dashboard') }}" class="text-xl font-bold text-blue-600 tracking-tight">
            {{ config('app.name', 'HR System') }}
        </a>
        <button @click="open = !open" class="p-2 text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>
    </div>

    <!-- Sidebar -->
    <aside :class="open ? 'translate-x-0' : (document.documentElement.dir === 'rtl' ? 'translate-x-full' : '-translate-x-full')" class="fixed md:sticky top-0 inset-y-0 z-30 w-64 bg-white border-{{ app()->getLocale() == 'ar' ? 'l' : 'r' }} border-gray-100 min-h-screen flex flex-col transition-transform duration-300 md:translate-x-0 {{ app()->getLocale() == 'ar' ? 'right-0' : 'left-0' }}">
        
        <div class="p-6 hidden md:block">
            <a href="{{ route('employee.dashboard') }}" class="text-xl font-bold text-blue-600 tracking-tight block text-center mb-0">
                {{ config('app.name', 'HR System') }}
            </a>
        </div>

        <nav class="flex-grow px-4 md:px-4 py-8 md:py-8 space-y-2 mt-[60px] md:mt-0">
            <a href="{{ route('employee.dashboard') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-colors {{ request()->routeIs('employee.dashboard*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50' }}">
                <span class="text-lg {{ app()->getLocale() == 'ar' ? 'ml-3' : 'mr-3' }}">📊</span>
                {{ __('Dashboard') ?? 'Dashboard' }} 
            </a>
            <a href="/employee/profile" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-colors {{ request()->is('employee/profile*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50' }}">
                <span class="text-lg {{ app()->getLocale() == 'ar' ? 'ml-3' : 'mr-3' }}">👤</span>
                {{ __('My Profile') }}
            </a>
            <a href="{{ route('employee.payslips.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-colors {{ request()->routeIs('employee.payslips.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50' }}">
                <span class="text-lg {{ app()->getLocale() == 'ar' ? 'ml-3' : 'mr-3' }}">💰</span>
                {{ __('Payslips') ?? 'Payslips' }}
            </a>
            <a href="/employee/leaves" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-colors {{ request()->is('employee/leaves*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50' }}">
                <span class="text-lg {{ app()->getLocale() == 'ar' ? 'ml-3' : 'mr-3' }}">🏖️</span>
                {{ __('My Leaves') }}
            </a>
            <a href="{{ route('employee.tasks.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-colors {{ request()->routeIs('employee.tasks.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50' }}">
                <span class="text-lg {{ app()->getLocale() == 'ar' ? 'ml-3' : 'mr-3' }}">✅</span>
                {{ __('Tasks') }}
            </a>
            <a href="{{ route('employee.assets.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-colors {{ request()->routeIs('employee.assets.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50' }}">
                <span class="text-lg {{ app()->getLocale() == 'ar' ? 'ml-3' : 'mr-3' }}">💻</span>
                {{ __('Assets') }}
            </a>
            <a href="/employee/announcements" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-colors {{ request()->is('employee/announcements*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50' }}">
                <span class="text-lg {{ app()->getLocale() == 'ar' ? 'ml-3' : 'mr-3' }}">📢</span>
                {{ __('Announcements') }}
            </a>
        </nav>

        <div class="p-4 border-t border-gray-100">
            <div class="flex items-center justify-center space-x-4 mb-4 {{ app()->getLocale() == 'ar' ? 'space-x-reverse' : '' }}">
                @if(app()->getLocale() == 'ar')
                    <a href="/lang/en" class="text-xs uppercase font-bold text-gray-400 hover:text-blue-600 transition-colors">EN</a>
                @else
                    <a href="/lang/ar" class="text-xs uppercase font-bold text-gray-400 hover:text-blue-600 transition-colors">عربي</a>
                @endif
            </div>

            <form method="POST" action="/logout" class="block w-full">
                @csrf
                <button type="submit" class="w-full py-2 px-4 text-sm font-medium text-red-500 hover:bg-red-50 rounded-xl transition-colors flex items-center justify-center">
                    <span class="mr-2 {{ app()->getLocale() == 'ar' ? 'ml-2 mr-0' : '' }}">🚪</span>
                    {{ __('Logout') ?? 'Logout' }}
                </button>
            </form>
        </div>
    </aside>

    <!-- Overlay for mobile when sidebar is open -->
    <div x-show="open" @click="open = false" class="fixed inset-0 bg-black bg-opacity-25 z-20 md:hidden" x-cloak></div>

    <!-- Main Content Area -->
    <main class="flex-1 w-full mt-16 md:mt-0 p-6 {{ app()->getLocale() == 'ar' ? 'mr-0' : 'ml-0' }}">
        <div class="max-w-6xl mx-auto">
            @yield('content')
        </div>
    </main>

</body>
</html>
