@extends('layouts.admin')

@section('content')
<div class="pt-8 pb-12">
    <div class="w-full">
        <!-- Standard Header -->
        <x-dashboard-sub-header 
            :title="__('messages.reminder_phrases') ?? 'Reminder Phrases'" 
            :subtitle="__('messages.manage_system_wide_templates') ?? 'Manage system-wide notification and announcement templates.'"
        >
            <x-slot name="actions">
                <a href="{{ route('admin.reminder-phrases.create') }}" class="group relative inline-flex items-center gap-3 px-8 py-4 bg-primary text-secondary font-black rounded-2xl shadow-[0_10px_30px_rgba(20,37,51,0.1)] hover:translate-y-[-2px] transition-all duration-300">
                    <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="text-xs uppercase tracking-widest">{{ __('messages.add_phrase') ?? 'Add Phrase' }}</span>
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

        <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100/50 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-50">
                            <th class="px-10 py-6 text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.event') ?? 'Event' }}</th>
                            <th class="px-10 py-6 text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.english_content') ?? 'English Content' }}</th>
                            <th class="px-10 py-6 text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.arabic_content') ?? 'Arabic Content' }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($phrases as $phrase)
                        <tr class="hover:bg-gray-50/30 transition-colors group/row">
                            <td class="px-10 py-8">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-secondary font-black text-xs">
                                        {{ substr($phrase->event_key->value, 0, 1) }}
                                    </div>
                                    <span class="text-sm font-black text-secondary uppercase tracking-tight">{{ str_replace('_', ' ', $phrase->event_key->value) }}</span>
                                </div>
                            </td>
                            <td class="px-10 py-8">
                                <p class="text-sm text-gray-500 font-medium leading-relaxed">{{ Str::limit($phrase->text_en, 80) }}</p>
                            </td>
                            <td class="px-10 py-8">
                                <p class="text-sm text-gray-600 font-bold leading-relaxed font-arabic">{{ Str::limit($phrase->text_ar, 80) }}</p>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-10 py-24 text-center">
                                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-200">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <h3 class="text-xl font-black text-secondary tracking-tight mb-2">{{ __('messages.no_phrases_yet') ?? 'No phrases configured yet' }}</h3>
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

