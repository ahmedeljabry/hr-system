@extends('layouts.admin')

@section('content')
<div class="pt-8 pb-12">
    <div class="w-full">
        <!-- Standard Header -->
        <x-dashboard-sub-header 
            :title="__('messages.clients_management') ?? 'Clients Management'" 
            :subtitle="__('messages.manage_client_subscriptions_description') ?? 'Manage client subscriptions and view organization details.'"
        />


    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-100 text-green-600 px-6 py-4 rounded-2xl shadow-sm flex items-center">
            <svg class="w-6 h-6 me-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-100 text-red-600 px-6 py-4 rounded-2xl shadow-sm flex items-center">
            <svg class="w-6 h-6 me-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <span class="font-bold">{{ session('error') }}</span>
        </div>
    @endif

    <x-data-table 
        endpoint="{{ route('admin.clients.index') }}" 
        delete-endpoint="{{ route('admin.clients.bulk-destroy') }}"
    >
        <x-slot name="head">
            <th class="px-6 py-5 text-start text-xs font-extrabold text-gray-500 uppercase tracking-widest">{{ __('messages.company_name') ?? 'Company Name' }}</th>
            <th class="px-6 py-5 text-start text-xs font-extrabold text-gray-500 uppercase tracking-widest">{{ __('messages.status') ?? 'Status' }}</th>
            <th class="px-6 py-5 text-start text-xs font-extrabold text-gray-500 uppercase tracking-widest">{{ __('messages.employees') ?? 'Employees' }}</th>
            <th class="px-6 py-5 text-end text-xs font-extrabold text-gray-500 uppercase tracking-widest">{{ __('messages.actions') ?? 'Actions' }}</th>
        </x-slot>
        <x-slot name="body">
            <td class="px-6 py-6 whitespace-nowrap">
                <div class="flex items-center">
                    <div class="bg-primary/20 text-secondary w-10 h-10 rounded-xl flex items-center justify-center font-bold me-3 text-lg" :text-content="item.name.substring(0, 1)"></div>
                    <div>
                        <div class="text-sm font-bold text-gray-900" x-text="item.name"></div>
                        <div class="text-xs text-gray-400">ID: #<span x-text="item.id"></span></div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-6 whitespace-nowrap">
                <!-- Fallback since standard table approach required select forms here -->
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider"
                      :class="{
                          'bg-green-100 text-green-700': item.status === 'active',
                          'bg-amber-100 text-amber-700': item.status === 'suspended',
                          'bg-red-100 text-red-700': item.status === 'expired'
                      }" x-text="item.status === 'active' ? '{{ __('messages.active') }}' : (item.status === 'suspended' ? '{{ __('messages.suspended') }}' : '{{ __('messages.expired') }}')"></span>
            </td>
            <td class="px-6 py-6 whitespace-nowrap text-sm text-gray-900 font-bold" x-text="item.employees_count"></td>
            <td class="px-6 py-6 whitespace-nowrap text-end text-sm font-medium">
                <div class="flex items-center justify-end gap-3">
                    <form :action="`/admin/impersonate/client/${item.id}`" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="p-2 text-gray-400 hover:text-secondary hover:bg-gray-100 rounded-lg transition-all" :title="'{{ __('messages.login_as_client') }}'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                        </button>
                    </form>
                    <a :href="`/admin/clients/${item.id}/edit`" class="p-2 text-gray-400 hover:text-primary hover:bg-primary/10 rounded-lg transition-all" :title="'{{ __('messages.edit_client') }}'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </a>
                    <a :href="`/admin/clients/${item.id}`" class="p-2 text-gray-400 hover:text-secondary hover:bg-gray-100 rounded-lg transition-all" :title="'{{ __('messages.view_details') }}'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </a>
                </div>
            </td>
        </x-slot>
    </x-data-table>
</div>
@endsection