<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ App::isLocale('ar') ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('errors.403_title') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-surface text-text-main h-screen flex items-center justify-center">
    <div class="text-center px-4">
        <svg class="mx-auto h-40 w-40 text-primary mb-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <!-- Example 403 Line Art SVG (Lock/Shield) -->
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
        </svg>
        <h1 class="text-5xl font-outfit font-bold mb-4">403</h1>
        <h2 class="text-2xl font-semibold mb-2">{{ __('errors.403_title') }}</h2>
        <p class="text-gray-500 font-inter mb-8 max-w-md mx-auto">{{ __('errors.403_message') }}</p>
        <a href="/" class="inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-primary hover:bg-opacity-90 transition-colors">
            {{ __('errors.return_home') }}
        </a>
    </div>
</body>
</html>
