@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold text-gray-900">{{ __('Reminder Phrases') }}</h1>
            <p class="mt-2 text-sm text-gray-700">{{ __('Manage system-wide notification templates.') }}</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <a href="{{ route('admin.reminder-phrases.create') }}" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">
                {{ __('Add Phrase') }}
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mt-4 bg-green-50 p-4 rounded-md">
            <p class="text-green-800">{{ session('success') }}</p>
        </div>
    @endif

    <div class="mt-8 flex flex-col">
        <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ __('Event') }}</th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ __('English') }}</th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ __('Arabic') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($phrases as $phrase)
                            <tr>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-900">{{ $phrase->event_key->value }}</td>
                                <td class="px-3 py-4 text-sm text-gray-500">{{ Str::limit($phrase->text_en, 50) }}</td>
                                <td class="px-3 py-4 text-sm text-gray-500">{{ Str::limit($phrase->text_ar, 50) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-3 py-4 text-sm text-gray-500 text-center">{{ __('No reminder phrases configured.') }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
