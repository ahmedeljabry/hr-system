@extends('layouts.admin')

@section('content')
<div class="pt-8 pb-12" x-data="reminderForm(@js($events))">
    <div class="max-w-4xl mx-auto">
        <!-- Standard Header -->
        <x-dashboard-sub-header 
            :title="__('messages.create_reminder_phrase') ?? 'Create Reminder Phrase'" 
            :subtitle="__('messages.configure_notification_templates') ?? 'Define how system notifications look and feel for users.'"
        >
            <x-slot name="actions">
                <a href="{{ route('admin.reminder-phrases.index') }}" class="group relative inline-flex items-center gap-3 px-8 py-4 bg-white text-secondary font-black rounded-2xl shadow-[0_10px_30px_rgba(0,0,0,0.02)] border border-gray-100 hover:translate-y-[-2px] transition-all duration-300">
                    <svg class="w-5 h-5 group-hover:translate-x-[-4px] transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span class="text-xs uppercase tracking-widest">{{ __('messages.back') ?? 'Back' }}</span>
                </a>
            </x-slot>
        </x-dashboard-sub-header>

        <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100/50 p-12">
            <form action="{{ route('admin.reminder-phrases.store') }}" method="POST" class="space-y-10">
                @csrf

                <!-- Event Selection -->
                <div class="space-y-4">
                    <label for="event_key" class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">{{ __('messages.notification_event') ?? 'Notification Event' }}</label>
                    <div class="relative">
                        <select x-model="selectedEvent" name="event_key" id="event_key" class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none appearance-none font-bold text-secondary">
                            <option value="">{{ __('messages.select_event_placeholder') ?? 'Select an event...' }}</option>
                            @foreach(\App\Enums\NotificationEvent::cases() as $event)
                                <option value="{{ $event->value }}">{{ $event->label() }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-6 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    @error('event_key') <p class="text-xs font-bold text-red-500 ml-1">{{ $message }}</p> @enderror
                </div>

                <!-- Variables Cheat Sheet -->
                <div x-show="selectedEvent" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="group relative p-8 rounded-3xl bg-secondary shadow-[0_20px_40px_rgba(20,37,51,0.1)] overflow-hidden" 
                     x-cloak>
                    <div class="absolute top-0 right-0 w-32 h-32 bg-primary/10 rounded-bl-[5rem] -mr-10 -mt-10 blur-2xl"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="bg-primary/20 p-2 rounded-xl border border-primary/20">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h3 class="text-sm font-black text-white tracking-tight uppercase">{{ __('messages.available_variables') ?? 'Dynamic Variables' }}</h3>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <template x-for="variable in currentVariables" :key="variable">
                                <span class="px-4 py-2 bg-white/5 border border-white/10 rounded-xl text-xs font-black text-primary font-mono lowercase group-hover:bg-white/10 transition-colors" x-text="'{' + variable + '}'"></span>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- English Template -->
                <div class="space-y-4">
                    <div class="flex justify-between items-center ml-1">
                        <label for="text_en" class="text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('messages.english_template') ?? 'English Template' }}</label>
                        <span class="px-2 py-1 bg-gray-100 text-[10px] font-black text-gray-500 rounded-md uppercase tracking-wider">{{ __('messages.english') }}</span>
                    </div>
                    <textarea name="text_en" id="text_en" rows="4" class="w-full px-6 py-5 bg-gray-50 border border-gray-100 rounded-3xl focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none font-medium text-gray-600 leading-relaxed" placeholder="Enter content in English..." required></textarea>
                    @error('text_en') <p class="text-xs font-bold text-red-500 ml-1">{{ $message }}</p> @enderror
                </div>

                <!-- Arabic Template -->
                <div class="space-y-4">
                    <div class="flex justify-between items-center ml-1">
                        <label for="text_ar" class="text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('messages.arabic_template') ?? 'Arabic Template' }}</label>
                        <span class="px-2 py-1 bg-amber-50 text-[10px] font-black text-amber-600 rounded-md uppercase tracking-wider">{{ __('messages.arabic') }}</span>
                    </div>
                    <textarea name="text_ar" id="text_ar" rows="4" dir="rtl" class="w-full px-8 py-5 bg-gray-50 border border-gray-100 rounded-3xl focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none font-bold text-secondary text-right leading-loose font-arabic" placeholder="...ادخل المحتوى باللغة العربية" required></textarea>
                    @error('text_ar') <p class="text-xs font-bold text-red-500 ml-1">{{ $message }}</p> @enderror
                </div>

                <!-- Form Actions -->
                <div class="pt-6 border-t border-gray-50 flex justify-end items-center gap-6">
                    <a href="{{ route('admin.reminder-phrases.index') }}" class="text-xs font-black text-gray-400 uppercase tracking-widest hover:text-secondary transition-colors">{{ __('messages.cancel') ?? 'Cancel' }}</a>
                    <button type="submit" class="group relative inline-flex items-center gap-3 px-10 py-4 bg-primary text-secondary font-black rounded-2xl shadow-[0_15px_40px_rgba(20,37,51,0.15)] hover:translate-y-[-2px] transition-all duration-300">
                        <span class="text-xs uppercase tracking-widest">{{ __('messages.save_phrase') ?? 'Save Template' }}</span>
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7-7 7"></path></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('reminderForm', (eventsMap) => ({
            selectedEvent: '',
            get currentVariables() {
                return this.selectedEvent && eventsMap[this.selectedEvent] ? eventsMap[this.selectedEvent] : [];
            }
        }));
    });
</script>
@endpush
@endsection

