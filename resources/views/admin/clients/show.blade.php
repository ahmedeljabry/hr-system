@extends('layouts.admin')

@section('content')
<div class="pt-8 pb-12">
    <div class="w-full">
        <!-- Standard Header -->
        <x-dashboard-sub-header 
            :title="$client->name" 
            :subtitle="__('messages.client_details_management') ?? 'Client Details & System Overview'"
        >
            <x-slot name="actions">
                <a href="{{ route('admin.clients.index') }}" class="group relative inline-flex items-center gap-3 px-8 py-4 bg-white text-secondary font-black rounded-2xl shadow-[0_10px_30px_rgba(0,0,0,0.02)] border border-gray-100 hover:translate-y-[-2px] transition-all duration-300">
                    <svg class="w-5 h-5 group-hover:translate-x-[-4px] transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span class="text-xs uppercase tracking-widest">{{ __('messages.back_to_clients') ?? 'Back' }}</span>
                </a>
                
                <form action="{{ route('admin.clients.destroy', $client->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" 
                            onclick="const form = this.closest('form'); Swal.fire({
                                title: '{{ __('messages.are_you_sure') }}',
                                text: '{{ __('messages.confirm_delete_client') }}',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#EF4444',
                                cancelButtonColor: '#6B7280',
                                confirmButtonText: '{{ __('messages.yes_delete') }}',
                                cancelButtonText: '{{ __('messages.cancel') }}',
                                reverseButtons: true,
                                customClass: {
                                    popup: 'rounded-[2rem]',
                                    confirmButton: 'rounded-xl font-bold px-6 py-3',
                                    cancelButton: 'rounded-xl font-bold px-6 py-3'
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    form.submit();
                                }
                            })"
                            class="group relative inline-flex items-center gap-3 px-8 py-4 bg-red-500 text-white font-black rounded-2xl shadow-[0_10px_30px_rgba(239,68,68,0.2)] border border-red-600 hover:translate-y-[-2px] transition-all duration-300">
                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        <span class="text-xs uppercase tracking-widest">{{ __('messages.delete_client') ?? 'Delete Client' }}</span>
                    </button>
                </form>
            </x-slot>
        </x-dashboard-sub-header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 mb-12">
            <!-- Client Info Card -->
            <div class="lg:col-span-2 group relative bg-white p-10 rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100/50 hover:shadow-[0_40px_80px_rgba(0,0,0,0.06)] hover:translate-y-[-8px] transition-all duration-500 overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-bl-[5rem] -mr-10 -mt-10 transition-transform duration-700 group-hover:scale-125"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center text-secondary font-black text-xl">
                            {{ substr($client->name, 0, 1) }}
                        </div>
                        <h3 class="text-xl font-black text-secondary tracking-tight">{{ __('messages.client_information') ?? 'Client Information' }}</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="p-6 bg-gray-50/50 rounded-3xl border border-gray-100">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('messages.company_name') ?? 'Company Name' }}</p>
                            <p class="text-lg font-black text-secondary">{{ $client->name }}</p>
                        </div>
                        <div class="p-6 bg-gray-50/50 rounded-3xl border border-gray-100">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('messages.status') ?? 'Status' }}</p>
                            @if($client->isActive())
                                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-black bg-green-50 text-green-600 border border-green-100 uppercase tracking-widest">{{ __('messages.active') ?? 'Active' }}</span>
                            @elseif($client->isSuspended())
                                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-black bg-amber-50 text-amber-600 border border-amber-100 uppercase tracking-widest">{{ __('messages.suspended') ?? 'Suspended' }}</span>
                            @else
                                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-black bg-red-50 text-red-600 border border-red-100 uppercase tracking-widest">{{ __('messages.expired') ?? 'Expired' }}</span>
                            @endif
                        </div>
                        <div class="p-6 bg-gray-50/50 rounded-3xl border border-gray-100">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('messages.subscription_end') ?? 'Subscription End' }}</p>
                            <p class="text-lg font-black text-secondary">{{ $client->subscription_end ? $client->subscription_end->translatedFormat('Y-m-d') : '—' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Mini Card -->
            <div class="group relative bg-secondary p-10 rounded-[2.5rem] shadow-[0_20px_50px_rgba(20,37,51,0.1)] border border-white/5 hover:translate-y-[-8px] transition-all duration-500 overflow-hidden flex flex-col justify-center">
                <div class="absolute top-0 right-0 w-32 h-32 bg-primary/10 rounded-bl-[5rem] -mr-10 -mt-10 blur-2xl"></div>
                <div class="relative z-10 text-center">
                    <p class="text-6xl font-black text-primary mb-2">{{ $employees->count() }}</p>
                    <p class="text-xs font-black text-white/40 uppercase tracking-[0.3em]">{{ __('messages.total_employees') ?? 'Total Employees' }}</p>
                </div>
            </div>
        </div>

        <!-- Employees Table -->
        <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100/50 overflow-hidden">
            <div class="px-10 py-8 border-b border-gray-50 bg-gray-50/30 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-1.5 h-6 bg-primary rounded-full"></div>
                    <h3 class="text-xl font-black text-secondary uppercase tracking-wider">{{ __('messages.employees') ?? 'Employees' }}</h3>
                    <span class="px-2.5 py-1 bg-primary/10 text-primary text-[10px] font-black rounded-lg border border-primary/10">
                        {{ $employees->count() }}
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-start border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100">
                            <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-start">{{ __('messages.name') ?? 'Name' }}</th>
                            <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-start">{{ __('messages.position') ?? 'Position' }}</th>
                            <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-start">{{ __('messages.hire_date') ?? 'Hire Date' }}</th>
                            <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-start">{{ __('messages.status') ?? 'Status' }}</th>
                            <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-end">{{ __('messages.actions') ?? 'Actions' }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($employees as $employee)
                        <tr class="hover:bg-gray-50/50 transition-colors group/row">
                            <td class="px-10 py-8">
                                <div class="text-sm font-black text-secondary group-hover/row:text-primary transition-colors">{{ $employee->name }}</div>
                            </td>
                            <td class="px-10 py-8 text-sm text-gray-500 font-bold">
                                {{ $employee->position }}
                            </td>
                            <td class="px-10 py-8 text-sm text-gray-400 font-black font-outfit uppercase tracking-tighter">
                                {{ $employee->hire_date->translatedFormat('Y-m-d') }}
                            </td>
                            <td class="px-10 py-8 text-start">
                                @if($employee->user_id)
                                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[9px] font-black bg-emerald-50 text-emerald-600 border border-emerald-100 uppercase tracking-widest leading-none">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 me-2 animate-pulse"></span>
                                        {{ __('messages.has_login') ?? 'Has Login' }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[9px] font-black bg-gray-50 text-gray-400 border border-gray-200 uppercase tracking-widest leading-none">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-300 me-2"></span>
                                        {{ __('messages.no_login') ?? 'No Login' }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-10 py-8 text-end">
                                @if($employee->user)
                                    <a href="{{ route('admin.users.edit', $employee->user->id) }}" class="group/btn inline-flex items-center gap-3 px-6 py-2.5 bg-secondary text-white font-black rounded-xl shadow-[0_4px_15px_rgba(20,37,51,0.1)] hover:translate-y-[-2px] transition-all duration-300">
                                        <span class="text-[10px] uppercase tracking-widest">{{ __('messages.edit_user') ?? 'Edit User' }}</span>
                                        <svg class="w-3.5 h-3.5 group-hover/btn:translate-x-1 rtl:group-hover/btn:translate-x-[-4px] transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-10 py-24 text-center">
                                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-200">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                </div>
                                <h3 class="text-xl font-black text-secondary tracking-tight mb-2">{{ __('messages.no_employees_yet') ?? 'No employees registered' }}</h3>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection