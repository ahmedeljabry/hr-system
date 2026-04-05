<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ App::isLocale('ar') ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('errors.500_title') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-surface text-text-main h-screen flex items-center justify-center">
    <div class="text-center px-4">
        <svg class="mx-auto h-40 w-40 text-primary mb-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <!-- Example 500 Line Art SVG -->
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
        </svg>
        <h1 class="text-5xl font-outfit font-bold mb-4">500</h1>
        <h2 class="text-2xl font-semibold mb-2">{{ __('errors.500_title') }}</h2>
        <p class="text-gray-500 font-inter mb-8 max-w-md mx-auto">{{ __('errors.500_message') }}</p>
        <a href="/" class="inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-primary hover:bg-opacity-90 transition-colors">
            {{ __('errors.return_home') }}
        </a>
    </div>
</body>
</html>
