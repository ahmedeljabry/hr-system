@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-gray-900 overflow-hidden shadow-2xl rounded-3xl p-10 text-white mb-8 relative border border-gray-800">
            <div class="relative z-10 flex justify-between items-center">
                <div>
                    <h1 class="text-4xl font-extrabold mb-2 text-blue-400">نظام إدارة الموارد البشرية</h1>
                    <p class="text-gray-400 text-lg">لوحة تحكم مدير النظام - Super Admin Dashboard</p>
                </div>
                <div class="bg-gray-800 p-4 rounded-2xl border border-gray-700">
                    <span class="text-xs uppercase font-bold text-gray-500 tracking-widest block mb-1">دور المستخدم / Role</span>
                    <span class="text-blue-400 font-bold">مدير النظام (Super Admin)</span>
                </div>
            </div>
            <!-- Grid effect -->
            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#3b82f6 1px, transparent 1px); background-size: 20px 20px;"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <a href="/admin/clients" class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 hover:border-blue-300 hover:shadow-md transition-all group">
                <div class="flex items-center space-x-4 {{ app()->getLocale() == 'ar' ? 'space-x-reverse' : '' }} mb-4">
                    <div class="bg-blue-50 p-4 rounded-xl group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">إدارة العملاء</h3>
                <p class="text-sm text-gray-400">عرض وإدارة جميع شركات العملاء والاشتراكات</p>
                <div class="mt-6 flex items-center text-blue-600 font-bold text-sm">
                    <span>عرض التفاصيل</span>
                    <svg class="w-4 h-4 ms-2 transform rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </div>
            </a>
            
            <!-- Other card placeholders -->
            <div class="bg-white p-8 rounded-2xl border border-dashed border-gray-200 flex flex-col items-center justify-center text-gray-300 opacity-60">
                <p class="font-medium text-sm">إعدادات النظام</p>
            </div>
            <div class="bg-white p-8 rounded-2xl border border-dashed border-gray-200 flex flex-col items-center justify-center text-gray-300 opacity-60">
                <p class="font-medium text-sm">تقارير النظام</p>
            </div>
            <div class="bg-white p-8 rounded-2xl border border-dashed border-gray-200 flex flex-col items-center justify-center text-gray-300 opacity-60">
                <p class="font-medium text-sm">سجلات الأمان</p>
            </div>
        </div>
    </div>
</div>
@endsection
