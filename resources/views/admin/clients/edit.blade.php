@extends('layouts.admin')

@section('content')
<div class="pt-8 pb-12">
    <div class="w-full max-w-4xl mx-auto">
        <!-- Standard Header -->
        <x-dashboard-sub-header 
            :title="__('messages.edit_client') ?? 'Edit Client Details'" 
            :subtitle="__('messages.update_client_instructions') ?? 'Modify the core settings and identification for this organization.'"
        >
            <x-slot name="actions">
                <a href="{{ route('admin.clients.show', $client->id) }}" class="group relative inline-flex items-center gap-3 px-8 py-4 bg-white text-secondary font-black rounded-2xl shadow-[0_10px_30px_rgba(0,0,0,0.02)] border border-gray-100 hover:translate-y-[-2px] transition-all duration-300">
                    <svg class="w-5 h-5 group-hover:translate-x-[-4px] transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span class="text-xs uppercase tracking-widest">{{ __('messages.cancel') }}</span>
                </a>
            </x-slot>
        </x-dashboard-sub-header>

        <div class="bg-white p-10 rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100/50">
            <form action="{{ route('admin.clients.update', $client->id) }}" method="POST" class="space-y-8">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Client Name -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-4">{{ __('messages.company_name') }}</label>
                        <input type="text" name="name" value="{{ old('name', $client->name) }}" required
                               class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-secondary font-bold focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                        @error('name') <p class="text-xs text-red-500 font-bold px-4 pt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Client Slug (Read-Only after creation) -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-4">{{ __('messages.slug') ?? 'Access Slug' }}</label>
                        <div class="w-full px-6 py-4 bg-gray-100/70 border border-gray-200/60 rounded-2xl text-secondary/70 font-bold flex items-center gap-3 cursor-not-allowed select-all">
                            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            <span>{{ $client->slug }}</span>
                        </div>
                        <p class="text-[9px] text-amber-500 font-bold px-4 pt-1 uppercase flex items-center gap-1">
                            <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                            {{ __('messages.slug_readonly_hint') ?? 'This slug is permanent and cannot be changed. Modifying it would break all login URLs and bookmarks.' }}
                        </p>
                    </div>

                    <!-- Subscription Start -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-4">{{ __('messages.subscription_start') ?? 'Subscription Start' }}</label>
                        <input type="date" name="subscription_start" value="{{ old('subscription_start', $client->subscription_start ? $client->subscription_start->format('Y-m-d') : '') }}"
                               class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-secondary font-bold focus:ring-2 focus:ring-primary focus:border-primary transition-all font-outfit">
                        @error('subscription_start') <p class="text-xs text-red-500 font-bold px-4 pt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Subscription End -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-4">{{ __('messages.subscription_end') }}</label>
                        <input type="date" name="subscription_end" value="{{ old('subscription_end', $client->subscription_end ? $client->subscription_end->format('Y-m-d') : '') }}"
                               class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-secondary font-bold focus:ring-2 focus:ring-primary focus:border-primary transition-all font-outfit">
                        @error('subscription_end') <p class="text-xs text-red-500 font-bold px-4 pt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-50 flex justify-end">
                    <button type="submit" class="group relative inline-flex items-center gap-3 px-12 py-5 bg-secondary text-white font-black rounded-2xl shadow-[0_20px_40px_rgba(20,37,51,0.2)] hover:translate-y-[-4px] transition-all duration-300">
                        <span class="text-xs uppercase tracking-widest">{{ __('messages.save_changes') }}</span>
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
