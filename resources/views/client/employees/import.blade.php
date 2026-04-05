@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Premium Hero Section -->
        <div class="bg-secondary overflow-hidden shadow-2xl rounded-3xl p-10 text-white mb-10 relative group border border-primary/20">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <h1 class="text-4xl font-extrabold mb-2 tracking-tight text-primary">{{ __('messages.import_employees') }}</h1>
                    <p class="text-gray-300 text-lg opacity-90">{{ __('messages.import_format_hint') }}</p>
                </div>
                
                <div class="flex items-center gap-4">
                    <a href="{{ route('client.employees.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-white/10 hover:bg-white/20 border border-white/20 text-white text-sm font-bold rounded-2xl transition-all duration-300 backdrop-blur-md group/back">
                        <svg class="w-5 h-5 me-2 group-hover/back:-translate-x-1 transition-transform rtl:group-hover/back:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        {{ __('messages.back') }}
                    </a>
                </div>
            </div>
            
            <!-- Animated decorative overlays -->
            <div class="absolute top-[-2rem] right-[-2rem] w-48 h-48 bg-primary opacity-5 rounded-full transition-transform duration-700 group-hover:scale-110"></div>
            <div class="absolute bottom-[-1rem] left-[10%] w-24 h-24 bg-primary opacity-5 rounded-full transition-transform duration-500 group-hover:-translate-y-4"></div>
        </div>

        @if ($errors->any())
            <div class="mb-8 animate-in fade-in slide-in-from-top-4 duration-500">
                <div class="bg-red-50 border border-red-100 p-5 rounded-[1.5rem] flex items-center gap-4 shadow-sm">
                    <div class="w-10 h-10 rounded-full bg-red-500/10 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
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
        <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100/50 overflow-hidden transition-all duration-500">
            <div class="p-8 border-b border-gray-50 bg-secondary/5">
                <h2 class="text-xl font-black text-secondary mb-3 flex items-center gap-3">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ __('messages.import_instructions') }}
                </h2>
                <div class="mt-4 flex flex-wrap gap-2">
                    @php
                        $cols = [
                            ['col' => 'A', 'key' => 'Employee Name (Arabic)', 'label' => __('messages.name_ar')],
                            ['col' => 'B', 'key' => 'Employee Name (English)', 'label' => __('messages.name_en')],
                            ['col' => 'C', 'key' => 'Email Address', 'label' => __('messages.email')],
                            ['col' => 'D', 'key' => 'Password', 'label' => __('messages.password')],
                            ['col' => 'E', 'key' => 'Position', 'label' => __('messages.position')],
                            ['col' => 'F', 'key' => 'National ID / Residency Number', 'label' => __('messages.national_id_number')],
                            ['col' => 'G', 'key' => 'Phone Number', 'label' => __('messages.phone_field')],
                            ['col' => 'H', 'key' => 'Emergency Phone', 'label' => __('messages.emergency_phone')],
                            ['col' => 'I', 'key' => 'Bank IBAN', 'label' => __('messages.bank_iban')],
                            ['col' => 'J', 'key' => 'Basic Salary', 'label' => __('messages.basic_salary')],
                            ['col' => 'K', 'key' => 'Housing Allowance', 'label' => __('messages.housing_allowance')],
                            ['col' => 'L', 'key' => 'Transportation Allowance', 'label' => __('messages.transportation_allowance')],
                            ['col' => 'M', 'key' => 'Other Allowances', 'label' => __('messages.other_allowances')],
                            ['col' => 'N', 'key' => 'Date of Birth', 'label' => __('messages.date_of_birth')],
                            ['col' => 'O', 'key' => 'Hire Date', 'label' => __('messages.hire_date')],
                        ];
                    @endphp
                    @foreach($cols as $col)
                        <div class="flex flex-col bg-white border border-gray-100 rounded-xl p-3 shadow-sm hover:border-primary transition-all hover:scale-105">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="w-6 h-6 rounded-lg bg-secondary text-white text-[10px] font-black flex items-center justify-center">{{ $col['col'] }}</span>
                                <span class="text-xs font-black text-secondary select-all">{{ $col['key'] }}</span>
                            </div>
                            <span class="text-[10px] font-bold text-gray-400 ms-8">{{ $col['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <form method="POST" action="{{ route('client.employees.import') }}" enctype="multipart/form-data" class="p-12 space-y-10">
                @csrf

                <div>
                    <label for="file" class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-4">{{ __('messages.upload_excel') }} <span class="text-primary">*</span></label>
                    <div class="mt-1 flex justify-center px-6 pt-12 pb-12 border-[3px] border-gray-200 border-dashed rounded-[2rem] hover:border-primary transition-colors bg-gray-50 focus-within:border-primary group">
                        <div class="space-y-4 text-center">
                            <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto shadow-sm group-hover:scale-110 transition-transform duration-500">
                                <svg class="mx-auto h-10 w-10 text-primary" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <div class="flex text-sm text-gray-600 justify-center">
                                <label for="file" class="relative cursor-pointer bg-primary/10 hover:bg-primary/20 rounded-xl font-black text-secondary focus-within:outline-none transition-all duration-300">
                                    <span class="px-6 py-3 inline-block">{{ __('messages.choose_file') }}</span>
                                    <input id="file" name="file" type="file" class="sr-only" accept=".xlsx,.xls">
                                </label>
                            </div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-2">{{ __('messages.excel_size_hint') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Import Results Errors -->
                @if(session('import_failures'))
                    <div class="bg-red-50 border border-red-100 p-6 rounded-[1.5rem] shadow-sm">
                        <h3 class="text-sm font-black text-red-800 mb-4 tracking-tight flex items-center gap-2">
                            <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            {{ __('messages.import_errors') }}
                        </h3>
                        <div class="max-h-60 overflow-y-auto space-y-3 bg-white p-4 rounded-xl border border-red-100/50">
                            @foreach(session('import_failures') as $failure)
                                <div class="text-xs flex items-start gap-2 bg-red-50/50 p-2 rounded-lg">
                                    <span class="font-black text-red-800 bg-red-100 px-2 py-0.5 rounded-md">Row {{ $failure->row() }}:</span>
                                    <span class="text-red-600 font-bold mt-0.5">{{ implode(', ', $failure->errors()) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                @if(session('import_success_count') > 0)
                    <div class="bg-emerald-50 border border-emerald-100 p-5 rounded-[1.5rem] flex items-center gap-4 shadow-sm">
                        <div class="w-10 h-10 rounded-full bg-emerald-500/10 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <p class="text-emerald-800 font-bold tracking-tight">{{ __('messages.import_partial_success', ['count' => session('import_success_count')]) }}</p>
                    </div>
                @endif

                <!-- Form Actions -->
                <div class="pt-10 border-t border-gray-50 flex items-center justify-center gap-6">
                    <a href="{{ route('client.employees.index') }}" 
                       class="px-10 py-4 text-gray-500 hover:text-secondary font-black transition-colors">
                        {{ __('messages.cancel') }}
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-20 py-5 bg-primary hover:bg-[#8affaa] text-secondary text-lg font-black rounded-3xl shadow-[0_20px_50px_rgba(var(--color-primary-rgb),0.3)] hover:shadow-[0_25px_60px_rgba(var(--color-primary-rgb),0.5)] border-b-4 border-emerald-400 hover:border-emerald-300 transition-all duration-500 hover:-translate-y-2 active:translate-y-1 active:border-b-0 group/submit">
                        <svg class="w-7 h-7 me-4 group-hover/submit:-translate-y-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        {{ __('messages.import') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
