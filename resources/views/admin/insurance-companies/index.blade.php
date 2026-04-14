@extends('layouts.admin')

@section('content')
<div class="pt-8 pb-12" x-data="{ showAddModal: false }">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <x-dashboard-sub-header 
            :title="__('messages.insurance_companies')" 
            :subtitle="__('messages.manage_companies')"
        >
            <x-slot name="actions">
                <button @click="showAddModal = true" 
                   class="inline-flex items-center px-6 py-3 bg-primary hover:bg-primary/90 text-secondary text-xs font-black rounded-xl shadow-lg transition-all duration-300 hover:-translate-y-1 active:translate-y-0 group/add">
                    <svg class="w-4 h-4 me-2 group-hover/add:rotate-90 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" /></svg>
                    {{ __('messages.add_company') }}
                </button>
            </x-slot>
        </x-dashboard-sub-header>

        @if(session('success'))
            <div class="mb-8 bg-green-50 border border-green-100 p-5 rounded-2xl shadow-sm flex items-center gap-4 animate-in fade-in slide-in-from-top-4 duration-500">
                <div class="bg-green-100 p-2 rounded-xl">
                    <svg class="h-6 w-6 text-green-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                </div>
                <p class="text-sm font-bold text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-8 bg-red-50 border border-red-100 p-5 rounded-2xl shadow-sm flex items-center gap-4 animate-in fade-in slide-in-from-top-4 duration-500">
                <div class="bg-red-100 p-2 rounded-xl">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <p class="text-sm font-bold text-red-800">{{ session('error') }}</p>
            </div>
        @endif

        <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100/50 overflow-hidden transition-all duration-500">
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($companies as $company)
                        <div class="p-6 rounded-3xl bg-gray-50 border border-gray-100 flex items-center justify-between group hover:border-primary/20 transition-all">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-2xl bg-primary/10 flex items-center justify-center text-primary">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </div>
                                <span class="text-base font-black text-secondary">{{ $company->name }}</span>
                            </div>
                            
                            <form method="POST" id="delete-form-{{ $company->id }}" action="{{ route('admin.insurance-companies.destroy', $company->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" 
                                        onclick="confirmDelete({{ $company->id }})"
                                        class="p-2 text-gray-300 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="col-span-full py-20 flex flex-col items-center justify-center text-center opacity-60">
                            <div class="bg-gray-100 p-8 rounded-[2.5rem] mb-6">
                                <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold text-secondary mb-2">{{ __('messages.no_companies_found') }}</h3>
                            <p class="text-sm text-gray-400">{{ __('messages.add_company_desc') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <template x-teleport="body">
        <div x-show="showAddModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div x-show="showAddModal" @click="showAddModal = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 bg-secondary/60 backdrop-blur-sm"></div>
            
            <div x-show="showAddModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative bg-white rounded-[2.5rem] shadow-2xl w-full max-w-md overflow-hidden">
                <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                    <h3 class="text-xl font-black text-secondary tracking-tight">{{ __('messages.add_company') }}</h3>
                    <button @click="showAddModal = false" class="p-2 hover:bg-white rounded-full transition-colors text-gray-400 hover:text-secondary">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form action="{{ route('admin.insurance-companies.store') }}" method="POST" class="p-8 space-y-6">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('messages.company_name') }} <span class="text-primary">*</span></label>
                        <input type="text" name="name" required class="block w-full px-5 py-4 text-sm font-bold border-2 border-gray-100 rounded-2xl focus:ring-0 focus:border-primary/30 transition-all bg-gray-50 focus:bg-white" placeholder="{{ __('messages.enter_company_name') }}">
                    </div>

                    <div class="pt-6 flex gap-4">
                        <button type="button" @click="showAddModal = false" class="flex-1 px-8 py-4 bg-gray-100 hover:bg-gray-200 text-secondary font-black rounded-2xl transition-all">{{ __('messages.cancel') }}</button>
                        <button type="submit" class="flex-1 px-8 py-4 bg-primary hover:bg-[#8affaa] text-secondary font-black rounded-2xl shadow-lg transition-all">{{ __('messages.add') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(id) {
    const isAr = document.documentElement.dir === 'rtl';
    
    Swal.fire({
        title: isAr ? 'هل أنت متأكد؟' : 'Are you sure?',
        text: isAr ? 'سيتم حذف شركة التأمين هذه بشكل نهائي.' : 'This insurance company will be permanently deleted.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ff4d4d',
        cancelButtonColor: '#a0aec0',
        confirmButtonText: isAr ? 'نعم، احذف' : 'Yes, delete it!',
        cancelButtonText: isAr ? 'إلغاء' : 'Cancel',
        borderRadius: '2rem',
        customClass: {
            popup: 'rounded-[2rem] border-none shadow-2xl',
            confirmButton: 'rounded-xl font-black px-6 py-3',
            cancelButton: 'rounded-xl font-black px-6 py-3'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    })
}
</script>
@endpush
