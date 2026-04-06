@extends('layouts.app')

@section('content')
<div class="pt-8 pb-12">
    <div class="w-full mx-auto">
        
        <!-- Standard Header -->
        <x-dashboard-sub-header 
            :title="__('messages.create_announcement')" 
            :subtitle="__('messages.announcements')"
            :backLink="route('client.announcements.index')"
        />


<div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
    <!-- Main Form Area (Now on the Left) -->
    <div class="lg:col-span-2 order-2 lg:order-1">
        <div class="bg-white rounded-[2.5rem] shadow-[0_25px_60px_rgba(0,0,0,0.02)] border border-gray-100 p-12 transition-all hover:shadow-[0_40px_80px_rgba(0,0,0,0.04)]">
            <form action="{{ route('client.announcements.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="space-y-12">
                    <!-- Title -->
                    <div class="group">
                        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-300 mb-4 group-focus-within:text-primary transition-colors text-start">{{ __('messages.task_title_label') }}</label>
                        <input type="text" name="title" value="{{ old('title') }}" 
                            class="w-full bg-gray-50/50 border-2 border-transparent rounded-[1.5rem] px-8 py-6 font-bold text-secondary placeholder-gray-300 focus:bg-white focus:border-primary/20 focus:ring-4 focus:ring-primary/5 transition-all outline-none text-start"
                            placeholder="{{ __('messages.announcement_title_placeholder') }}" required>
                        @error('title') <p class="mt-3 text-red-500 text-xs font-bold">{{ $message }}</p> @enderror
                    </div>

                    <!-- Body -->
                    <div class="group">
                        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-300 mb-4 group-focus-within:text-primary transition-colors text-start">{{ __('messages.task_description_label') }}</label>
                        <textarea name="body" rows="10" 
                            class="w-full bg-gray-50/50 border-2 border-transparent rounded-[2rem] px-8 py-8 font-bold text-secondary placeholder-gray-300 focus:bg-white focus:border-primary/20 focus:ring-4 focus:ring-primary/5 transition-all outline-none resize-none text-start antialiased"
                            placeholder="{{ __('messages.announcement_body_placeholder') }}" required>{{ old('body') }}</textarea>
                        @error('body') <p class="mt-3 text-red-500 text-xs font-bold">{{ $message }}</p> @enderror
                    </div>

                    <!-- Attachment -->
                    <div class="group" x-data="{ fileName: '' }">
                        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-300 mb-4 group-focus-within:text-primary transition-colors text-start">{{ __('messages.attachments') }}</label>
                        <label class="relative block bg-gray-50/50 border-2 border-dashed border-gray-100 rounded-[2rem] p-10 cursor-pointer hover:border-primary/30 hover:bg-white transition-all group/upload shadow-sm overflow-hidden">
                            <input type="file" name="attachment" class="absolute inset-0 w-full h-full opacity-0 z-10 cursor-pointer" 
                                @change="fileName = $event.target.files[0].name">
                            
                            <div class="flex flex-col items-center justify-center gap-4">
                                <div class="w-16 h-16 rounded-2xl bg-white shadow-sm flex items-center justify-center group-hover/upload:scale-110 transition-transform duration-500">
                                    <svg x-show="!fileName" class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    <svg x-show="fileName" class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04M12 21.48V22" />
                                    </svg>
                                </div>
                                <div class="text-center">
                                    <p class="text-sm font-black text-secondary uppercase tracking-widest antialiased" x-text="fileName ? fileName : '{{ __('messages.upload_file') }}'"></p>
                                    <p x-show="!fileName" class="text-[10px] font-bold text-gray-400 mt-1 uppercase tracking-[0.1em]">{{ __('messages.excel_size_hint') }}</p>
                                </div>
                            </div>
                        </label>
                        @error('attachment') <p class="mt-3 text-red-500 text-xs font-bold">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-12 flex justify-end">
                    <button type="submit" class="group flex items-center gap-4 bg-secondary px-12 py-5 rounded-2xl font-black text-white hover:bg-primary hover:text-secondary transition-all duration-500 shadow-xl shadow-secondary/10 hover:shadow-primary/20">
                        <span class="text-sm tracking-widest antialiased">{{ __('messages.save_data') }}</span>
                        <svg class="w-5 h-5 transition-transform group-hover:scale-125" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Help / Instructions Card (Now on the Right) -->
    <div class="lg:col-span-1 order-1 lg:order-2 space-y-6">
        <div class="bg-secondary rounded-[2.5rem] p-10 text-white shadow-2xl shadow-secondary/20 relative overflow-hidden group">
            <div class="relative z-10">
                <div class="flex justify-start mb-8 text-primary opacity-50 group-hover:opacity-100 transition-opacity">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="text-start">
                    <h3 class="text-2xl font-black mb-6 tracking-tight">{{ __('messages.broadcast_notice_title') }}</h3>
                    <p class="text-sm text-white/60 leading-relaxed font-bold mb-10 antialiased italic">
                        {{ __('messages.broadcast_notice_desc') }}
                    </p>
                    <ul class="space-y-6">
                        <li class="flex items-center gap-4 text-xs font-black text-primary uppercase tracking-widest transition-transform group-hover:translate-x-1 rtl:group-hover:-translate-x-1">
                            <div class="w-2 h-2 rounded-full bg-primary shadow-[0_0_10px_rgba(0,200,150,0.5)]"></div>
                            {{ __('messages.feature_visible_employees') }}
                        </li>
                        <li class="flex items-center gap-4 text-xs font-black text-primary uppercase tracking-widest transition-transform group-hover:translate-x-1 rtl:group-hover:-translate-x-1">
                            <div class="w-2 h-2 rounded-full bg-primary shadow-[0_0_10px_rgba(0,200,150,0.5)]"></div>
                            {{ __('messages.feature_multi_line') }}
                        </li>
                        <li class="flex items-center gap-4 text-xs font-black text-primary uppercase tracking-widest transition-transform group-hover:translate-x-1 rtl:group-hover:-translate-x-1">
                            <div class="w-2 h-2 rounded-full bg-primary shadow-[0_0_10px_rgba(0,200,150,0.5)]"></div>
                            {{ __('messages.feature_real_time') }}
                        </li>
                    </ul>
                </div>
            </div>
            <!-- Decorative circle -->
            <div class="absolute top-[-5rem] left-[-5rem] w-48 h-48 bg-primary/5 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000"></div>
        </div>
    </div>
</div>
</div>
@endsection
