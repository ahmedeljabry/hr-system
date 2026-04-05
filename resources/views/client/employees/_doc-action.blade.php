<div class="group/doc border-2 border-dashed {{ $file ? 'border-primary/20 bg-primary/5' : 'border-gray-50 bg-gray-50/50' }} rounded-[2rem] p-6 transition-all hover:bg-primary/10">
    <div class="flex items-center gap-4 mb-3">
        <div class="w-10 h-10 rounded-2xl {{ $file ? 'bg-primary text-secondary' : 'bg-gray-200 text-gray-400' }} flex items-center justify-center shadow-lg transition-transform group-hover/doc:scale-110">
            @if($type == 'national_id')
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-4m-4 0V4a2 2 0 114 0v2m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
            @elseif($type == 'cv')
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            @elseif($type == 'contract')
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 0012 20a10.003 10.003 0 006.255-2.164l.054.09A9.991 9.991 0 0112 22c-5.523 0-10-4.477-10-10 0-2.39 1.833-4.35 4.14-4.632M12 11c0-5.523 4.477-10 10-10 2.39 0 4.35 1.833 4.632 4.14M12 11c0 5.523-4.477 10-10 10-2.39 0-4.35-1.833-4.632-4.14M12 11c0-5.523 4.477-10 10-10"></path></svg>
            @endif
        </div>
        <div>
            <h4 class="text-xs font-black text-secondary tracking-tight">{{ $label }}</h4>
            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">{{ $file ? __('messages.view') : __('messages.not_uploaded') ?? 'NOT UPLOADED' }}</p>
        </div>
    </div>
    @if($file)
        <a href="{{ route('client.files.employee', [$employee->id, $type]) }}" 
           class="inline-flex justify-center w-full py-2.5 bg-white border border-gray-100 rounded-xl text-[10px] font-black text-secondary hover:bg-secondary hover:text-white transition-all shadow-sm">
            {{ __('messages.open_document') ?? 'OPEN DOCUMENT' }}
        </a>
    @else
        <div class="py-2.5 text-center text-[10px] font-black text-gray-300 italic opacity-50 uppercase tracking-widest">
            {{ __('messages.pending') }}
        </div>
    @endif
</div>
