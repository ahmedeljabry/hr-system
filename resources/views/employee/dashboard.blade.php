@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-gradient-to-br from-emerald-600 to-teal-700 overflow-hidden shadow-2xl rounded-3xl p-10 text-white mb-8 relative">
            <div class="relative z-10 flex justify-between items-center">
                <div>
                  <h1 class="text-4xl font-extrabold mb-2">{{ __('messages.dashboard') }}</h1>
                  <p class="text-emerald-100 text-lg opacity-90">مرحبًا بك، {{ Auth::user()->name }}</p>
                </div>
                <div class="bg-white/20 backdrop-blur-md p-4 rounded-2xl border border-white/20">
                    <span class="text-xs uppercase font-bold text-white/50 tracking-widest block mb-1">الشركة / Company</span>
                    <span class="text-white font-bold">{{ Auth::user()->client?->name ?? '---' }}</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 opacity-60">
            <div class="bg-white p-8 rounded-2xl border border-dashed border-gray-200 flex flex-col items-center justify-center text-gray-400">
                <p class="font-medium text-lg mb-2">الحضور والغياب</p>
                <p class="text-sm">هذه الميزة قيد التطوير للمرحلة القادمة</p>
            </div>
            <div class="bg-white p-8 rounded-2xl border border-dashed border-gray-200 flex flex-col items-center justify-center text-gray-400">
                <p class="font-medium text-lg mb-2">كشف الراتب</p>
                <p class="text-sm">هذه الميزة قيد التطوير للمرحلة القادمة</p>
            </div>
        </div>
    </div>
</div>
@endsection
