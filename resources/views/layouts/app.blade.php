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
<body class="bg-gray-50 text-gray-900 antialiased min-h-screen flex flex-col">

    <nav class="bg-white border-b border-gray-100 py-4 px-6 mb-8 shadow-sm">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <!-- Brand -->
            <div class="flex items-center space-x-4 {{ app()->getLocale() == 'ar' ? 'space-x-reverse' : '' }}">
                <a href="/" class="text-xl font-bold text-blue-600 tracking-tight">
                    {{ config('app.name', 'HR System') }}
                </a>
            </div>

            <!-- Nav Links & Switcher -->
            <div class="flex items-center space-x-6 {{ app()->getLocale() == 'ar' ? 'space-x-reverse' : '' }}">
                
                <!-- Role-based links (simple placeholders for now) -->
                @auth
                    @if(Auth::user()->isSuperAdmin())
                        <a href="/admin/clients" class="text-sm font-medium hover:text-blue-500 transition-colors">{{ __('messages.clients') }}</a>
                    @elseif(Auth::user()->isClient())
                        <a href="/client/dashboard" class="text-sm font-medium hover:text-blue-500 transition-colors">{{ __('messages.dashboard') }}</a>
                        <a href="{{ route('client.attendance.index') }}" class="text-sm font-medium hover:text-blue-500 transition-colors">{{ __('Attendance') }}</a>
                        <a href="{{ route('client.tasks.index') }}" class="text-sm font-medium hover:text-blue-500 transition-colors">{{ __('Tasks') }}</a>
                        <a href="{{ route('client.assets.index') }}" class="text-sm font-medium hover:text-blue-500 transition-colors">{{ __('Assets') }}</a>
                        <a href="{{ route('client.leaves.index') }}" class="text-sm font-medium hover:text-blue-500 transition-colors">{{ __('Leaves') }}</a>
                    @elseif(Auth::user()->isEmployee())
                        <a href="/employee/dashboard" class="text-sm font-medium hover:text-blue-500 transition-colors">{{ __('messages.dashboard') }}</a>
                    @endif
                    
                    <form method="POST" action="/logout" class="inline">
                        @csrf
                        <button type="submit" class="text-sm font-medium text-red-500 hover:text-red-600 transition-colors">
                            {{ __('messages.logout') }}
                        </button>
                    </form>
                @endauth

                <!-- Lang Switcher -->
                <div class="flex items-center border-s border-gray-200 ps-6 {{ app()->getLocale() == 'ar' ? 'space-x-reverse' : '' }}">
                    @if(app()->getLocale() == 'ar')
                        <a href="/lang/en" class="text-xs uppercase font-bold text-gray-400 hover:text-blue-600 transition-colors">EN</a>
                    @else
                        <a href="/lang/ar" class="text-xs uppercase font-bold text-gray-400 hover:text-blue-600 transition-colors">عربي</a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow max-w-7xl mx-auto px-6 w-full">
        @yield('content')
    </main>

    <footer class="mt-12 py-8 bg-white border-t border-gray-50 text-center text-sm text-gray-400">
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('messages.all_rights_reserved') ?? '' }}</p>
    </footer>

</body>
</html>
