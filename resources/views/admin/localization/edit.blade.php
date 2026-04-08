@extends('layouts.admin')

@section('content')
<div class="pt-8 pb-12" x-data="{ 
    jobs: {{ json_encode($decision->jobs) }} || [{ occupation_code: '', job_title_ar: '', job_title_en: '' }],
    addJob() {
        this.jobs.push({ occupation_code: '', job_title_ar: '', job_title_en: '' });
    },
    removeJob(index) {
        if (this.jobs.length > 1) {
            this.jobs.splice(index, 1);
        }
    }
}">
    <div class="max-w-4xl mx-auto">
        <x-dashboard-sub-header 
            :title="__('Edit Localization Decision')" 
            :subtitle="__('Modify existing localization requirements.')"
        />

        <form action="{{ route('admin.localization.update', $decision->id) }}" method="POST" enctype="multipart/form-data" class="mt-8 space-y-8">
            @csrf
            @method('PUT')

            <!-- Basic Info -->
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <span class="w-8 h-8 bg-secondary/20 text-secondary rounded-lg flex items-center justify-center text-sm">1</span>
                    {{ __('Decision Details') }}
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('Saudi Workforce Percentage (%)') }}</label>
                        <input type="number" step="0.01" name="saudi_percentage" value="{{ $decision->saudi_percentage }}" required class="w-full px-4 py-3 rounded-xl border-gray-200 focus:ring-secondary focus:border-secondary transition-all">
                        @error('saudi_percentage') <p class="mt-1 text-sm text-red-500 font-bold">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Jobs Section -->
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                        <span class="w-8 h-8 bg-secondary/20 text-secondary rounded-lg flex items-center justify-center text-sm">2</span>
                        {{ __('Included Jobs') }}
                    </h3>
                    <button type="button" @click="addJob()" class="text-secondary font-bold flex items-center gap-1 hover:underline text-sm uppercase tracking-wider">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                        {{ __('Add Job Section') }}
                    </button>
                </div>

                <div class="space-y-6">
                    <template x-for="(job, index) in jobs" :key="index">
                        <div class="p-6 rounded-2xl bg-gray-50 border border-gray-100 relative group">
                            <button type="button" @click="removeJob(index)" x-show="jobs.length > 1" class="absolute -top-2 -inline-end-2 bg-red-500 text-white p-1.5 rounded-full shadow-md hover:bg-red-600 transition-colors opacity-0 group-hover:opacity-100">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1.5">{{ __('Occupation Code') }}</label>
                                    <input type="text" :name="`jobs[${index}][occupation_code]`" x-model="job.occupation_code" required class="w-full px-4 py-2.5 rounded-xl border-gray-200 focus:ring-secondary focus:border-secondary transition-all">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1.5">{{ __('Job Title (AR)') }}</label>
                                    <input type="text" :name="`jobs[${index}][job_title_ar]`" x-model="job.job_title_ar" required class="w-full px-4 py-2.5 rounded-xl border-gray-200 focus:ring-secondary focus:border-secondary transition-all text-right" dir="rtl">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1.5">{{ __('Job Title (EN)') }}</label>
                                    <input type="text" :name="`jobs[${index}][job_title_en]`" x-model="job.job_title_en" class="w-full px-4 py-2.5 rounded-xl border-gray-200 focus:ring-secondary focus:border-secondary transition-all">
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Files Section -->
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100" x-data="{ 
                files: [],
                handleFiles(e) {
                    this.files = Array.from(e.target.files).map(f => f.name);
                },
                clearFiles() {
                    this.files = [];
                    document.getElementById('files').value = '';
                }
            }">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <span class="w-8 h-8 bg-secondary/20 text-secondary rounded-lg flex items-center justify-center text-sm">3</span>
                    {{ __('Attachments') }}
                </h3>

                @if(!empty($decision->files))
                <div class="mb-8 p-6 bg-gray-50/50 rounded-2xl border border-gray-100">
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">{{ __('Existing Documents') }}</h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($decision->files as $file)
                            <div class="relative group aspect-square rounded-2xl overflow-hidden border border-gray-100 bg-white shadow-sm hover:shadow-md transition-all">
                                @if(Str::endsWith($file, ['.jpg', '.jpeg', '.png', '.gif']))
                                    <img src="{{ asset('storage/' . $file) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center p-4">
                                        <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"></path></svg>
                                        <span class="text-[8px] font-bold text-gray-400 mt-2 truncate w-full text-center px-2">{{ basename($file) }}</span>
                                    </div>
                                @endif
                                <a href="{{ asset('storage/' . $file) }}" target="_blank" class="absolute inset-0 bg-secondary/80 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center text-primary gap-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    <span class="text-[10px] font-black uppercase tracking-widest">{{ __('View') }}</span>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
                
                <div class="space-y-4">
                    <div class="border-2 border-dashed border-gray-200 rounded-2xl p-8 text-center transition-colors hover:border-secondary/50 group bg-gray-50/30">
                        <input type="file" name="files[]" multiple id="files" class="hidden" @change="handleFiles($event)">
                        
                        <div x-show="files.length === 0">
                            <label for="files" class="cursor-pointer">
                                <svg class="w-12 h-12 text-gray-300 mx-auto mb-4 group-hover:text-secondary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                <p class="text-gray-500 font-bold">{{ __('Add more images or documents') }}</p>
                                <p class="text-[10px] text-gray-400 uppercase tracking-widest mt-2">{{ __('PDF, JPG, PNG, DOCX') }}</p>
                            </label>
                        </div>

                        <div x-show="files.length > 0" class="text-start space-y-4 animate-in fade-in zoom-in duration-300">
                            <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 bg-green-500 text-white rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <span class="text-secondary font-black text-sm uppercase tracking-widest" x-text="`${files.length} {{ __('new files selected') }}`"></span>
                                </div>
                                <button type="button" @click="clearFiles()" class="text-red-500 text-xs font-bold hover:underline uppercase tracking-widest">{{ __('Clear All') }}</button>
                            </div>
                            
                            <ul class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                <template x-for="name in files">
                                    <li class="flex items-center gap-2 p-3 bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden">
                                        <svg class="w-4 h-4 text-secondary flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a2 2 0 011.414.586l4 4a2 2 0 01.586 1.414V19a2 2 0 01-2 2z"></path></svg>
                                        <span class="text-xs font-bold text-secondary truncate" x-text="name"></span>
                                    </li>
                                </template>
                            </ul>
                            
                            <label for="files" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg text-xs font-bold transition-colors cursor-pointer w-full justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                {{ __('Add More Files') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex items-center gap-4">
                <button type="submit" class="bg-secondary text-primary font-black px-10 py-4 rounded-2xl hover:shadow-xl transition-all uppercase tracking-widest text-sm">
                    {{ __('Update Decision') }}
                </button>
                <a href="{{ route('admin.localization.index') }}" class="text-gray-400 font-bold hover:text-gray-600 transition-colors uppercase tracking-widest text-xs">
                    {{ __('Cancel') }}
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
