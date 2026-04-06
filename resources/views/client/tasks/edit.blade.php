@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-full mx-auto">

            <!-- Premium Hero Section -->
            <div
                class="bg-secondary overflow-hidden shadow-2xl rounded-3xl p-10 text-white mb-10 relative group border border-primary/20">
                <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                    <div>
                        <h1 class="text-4xl font-extrabold mb-2 tracking-tight text-primary">
                            {{ __('messages.edit_task') }}</h1>
                        <p class="text-gray-300 text-lg opacity-90">{{ __('messages.update_task_details') }}</p>
                    </div>

                    <div class="flex items-center gap-4">
                        <a href="{{ route('client.tasks.index') }}"
                            class="inline-flex items-center px-6 py-3 bg-white/10 hover:bg-white/20 border border-white/20 text-white text-sm font-bold rounded-2xl transition-all duration-300 backdrop-blur-md group/back">
                            <svg class="w-5 h-5 me-2 group-hover/back:-translate-x-1 transition-transform rtl:group-hover/back:translate-x-1"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            {{ __('messages.back') }}
                        </a>
                    </div>
                </div>

                <!-- Animated decorative overlays -->
                <div
                    class="absolute top-[-2rem] right-[-2rem] w-48 h-48 bg-primary opacity-5 rounded-full transition-transform duration-700 group-hover:scale-110">
                </div>
                <div
                    class="absolute bottom-[-1rem] left-[10%] w-24 h-24 bg-primary opacity-5 rounded-full transition-transform duration-500 group-hover:-translate-y-4">
                </div>
            </div>

            @if ($errors->any())
                <div class="mb-8 animate-in fade-in slide-in-from-top-4 duration-500">
                    <div class="bg-red-50 border border-red-100 p-5 rounded-[1.5rem] flex items-center gap-4 shadow-sm">
                        <div class="w-10 h-10 rounded-full bg-red-500/10 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                        </div>
                        <ul class="text-red-800 text-sm font-bold tracking-tight list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Main Form Card -->
            <div
                class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100/50 overflow-hidden transition-all duration-500">
                <form method="POST" action="{{ route('client.tasks.update', $task) }}" enctype="multipart/form-data" class="p-12 space-y-16">
                    @csrf
                    @method('PUT')

                    <!-- Section: Task Details -->
                    <div class="space-y-10">
                        <div class="flex items-center gap-4 pb-4 border-b border-gray-50">
                            <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                    </path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-black text-secondary tracking-tight">
                                {{ __('messages.task_information') }}</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                            <!-- Task Title -->
                            <div class="md:col-span-2 space-y-3">
                                <label for="title"
                                    class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.task_title_label') }}
                                    <span class="text-primary">*</span></label>
                                <div class="relative group">
                                    <div
                                        class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-3' : 'left-3' }} flex items-center pointer-events-none transition-all duration-300">
                                        <div
                                            class="w-8 h-8 rounded-xl bg-primary/10 group-focus-within:bg-primary/20 flex items-center justify-center transition-all duration-300 group-focus-within:scale-110">
                                            <svg class="w-4 h-4 text-primary/60 group-focus-within:text-primary transition-colors duration-300"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                                </path>
                                            </svg>
                                        </div>
                                    </div>
                                    <input type="text" name="title" id="title"
                                        value="{{ old('title', $task->title) }}" required
                                        class="block w-full bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl py-4 {{ app()->getLocale() == 'ar' ? 'pr-14 pl-6' : 'pl-14 pr-6' }} text-secondary font-bold transition-all duration-300 outline-none"
                                        placeholder="{{ __('messages.task_title_placeholder') ?? __('What needs to be done?') }}">
                                </div>
                            </div>

                            <!-- Assignee -->
                            <div class="space-y-3">
                                <label for="employee_id"
                                    class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.assigned_to') }}</label>
                                <div class="relative group">
                                    <div
                                        class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-3' : 'left-3' }} flex items-center pointer-events-none transition-all duration-300">
                                        <div
                                            class="w-8 h-8 rounded-xl bg-primary/10 group-focus-within:bg-primary/20 flex items-center justify-center transition-all duration-300 group-focus-within:scale-110">
                                            <svg class="w-4 h-4 text-primary/60 group-focus-within:text-primary transition-colors duration-300"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                </path>
                                            </svg>
                                        </div>
                                    </div>
                                    <select name="employee_id" id="employee_id"
                                        class="block w-full bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl py-4 {{ app()->getLocale() == 'ar' ? 'pr-14 pl-10' : 'pl-14 pr-10' }} text-secondary font-bold transition-all duration-300 outline-none appearance-none">
                                        <option value="">{{ __('messages.unassigned') ?? __('Unassigned') }}</option>
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee->id }}"
                                                {{ old('employee_id', $task->employee_id) == $employee->id ? 'selected' : '' }}>
                                                {{ $employee->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div
                                        class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'left-4' : 'right-4' }} flex items-center pointer-events-none text-gray-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Due Date -->
                            <div class="space-y-3">
                                <label for="due_date"
                                    class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.due_date_label') }}</label>
                                <div class="relative group">
                                    <div
                                        class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-3' : 'left-3' }} flex items-center pointer-events-none transition-all duration-300">
                                        <div
                                            class="w-8 h-8 rounded-xl bg-primary/10 group-focus-within:bg-primary/20 flex items-center justify-center transition-all duration-300 group-focus-within:scale-110">
                                            <svg class="w-4 h-4 text-primary/60 group-focus-within:text-primary transition-colors duration-300"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                    </div>
                                    <input type="date" name="due_date" id="due_date"
                                        value="{{ old('due_date', $task->due_date?->format('Y-m-d')) }}"
                                        class="block w-full bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl py-4 {{ app()->getLocale() == 'ar' ? 'pr-14 pl-6' : 'pl-14 pr-6' }} text-secondary font-bold transition-all duration-300 outline-none">
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="md:col-span-2 space-y-4">
                                <label for="status"
                                    class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.status') ?? __('Status') }}</label>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                                    @foreach (['todo', 'in_progress', 'done'] as $status)
                                        <label class="cursor-pointer group relative">
                                            <input type="radio" name="status" value="{{ $status }}"
                                                {{ old('status', $task->status) == $status ? 'checked' : '' }}
                                                class="peer hidden">
                                            <div
                                                class="p-4 rounded-2xl border-2 border-gray-100 bg-gray-50 text-center transition-all duration-300 peer-checked:border-primary peer-checked:bg-white peer-checked:shadow-lg peer-checked:shadow-primary/20 group-hover:border-primary/30">
                                                <div
                                                    class="w-6 h-6 rounded-full border-2 border-gray-200 mx-auto mb-3 flex items-center justify-center peer-checked:border-primary transition-colors duration-300 group-hover:border-primary/40 relative">
                                                    <div class="w-3 h-3 rounded-full bg-primary scale-0 peer-checked:scale-100 transition-transform duration-300 absolute"></div>
                                                </div>
                                                <span
                                                    class="block text-xs font-black uppercase tracking-widest text-gray-400 peer-checked:text-secondary group-hover:text-secondary/70 transition-colors duration-300">{{ __($status) }}</span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2 space-y-3">
                                <label for="description"
                                    class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.task_description_label') }}</label>
                                <textarea name="description" id="description" rows="6"
                                    class="block w-full bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-3xl py-6 px-8 text-secondary font-medium transition-all duration-300 outline-none"
                                    placeholder="{{ __('Provide detailed instructions...') }}">{{ old('description', $task->description) }}</textarea>
                            </div>

                            <!-- Attachment -->
                            <div class="md:col-span-2 space-y-3" x-data="{ filesCount: 0 }">
                                <label for="attachments"
                                    class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.attachments') }}</label>
                                
                                @if($task->attachments && count($task->attachments) > 0)
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                                        @foreach($task->attachments as $index => $path)
                                            <div class="flex items-center gap-4 p-4 bg-primary/5 border border-primary/20 rounded-2xl group/file">
                                                <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-xs font-black text-secondary truncate">{{ basename($path) }}</p>
                                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ __('File') }} #{{ $index + 1 }}</p>
                                                </div>
                                                <a href="{{ Storage::url($path) }}" target="_blank"
                                                    class="inline-flex items-center px-4 py-2 bg-white hover:bg-primary hover:text-white border border-primary/20 rounded-xl text-[10px] font-black uppercase transition-all duration-300">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <label class="relative group block h-40 cursor-pointer">
                                    <input type="file" name="attachments[]" id="attachments" multiple
                                        class="absolute inset-0 w-full h-full opacity-0 z-10 cursor-pointer"
                                        @change="filesCount = $event.target.files.length">
                                    <div
                                        class="absolute inset-0 border-2 border-dashed border-gray-100 bg-gray-50 rounded-[2rem] group-hover:border-primary group-hover:bg-primary/5 transition-all duration-300 flex flex-col items-center justify-center overflow-hidden px-10">
                                        <div class="w-12 h-12 rounded-2xl bg-white shadow-sm flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-500">
                                            <svg x-show="filesCount == 0" class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                            </svg>
                                            <div x-show="filesCount > 0" class="relative">
                                                <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                <span class="absolute -top-2 -right-2 bg-emerald-500 text-white text-[8px] font-black w-4 h-4 rounded-full flex items-center justify-center border-2 border-white" x-text="filesCount"></span>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-sm font-black text-secondary mb-1" x-text="filesCount > 0 ? filesCount + ' {{ __('messages.files_selected_label') }}' : '{{ __('messages.upload_task_attachments') }}'"></p>
                                            <p x-show="filesCount == 0" class="text-[10px] font-bold text-gray-400 tracking-wider uppercase">{{ __('messages.pdf_limit_hint') }}</p>
                                        </div>
                                    </div>
                                </label>
                                @error('attachments.*') <p class="text-rose-500 text-[10px] font-bold mt-2 ml-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="pt-10 border-t border-gray-50 flex items-center justify-center gap-6">
                        <a href="{{ route('client.tasks.index') }}"
                            class="px-10 py-4 text-gray-500 hover:text-secondary font-black transition-colors">
                            {{ __('messages.cancel') }}
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-16 py-5 bg-primary hover:bg-[#8affaa] text-secondary text-base font-black rounded-3xl shadow-[0_20px_40px_rgba(var(--color-primary-rgb),0.2)] transition-all duration-300 hover:scale-105 active:scale-95 group/submit">
                            <svg class="w-6 h-6 me-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            {{ __('messages.update') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
