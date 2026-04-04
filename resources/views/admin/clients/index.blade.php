@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-10">
            <h1 class="text-3xl font-extrabold text-blue-600">إدارة العملاء والشركات</h1>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-100 text-green-600 px-6 py-4 rounded-2xl shadow-sm flex items-center">
                <svg class="w-6 h-6 me-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-start">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-5 text-start text-xs font-extrabold text-gray-500 uppercase tracking-widest">اسم الشركة</th>
                            <th class="px-6 py-5 text-start text-xs font-extrabold text-gray-500 uppercase tracking-widest">الحالة</th>
                            <th class="px-6 py-5 text-start text-xs font-extrabold text-gray-500 uppercase tracking-widest">بداية الاشتراك</th>
                            <th class="px-6 py-5 text-start text-xs font-extrabold text-gray-500 uppercase tracking-widest">نهاية الاشتراك</th>
                            <th class="px-6 py-5 text-end text-xs font-extrabold text-gray-500 uppercase tracking-widest">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($clients as $client)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-6 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="bg-blue-50 text-blue-600 w-10 h-10 rounded-xl flex items-center justify-center font-bold me-3">
                                        {{ substr($client->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-900">{{ $client->name }}</div>
                                        <div class="text-xs text-gray-400">ID: #{{ $client->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap">
                                @if($client->isActive())
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-50 text-green-600 border border-green-100">نشط / Active</span>
                                @elseif($client->isSuspended())
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-600 border border-amber-100">معلق / Suspended</span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-50 text-red-600 border border-red-100">منتهي / Expired</span>
                                @endif
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap text-sm text-gray-500">
                                {{ $client->subscription_start ? $client->subscription_start->format('Y-m-d') : '---' }}
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap text-sm text-gray-900 font-bold">
                                {{ $client->subscription_end ? $client->subscription_end->format('Y-m-d') : 'اشتراك مفتوح' }}
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap text-end text-sm font-medium">
                                <div class="flex justify-end items-center gap-2">
                                    <form action="{{ route('admin.clients.status', $client->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        @if($client->isSuspended())
                                            <input type="hidden" name="status" value="active">
                                            <button type="submit" class="text-green-600 hover:bg-green-50 px-3 py-2 rounded-lg font-bold transition-all border border-green-100">تفعيل</button>
                                        @else
                                            <input type="hidden" name="status" value="suspended">
                                            <button type="submit" class="text-amber-600 hover:bg-amber-50 px-3 py-2 rounded-lg font-bold transition-all border border-amber-100">تعليق</button>
                                        @endif
                                    </form>
                                    
                                    <div x-data="{ open: false }">
                                        <button @click="open = true" class="text-blue-600 hover:bg-blue-50 px-3 py-2 rounded-lg font-bold transition-all border border-blue-100">تحديث التاريخ</button>
                                        
                                        <!-- Minimal popup for date update -->
                                        <div x-show="open" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" x-cloak>
                                            <div @click.away="open = false" class="bg-white rounded-3xl p-8 max-w-sm w-full shadow-2xl overflow-hidden relative">
                                                <h4 class="text-xl font-bold mb-6">تحديث تاريخ الانتهاء</h4>
                                                <form action="{{ route('admin.clients.subscription', $client->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="date" name="subscription_end" value="{{ $client->subscription_end ? $client->subscription_end->format('Y-m-d') : '' }}" required
                                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 mb-6 outline-none">
                                                    <div class="flex gap-4">
                                                        <button type="submit" class="flex-1 bg-blue-600 text-white font-bold py-3 rounded-xl hover:bg-blue-700 transition">تأكيد</button>
                                                        <button type="button" @click="open = false" class="flex-1 bg-gray-100 text-gray-500 font-bold py-3 rounded-xl hover:bg-gray-200 transition">إلغاء</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
