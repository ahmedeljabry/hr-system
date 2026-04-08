@extends('layouts.app')

@section('content')
<div class="pt-8 pb-12">
    <div class="max-w-4xl mx-auto">
        
        <!-- Standard Header -->
        <x-dashboard-sub-header 
            :title="__('messages.terminate_employee')" 
            :subtitle="$employee->name"
            :backLink="route('client.employees.index')"
        />

        @if ($errors->any())
            <div class="mb-8 bg-red-50 border border-red-100 p-5 rounded-2xl shadow-sm flex items-center gap-4">
                <ul class="text-sm font-bold text-red-800 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100/50 overflow-hidden">
            <form action="{{ route('client.employees.terminate', $employee->id) }}" method="POST" enctype="multipart/form-data" class="p-12 space-y-10">
                @csrf

                <div class="grid grid-cols-1 gap-10">
                    <!-- Termination Reason -->
                    <div class="space-y-4">
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.termination_reason') }} <span class="text-primary">*</span></label>
                        <div class="grid grid-cols-1 gap-4">
                            @foreach($reasons as $reason)
                                <label class="relative flex items-start p-6 rounded-3xl border-2 cursor-pointer transition-all duration-300 hover:bg-gray-50 group {{ old('reason_case') == $reason->value ? 'border-primary bg-primary/5' : 'border-gray-100' }}">
                                    <input type="radio" name="reason_case" value="{{ $reason->value }}" class="hidden" {{ old('reason_case') == $reason->value ? 'checked' : '' }} onchange="updateSelection(this)">
                                    <div class="flex-shrink-0 w-6 h-6 rounded-full border-2 flex items-center justify-center transition-all duration-300 {{ old('reason_case') == $reason->value ? 'border-primary bg-primary' : 'border-gray-200 group-hover:border-primary/50' }}">
                                        @if(old('reason_case') == $reason->value)
                                            <svg class="w-3 h-3 text-secondary" fill="currentColor" viewBox="0 0 24 24"><path d="M20.285 2l-11.285 11.567-5.286-5.011-3.714 3.716 9 8.728 15-15.285z"/></svg>
                                        @endif
                                    </div>
                                    <div class="ms-4">
                                        <div class="text-sm font-black text-secondary mb-1">{{ $reason->label() }}</div>
                                        <div class="flex items-center gap-4">
                                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ __('messages.article') }}: {{ $reason->article() }}</span>
                                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ __('messages.notice_period') }}: {{ app()->getLocale() == 'ar' ? $reason->noticePeriodAr() : $reason->noticePeriod() }}</span>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Comments -->
                    <div class="space-y-4">
                        <label for="comments" class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.comments') }}</label>
                        <textarea name="comments" id="comments" rows="4" class="block w-full bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl py-4 px-6 text-secondary font-bold transition-all duration-300 outline-none" placeholder="{{ __('messages.termination_comments_placeholder') }}">{{ old('comments') }}</textarea>
                    </div>

                    <!-- File Upload -->
                    <div class="space-y-4">
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.attachments') }}</label>
                        <div x-data="{ fileCount: 0 }" class="relative">
                            <label class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-100 rounded-[2rem] bg-gray-50 hover:bg-primary/5 hover:border-primary transition-all duration-500 cursor-pointer group">
                                <input type="file" name="files[]" multiple class="hidden" @change="fileCount = $event.target.files.length">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-10 h-10 mb-4 text-gray-300 group-hover:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                    <p class="mb-2 text-sm font-bold text-gray-400" x-text="fileCount > 0 ? fileCount + ' {{ __('messages.files_selected') }}' : '{{ __('messages.termination_files_hint') }}'"></p>
                                    <p class="text-[10px] text-gray-300 uppercase font-black tracking-widest">{{ __('messages.drag_and_drop') }}</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="pt-10 border-t border-gray-50 flex items-center justify-center gap-6">
                    <a href="{{ route('client.employees.index') }}" class="px-10 py-4 text-gray-500 hover:text-secondary font-black transition-colors">{{ __('messages.cancel') }}</a>
                    <button type="submit" class="px-20 py-5 bg-red-500 hover:bg-red-600 text-white text-lg font-black rounded-3xl shadow-xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-1 active:translate-y-0">
                        {{ __('messages.confirm_termination') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function updateSelection(input) {
        // Remove active class from all labels
        document.querySelectorAll('label.relative').forEach(label => {
            label.classList.remove('border-primary', 'bg-primary/5');
            label.classList.add('border-gray-100');
            const circle = label.querySelector('.flex-shrink-0');
            circle.classList.remove('border-primary', 'bg-primary');
            circle.classList.add('border-gray-200');
            const svg = circle.querySelector('svg');
            if (svg) svg.remove();
        });

        // Add active class to selected label
        const label = input.closest('label');
        label.classList.remove('border-gray-100');
        label.classList.add('border-primary', 'bg-primary/5');
        
        const circle = label.querySelector('.flex-shrink-0');
        circle.classList.remove('border-gray-200');
        circle.classList.add('border-primary', 'bg-primary');
        
        const svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
        svg.setAttribute("class", "w-3 h-3 text-secondary");
        svg.setAttribute("fill", "currentColor");
        svg.setAttribute("viewBox", "0 0 24 24");
        const path = document.createElementNS("http://www.w3.org/2000/svg", "path");
        path.setAttribute("d", "M20.285 2l-11.285 11.567-5.286-5.011-3.714 3.716 9 8.728 15-15.285z");
        svg.appendChild(path);
        circle.appendChild(svg);
    }
</script>
@endsection
