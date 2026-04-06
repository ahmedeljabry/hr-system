@extends('layouts.app')

@section('content')
<div class="mb-10 bg-secondary rounded-[2rem] p-10 text-white relative overflow-hidden group shadow-2xl shadow-secondary/20">
    <div class="relative z-10 flex items-center justify-between">
        <div>
            <a href="{{ route('client.announcements.index') }}" class="inline-flex items-center gap-4 px-10 py-5 bg-white/10 hover:bg-white/20 text-white rounded-[2rem] font-black transition-all border border-white/10 backdrop-blur-md shadow-xl group/back">
                <svg class="w-7 h-7 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="text-2xl leading-none">{{ __('messages.back') }}</span>
            </a>
        </div>
        <div class="flex items-center gap-4 text-start">
            <div class="text-start">
                <h1 class="text-4xl font-black tracking-tight text-white mb-1">{{ __('Edit Announcement') }}</h1>
                <p class="text-primary font-bold text-xs opacity-80 uppercase tracking-widest">{{ __('messages.announcements') }}</p>
            </div>
            <div class="w-2 h-12 bg-primary rounded-full shadow-[0_0_15px_rgba(0,200,150,0.4)]"></div>
        </div>
    </div>
    <!-- Decorative background element -->
    <div class="absolute bottom-[-2rem] right-[-2rem] w-48 h-48 bg-primary/10 rounded-full blur-3xl transition-transform duration-1000 group-hover:scale-125"></div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
    <!-- Main Form Area (Left) -->
    <div class="lg:col-span-2 order-2 lg:order-1">
        <div class="bg-white rounded-[2.5rem] shadow-[0_25px_60px_rgba(0,0,0,0.02)] border border-gray-100 p-12 transition-all hover:shadow-[0_40px_80px_rgba(0,0,0,0.04)]">
            <form action="{{ route('client.announcements.update', $announcement) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="space-y-12">
                    <!-- Title -->
                    <div class="group">
                        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-300 mb-4 group-focus-within:text-primary transition-colors text-start">{{ __('messages.task_title_label') }}</label>
                        <input type="text" name="title" value="{{ old('title', $announcement->title) }}" 
                            class="w-full bg-gray-50/50 border-2 border-transparent rounded-[1.5rem] px-8 py-6 font-bold text-secondary placeholder-gray-300 focus:bg-white focus:border-primary/20 focus:ring-4 focus:ring-primary/5 transition-all outline-none text-start"
                            placeholder="{{ __('messages.announcement_title_placeholder') }}" required>
                        @error('title') <p class="mt-3 text-red-500 text-xs font-bold">{{ $message }}</p> @enderror
                    </div>

                    <!-- Body -->
                    <div class="group">
                        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-300 mb-4 group-focus-within:text-primary transition-colors text-start">{{ __('messages.task_description_label') }}</label>
                        <textarea name="body" rows="10" 
                            class="w-full bg-gray-50/50 border-2 border-transparent rounded-[2rem] px-8 py-8 font-bold text-secondary placeholder-gray-300 focus:bg-white focus:border-primary/20 focus:ring-4 focus:ring-primary/5 transition-all outline-none resize-none text-start antialiased"
                            placeholder="{{ __('messages.announcement_body_placeholder') }}" required>{{ old('body', $announcement->body) }}</textarea>
                        @error('body') <p class="mt-3 text-red-500 text-xs font-bold">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-12 flex justify-end">
                    <button type="submit" class="group flex items-center gap-4 bg-secondary px-12 py-5 rounded-2xl font-black text-white hover:bg-primary hover:text-secondary transition-all duration-500 shadow-xl shadow-secondary/10 hover:shadow-primary/20">
                        <span class="text-sm tracking-widest antialiased">{{ __('messages.update') }}</span>
                        <svg class="w-5 h-5 transition-transform group-hover:scale-125" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Help / Instructions Card (Right) -->
    <div class="lg:col-span-1 order-1 lg:order-2 space-y-6">
        <div class="bg-secondary rounded-[2.5rem] p-10 text-white shadow-2xl shadow-secondary/20 relative overflow-hidden group">
            <div class="relative z-10">
                <div class="flex justify-start mb-8 text-primary opacity-50 group-hover:opacity-100 transition-opacity">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </div>
                <div class="text-start">
                    <h3 class="text-2xl font-black mb-6 tracking-tight">{{ __('Update Announcement') }}</h3>
                    <p class="text-sm text-white/60 leading-relaxed font-bold mb-10 antialiased italic">
                        {{ __('Updates made to this announcement will be reflected immediately on all employee dashboards.') }}
                    </p>
                    <ul class="space-y-6">
                        <li class="flex items-center gap-4 text-xs font-black text-primary uppercase tracking-widest transition-transform group-hover:translate-x-1 rtl:group-hover:-translate-x-1">
                            <div class="w-2 h-2 rounded-full bg-primary shadow-[0_0_10px_rgba(0,200,150,0.5)]"></div>
                            {{ __('Instant sync to dashboards') }}
                        </li>
                    </ul>
                </div>
            </div>
            <!-- Decorative circle -->
            <div class="absolute top-[-5rem] left-[-5rem] w-48 h-48 bg-primary/5 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000"></div>
        </div>
    </div>
</div>
@endsection
