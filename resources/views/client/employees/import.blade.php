@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ __('messages.import_employees') }}</h1>
        <p class="mt-2 text-sm text-gray-500">{{ __('messages.back') }} <a href="{{ route('client.employees.index') }}" class="text-blue-600 hover:text-blue-500 underline font-medium">{{ __('messages.employees') }}</a></p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-50 bg-gray-50/30">
            <h2 class="text-lg font-bold text-gray-900 mb-2">{{ __('messages.import_instructions') }}</h2>
            <p class="text-sm text-gray-600 leading-relaxed">
                {{ __('messages.import_format_hint') }}
            </p>
            <div class="mt-4 flex flex-wrap gap-2">
                @foreach([__('messages.employee_name'), __('messages.position'), __('messages.national_id_number'), __('messages.basic_salary'), __('messages.hire_date')] as $col)
                    <span class="px-2 py-1 bg-white border border-gray-200 rounded text-xs font-bold text-gray-500">{{ $col }}</span>
                @endforeach
            </div>
        </div>

        <form method="POST" action="{{ route('client.employees.import') }}" enctype="multipart/form-data" class="p-8 space-y-6">
            @csrf

            <div>
                <label for="file" class="block text-sm font-semibold text-gray-700 mb-1 tracking-tight">{{ __('messages.upload_excel') }} <span class="text-red-500">*</span></label>
                <div class="mt-1 flex justify-center px-6 pt-10 pb-10 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors bg-gray-50/50">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600 justify-center">
                            <label for="file" class="relative cursor-pointer bg-transparent rounded-md font-semibold text-blue-600 hover:text-blue-500 focus-within:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <span class="px-3 py-1.5 border border-blue-600 rounded-md bg-white hover:bg-blue-50 transition-all inline-block mt-2">{{ __('messages.choose_file') }}</span>
                                <input id="file" name="file" type="file" class="sr-only" accept=".xlsx,.xls">
                            </label>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">{{ __('messages.excel_size_hint') }}</p>
                    </div>
                </div>
                @error('file')
                    <p class="mt-2 text-sm text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Import Results Errors -->
            @if(session('import_failures'))
                <div class="bg-red-50 rounded-lg p-5 border border-red-100">
                    <h3 class="text-sm font-bold text-red-800 mb-3">{{ __('messages.import_errors') }}</h3>
                    <div class="max-h-40 overflow-y-auto space-y-2">
                        @foreach(session('import_failures') as $failure)
                            <div class="text-xs text-red-600 flex items-start">
                                <span class="font-bold mr-2">Row {{ $failure->row() }}:</span>
                                <span>{{ implode(', ', $failure->errors()) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            
            @if(session('import_success_count') > 0)
                <div class="bg-green-50 rounded-lg p-5 border border-green-100">
                    <div class="text-sm text-green-800">
                        {{ __('messages.import_partial_success', ['count' => session('import_success_count')]) }}
                    </div>
                </div>
            @endif

            <div class="pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('client.employees.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                    {{ __('messages.cancel') }}
                </a>
                <button type="submit" class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-bold rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    {{ __('messages.import') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
