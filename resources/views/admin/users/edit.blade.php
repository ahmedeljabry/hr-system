@extends('layouts.admin')

@section('content')
<div class="pt-8 pb-12">
    <div class="max-w-4xl mx-auto">
        <!-- Standard Header -->
        <x-dashboard-sub-header 
            :title="__('messages.edit_user') ?? 'Edit User Account'" 
            :subtitle="$user->name . ' (' . $user->email . ')'"
        >
            <x-slot name="actions">
                <a href="{{ route('admin.clients.index') }}" class="group relative inline-flex items-center gap-3 px-8 py-4 bg-white text-secondary font-black rounded-2xl shadow-[0_10px_30px_rgba(0,0,0,0.02)] border border-gray-100 hover:translate-y-[-2px] transition-all duration-300">
                    <svg class="w-5 h-5 group-hover:translate-x-[-4px] transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span class="text-xs uppercase tracking-widest">{{ __('messages.back') ?? 'Back' }}</span>
                </a>
            </x-slot>
        </x-dashboard-sub-header>

        @if(session('success'))
            <div class="mb-8 bg-green-50 border border-green-100 text-green-600 px-10 py-6 rounded-3xl shadow-sm flex items-center gap-4">
                <div class="bg-green-100 p-2 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100/50 p-12">
            <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="space-y-12">
                @csrf
                @method('PATCH')

                <!-- Primary Information Section -->
                <div>
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-1.5 h-8 bg-primary rounded-full"></div>
                        <h3 class="text-xl font-black text-secondary uppercase tracking-wider">{{ __('messages.personal_account_information') ?? 'Account Information' }}</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <!-- Name Input -->
                        <div class="space-y-4">
                            <label for="name" class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">{{ __('messages.full_name') ?? 'Full Name' }}</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                                class="w-full px-8 py-5 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none font-bold text-secondary text-lg @error('name') border-red-300 @enderror">
                            @error('name')
                                <p class="text-xs font-bold text-red-500 ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email Input -->
                        <div class="space-y-4">
                            <label for="email" class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">{{ __('messages.email_address') ?? 'Email Address' }}</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                                class="w-full px-8 py-5 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none font-bold text-secondary text-lg @error('email') border-red-300 @enderror">
                            @error('email')
                                <p class="text-xs font-bold text-red-500 ml-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="pt-10 border-t border-gray-100 flex justify-end items-center gap-8">
                    <a href="{{ route('admin.clients.index') }}" class="text-xs font-black text-gray-400 uppercase tracking-widest hover:text-secondary transition-colors">{{ __('messages.cancel') ?? 'Cancel' }}</a>
                    <button type="submit" class="group relative inline-flex items-center gap-4 px-12 py-5 bg-secondary text-white font-black rounded-2xl shadow-[0_15px_40px_rgba(20,37,51,0.2)] hover:translate-y-[-2px] transition-all duration-300">
                        <span class="text-xs uppercase tracking-widest">{{ __('messages.update_user') ?? 'Update Account' }}</span>
                        <svg class="w-6 h-6 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7-7 7"></path></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection