@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-[60vh]">
    <div class="text-center">
        <h1 class="text-9xl font-bold text-blue-600 opacity-20">403</h1>
        <p class="text-2xl font-bold text-gray-800 mt-4">{{ __('messages.unauthorized') }}</p>
        <p class="text-gray-500 mt-2">عذرًا، ليس لديك الصلاحية للوصول إلى هذه الصفحة.</p>
        <div class="mt-8">
            <a href="/" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                العودة للرئيسية / Home
            </a>
        </div>
    </div>
</div>
@endsection
