@extends('layouts.admin')

@section('content')
<div class="pt-8 pb-12">
    <div class="w-full">
        <!-- Header -->
        <x-dashboard-sub-header 
            :title="__('messages.all_employees_global') ?? 'Global Employees Database'" 
            :subtitle="__('messages.manage_all_employees_desc') ?? 'View and search through all employees across all clients in the system.'"
        >
            <x-slot name="actions">
                <form action="{{ route('admin.employees.index') }}" method="GET" class="relative group">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400 group-focus-within:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="w-full sm:w-80 bg-white border border-gray-100 text-secondary text-sm rounded-2xl focus:ring-4 focus:ring-primary/10 focus:border-primary block ps-11 p-4 shadow-[0_10px_30px_rgba(0,0,0,0.02)] transition-all duration-300 font-bold" 
                           placeholder="{{ __('messages.search_employees') ?? 'Search employees...' }}">
                </form>
            </x-slot>
        </x-dashboard-sub-header>

        <!-- Employees Table -->
        <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100/50 overflow-hidden relative">
            
            @if($employees->isEmpty())
                <div class="flex flex-col items-center justify-center py-24 text-center px-4">
                    <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-black text-secondary tracking-tight mb-2">{{ __('messages.no_records_found') }}</h3>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-start border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50 border-b border-gray-100">
                                <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-start">{{ __('messages.name') ?? 'Name' }}</th>
                                <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-start">{{ __('messages.position') ?? 'Position' }}</th>
                                <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-start">{{ __('messages.client') ?? 'Client' }}</th>
                                <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-start">{{ __('messages.hire_date') ?? 'Hire Date' }}</th>
                                <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-end">{{ __('messages.actions') ?? 'Actions' }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($employees as $employee)
                            <tr class="hover:bg-gray-50/50 transition-colors group/row">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary font-black text-sm">
                                            {{ mb_substr($employee->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-black text-secondary group-hover/row:text-primary transition-colors">{{ $employee->name }}</div>
                                            <div class="text-[10px] text-gray-400 uppercase tracking-widest mt-1">{{ $employee->national_id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-sm text-gray-500 font-bold">
                                    {{ $employee->position }}
                                </td>
                                <td class="px-8 py-6">
                                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[10px] font-black bg-blue-50 text-blue-600 border border-blue-100 uppercase tracking-widest">
                                        {{ $employee->client->name }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-sm text-gray-400 font-black uppercase tracking-tighter">
                                    {{ $employee->hire_date ? $employee->hire_date->translatedFormat('Y-m-d') : '—' }}
                                </td>
                                <td class="px-8 py-6 text-end">
                                    <div class="flex items-center justify-end gap-3">
                                        @if($employee->user_id)
                                        <form action="{{ route('admin.impersonate.employee', $employee->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="p-2 text-gray-400 hover:text-primary hover:bg-primary/10 rounded-xl transition-all" title="{{ __('messages.login_as_employee') ?? 'Login as Employee' }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                            </button>
                                        </form>
                                        @endif
                                        <a href="{{ route('admin.employees.edit', $employee->id) }}" class="p-2 text-gray-400 hover:text-blue-500 hover:bg-blue-50 rounded-xl transition-all" title="Edit Employee Profile">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        <a href="{{ route('admin.clients.show', $employee->client_id) }}" class="p-2 text-gray-400 hover:text-primary hover:bg-primary/10 rounded-xl transition-all" title="View Client">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($employees->hasPages())
                <div class="px-8 py-6 border-t border-gray-50 bg-gray-50/30">
                    {{ $employees->links() }}
                </div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
