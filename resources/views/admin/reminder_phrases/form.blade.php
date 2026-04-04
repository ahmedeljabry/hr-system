@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="reminderForm(@js($events))">
    <h1 class="text-2xl font-semibold text-gray-900">{{ __('Create Reminder Phrase') }}</h1>
    
    <form action="{{ route('admin.reminder-phrases.store') }}" method="POST" class="mt-8 space-y-6">
        @csrf

        <div>
            <label for="event_key" class="block text-sm font-medium text-gray-700">{{ __('Event') }}</label>
            <select x-model="selectedEvent" name="event_key" id="event_key" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <option value="">{{ __('Select an event...') }}</option>
                @foreach(\App\Enums\NotificationEvent::cases() as $event)
                    <option value="{{ $event->value }}">{{ $event->label() }} ({{ $event->value }})</option>
                @endforeach
            </select>
            @error('event_key') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div x-show="selectedEvent" x-cloak class="bg-blue-50 p-4 rounded-md">
            <h3 class="text-sm font-medium text-blue-800">{{ __('Available Variables (Cheat Sheet)') }}</h3>
            <div class="mt-2 text-sm text-blue-700">
                <p>{{ __('You can use the following variables in your phrases. Wrap them in brackets, e.g., {variable_name}.') }}</p>
                <ul class="mt-2 list-disc list-inside">
                    <template x-for="variable in currentVariables" :key="variable">
                        <li><span class="font-mono bg-blue-100 px-1 py-0.5 rounded" x-text="'{' + variable + '}'"></span></li>
                    </template>
                </ul>
            </div>
        </div>

        <div>
            <label for="text_en" class="block text-sm font-medium text-gray-700">{{ __('English Template') }}</label>
            <textarea name="text_en" id="text_en" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required></textarea>
            @error('text_en') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="text_ar" class="block text-sm font-medium text-gray-700">{{ __('Arabic Template') }}</label>
            <textarea name="text_ar" id="text_ar" rows="3" dir="rtl" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm text-right" required></textarea>
            @error('text_ar') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end">
            <button type="submit" class="rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">
                {{ __('Save') }}
            </button>
        </div>
    </form>
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
