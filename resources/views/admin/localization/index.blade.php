@extends('layouts.admin')

@section('content')
<div class="pt-8 pb-12">
    <div class="w-full">
        <!-- Standard Header -->
        <x-dashboard-sub-header 
            :title="__('Localization Decisions') ?? 'Localization Decisions'" 
            :subtitle="__('Manage localization decisions and Saudi workforce requirements.') ?? 'Manage localization decisions and Saudi workforce requirements.'"
        />

        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-100 text-green-600 px-6 py-4 rounded-2xl shadow-sm flex items-center">
                <svg class="w-6 h-6 me-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
        @endif

        <x-data-table 
            endpoint="{{ route('admin.localization.index') }}" 
        >
            <x-slot name="actions">
                <a href="{{ route('admin.localization.create') }}" class="bg-secondary text-primary font-black px-6 py-3 rounded-xl hover:shadow-lg transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                    <span>{{ __('Add Decision') }}</span>
                </a>
            </x-slot>

            <x-slot name="head">
                <th class="px-6 py-5 text-start text-xs font-extrabold text-gray-500 uppercase tracking-widest">{{ __('Saudi Percentage') }}</th>
                <th class="px-6 py-5 text-start text-xs font-extrabold text-gray-500 uppercase tracking-widest">{{ __('Included Jobs') }}</th>
                <th class="px-6 py-5 text-start text-xs font-extrabold text-gray-500 uppercase tracking-widest">{{ __('Created At') }}</th>
                <th class="px-6 py-5 text-end text-xs font-extrabold text-gray-500 uppercase tracking-widest">{{ __('Actions') }}</th>
            </x-slot>
            <x-slot name="body">
                <td class="px-6 py-6 whitespace-nowrap">
                    <div class="text-sm font-bold text-gray-900" x-text="item.saudi_percentage + '%'"></div>
                </td>
                <td class="px-6 py-6 whitespace-nowrap">
                    <div class="text-sm text-gray-600" x-text="item.jobs_count + ' {{ __('Jobs') }}'"></div>
                </td>
                <td class="px-6 py-6 whitespace-nowrap">
                    <div class="text-sm text-gray-500" x-text="new Date(item.created_at).toLocaleDateString()"></div>
                </td>
                <td class="px-6 py-6 whitespace-nowrap text-end text-sm font-medium">
                    <div class="flex justify-end gap-3">
                        <a :href="`/admin/localization/${item.id}/edit`" class="p-2 bg-secondary/10 text-secondary rounded-lg hover:bg-secondary/20 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </a>
                        <form :action="`/admin/localization/${item.id}`" method="POST" onsubmit="return confirm('{{ __('Are you sure?') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </x-slot>
        </x-data-table>
    </div>
</div>
@endsection
