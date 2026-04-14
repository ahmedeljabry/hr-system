@extends('layouts.app')

@section('content')
<div class="pt-8 pb-12">
    <div class="w-full">
        <!-- Standard Header -->
        <x-dashboard-sub-header 
            :title="__('messages.insurance_policies')" 
            :subtitle="__('messages.insurance_policy_desc')"
        >
            <x-slot name="leading">
                <div class="w-16 h-16 bg-primary/20 rounded-[1.5rem] flex items-center justify-center shrink-0 border border-primary/30 shadow-2xl transition-transform duration-500">
                    <svg class="w-9 h-9 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
            </x-slot>
            <x-slot name="actions">
                <div class="flex items-center gap-3">
                    <form action="{{ route('client.medical-insurance.policies.bulk-import', ['client_slug' => request('client_slug')]) }}" method="POST" enctype="multipart/form-data" class="inline-block">
                        @csrf
                        <input type="file" name="excel_file" id="bulk_excel_file" class="hidden" onchange="this.form.submit()">
                        <button type="button" onclick="document.getElementById('bulk_excel_file').click()" 
                                class="inline-flex items-center px-6 py-3 bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-black rounded-xl transition-all duration-300 backdrop-blur-md group/import">
                            <svg class="w-4 h-4 me-2 transition-transform group-hover/import:-translate-y-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            {{ __('messages.upload_excel') }}
                        </button>
                    </form>

                    <a href="{{ route('client.medical-insurance.policies.create', ['client_slug' => request('client_slug')]) }}" 
                       class="inline-flex items-center px-6 py-3 bg-primary hover:bg-primary/90 text-secondary text-xs font-black rounded-xl shadow-lg transition-all duration-300 hover:-translate-y-1 active:translate-y-0 group/add">
                        <svg class="w-4 h-4 me-2 group-hover/add:rotate-90 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                        </svg>
                        {{ __('messages.add_policy') }}
                    </a>
                </div>
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
                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-sm font-bold text-red-800">{{ session('error') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-8 bg-amber-50 border border-amber-100 p-5 rounded-2xl shadow-sm flex flex-col gap-2 animate-in fade-in slide-in-from-top-4 duration-500">
                <div class="flex items-center gap-4">
                    <div class="bg-amber-100 p-2 rounded-xl">
                        <svg class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <p class="text-sm font-bold text-amber-800">{{ __('messages.import_errors') }}</p>
                </div>
                <ul class="list-disc list-inside text-xs text-amber-700 font-medium {{ app()->getLocale() == 'ar' ? 'pr-12' : 'pl-12' }}">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div x-data="{ showImportInfo: false }" class="mb-10">
            <button @click="showImportInfo = !showImportInfo" 
                    class="group flex items-center gap-3 px-5 py-3 bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md hover:border-primary/30 transition-all duration-300">
                <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary transition-transform group-hover:scale-110" :class="showImportInfo ? 'rotate-90' : ''">
                    <svg class="w-4 h-4 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
                <span class="text-sm font-black text-secondary uppercase tracking-wider">{{ __('messages.import_instructions') }}</span>
                <div class="px-2 py-0.5 rounded-md bg-amber-50 text-[10px] font-black text-amber-600 border border-amber-100 uppercase tracking-tighter">
                    {{ __('messages.required_documents') }}
                </div>
            </button>
            
            <div x-show="showImportInfo" 
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 -translate-y-8 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 -translate-y-8 scale-95"
                 class="mt-6 bg-gradient-to-br from-secondary via-secondary to-[#1a2b3c] p-10 rounded-[3rem] shadow-2xl text-white relative overflow-hidden group border border-white/5" style="display: none;">
                
                <!-- Decorative Glows -->
                <div class="absolute -top-24 -left-24 w-64 h-64 bg-primary/10 rounded-full blur-[100px] pointer-events-none"></div>
                <div class="absolute -bottom-24 -right-24 w-64 h-64 bg-blue-500/10 rounded-full blur-[100px] pointer-events-none"></div>

                <div class="relative z-10">
                    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
                        <div>
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/20 text-primary text-[10px] font-black uppercase tracking-[0.2em] mb-4 border border-primary/20">
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
                                </span>
                                {{ __('messages.instruction_manual') ?? 'Import Guide' }}
                            </div>
                            <h2 class="text-3xl font-black text-white mb-2">{{ __('messages.upload_policy_data') }}</h2>
                            <p class="text-gray-400 text-sm font-bold max-w-2xl leading-relaxed">{{ __('messages.bulk_import_format_hint') }}</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Company Info -->
                        <div class="bg-white/5 backdrop-blur-md p-6 rounded-[2rem] border border-white/10 hover:border-primary/30 transition-all duration-300 group/card">
                            <div class="w-12 h-12 rounded-2xl bg-primary/20 flex items-center justify-center text-primary mb-6 group-hover/card:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <h3 class="text-sm font-black text-white uppercase tracking-widest mb-4 flex items-center justify-between">
                                {{ __('messages.insurance_company') }}
                                <span class="h-1 w-8 bg-primary/30 rounded-full"></span>
                            </h3>
                            <div class="space-y-2">
                                @foreach(['insurance_company', 'company_name', 'اسم الشركة', 'شركة التأمين'] as $alias)
                                    <div class="flex items-center gap-2 px-3 py-2 bg-white/5 rounded-xl border border-white/5 text-[11px] font-bold text-gray-300 hover:text-white transition-colors">
                                        <div class="w-1 h-1 rounded-full bg-primary/50"></div>
                                        {{ $alias }}
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Policy Number -->
                        <div class="bg-white/5 backdrop-blur-md p-6 rounded-[2rem] border border-white/10 hover:border-blue-400/30 transition-all duration-300 group/card">
                            <div class="w-12 h-12 rounded-2xl bg-blue-500/20 flex items-center justify-center text-blue-400 mb-6 group-hover/card:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="text-sm font-black text-white uppercase tracking-widest mb-4 flex items-center justify-between">
                                {{ __('messages.policy_number') }}
                                <span class="h-1 w-8 bg-blue-500/30 rounded-full"></span>
                            </h3>
                            <div class="space-y-2">
                                @foreach(['policy_number', 'رقم البوليصة', 'رقم العقد'] as $alias)
                                    <div class="flex items-center gap-2 px-3 py-2 bg-white/5 rounded-xl border border-white/5 text-[11px] font-bold text-gray-300 hover:text-white transition-colors">
                                        <div class="w-1 h-1 rounded-full bg-blue-500/50"></div>
                                        {{ $alias }}
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Dates -->
                        <div class="bg-white/5 backdrop-blur-md p-6 rounded-[2rem] border border-white/10 hover:border-amber-400/30 transition-all duration-300 group/card">
                            <div class="w-12 h-12 rounded-2xl bg-amber-500/20 flex items-center justify-center text-amber-400 mb-6 group-hover/card:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 class="text-sm font-black text-white uppercase tracking-widest mb-4 flex items-center justify-between">
                                {{ __('messages.period') }}
                                <span class="h-1 w-8 bg-amber-500/30 rounded-full"></span>
                            </h3>
                            <div class="space-y-2">
                                @foreach(['start_date / end_date', 'تاريخ البداية / النهاية', 'بداية التأمين / نهاية التأمين'] as $alias)
                                    <div class="flex items-center gap-2 px-3 py-2 bg-white/5 rounded-xl border border-white/5 text-[11px] font-bold text-gray-300 hover:text-white transition-colors">
                                        <div class="w-1 h-1 rounded-full bg-amber-500/50"></div>
                                        {{ $alias }}
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Employee Details -->
                        <div class="bg-white/5 backdrop-blur-md p-6 rounded-[2rem] border border-white/10 hover:border-rose-400/30 transition-all duration-300 group/card">
                            <div class="w-12 h-12 rounded-2xl bg-rose-500/20 flex items-center justify-center text-rose-400 mb-6 group-hover/card:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <h3 class="text-sm font-black text-white uppercase tracking-widest mb-4 flex items-center justify-between">
                                {{ __('messages.employees') }}
                                <span class="h-1 w-8 bg-rose-500/30 rounded-full"></span>
                            </h3>
                            <div class="space-y-2">
                                @foreach(['national_id / رقم الهوية', 'class / الفئة', 'cost / التكلفة'] as $alias)
                                    <div class="flex items-center gap-2 px-3 py-2 bg-white/5 rounded-xl border border-white/5 text-[11px] font-bold text-gray-300 hover:text-white transition-colors">
                                        <div class="w-1 h-1 rounded-full bg-rose-500/50"></div>
                                        {{ $alias }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-8">
            <!-- Main Content Container -->
            <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100/50 overflow-hidden min-h-[400px] flex flex-col transition-all duration-500">
                
                <!-- Search & Controls -->
                <div class="p-8 border-b border-gray-50 flex flex-col md:flex-row items-center justify-between gap-6 bg-gray-50/30">
                    <form method="GET" action="{{ route('client.medical-insurance.policies.index', ['client_slug' => request('client_slug')]) }}" class="relative w-full max-w-xl group">
                        <div class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-0 pr-6' : 'left-0 pl-6' }} flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-primary transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="block w-full {{ app()->getLocale() == 'ar' ? 'pr-14 pl-10' : 'pl-14 pr-10' }} py-4 text-sm font-medium border-2 border-transparent bg-white rounded-2xl shadow-sm focus:ring-0 focus:border-primary/30 focus:bg-white transition-all placeholder:text-gray-300" 
                               placeholder="{{ __('messages.search_policies') ?? 'Search policies...' }}">
                        
                        @if(request('search'))
                            <button type="button" onclick="window.location.href='{{ route('client.medical-insurance.policies.index', ['client_slug' => request('client_slug')]) }}'" class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'left-4' : 'right-4' }} flex items-center text-gray-300 hover:text-gray-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        @endif
                    </form>
                </div>

                <div class="overflow-x-auto p-1">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100">
                            <th class="px-8 py-5 text-start text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.policy_number') }}</th>
                            <th class="px-8 py-5 text-start text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.insurance_company') }}</th>
                            <th class="px-8 py-5 text-start text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.period') }}</th>
                            <th class="px-8 py-5 text-start text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.employees_count') }}</th>
                            <th class="px-8 py-5 text-start text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.status') }}</th>
                            <th class="px-8 py-5 text-end text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($policies as $policy)
                        <tr class="hover:bg-gray-50/50 transition-all">
                            <td class="px-8 py-6 whitespace-nowrap">
                                <span class="text-sm font-black text-secondary">{{ $policy->policy_number }}</span>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap">
                                <span class="text-sm font-bold text-gray-600">{{ $policy->insuranceCompany?->name ?? '—' }}</span>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap">
                                <span class="text-xs font-bold text-gray-500">{{ $policy->start_date->format('Y-m-d') }} - {{ $policy->end_date->format('Y-m-d') }}</span>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-primary/10 text-primary-dark font-black text-xs">
                                        {{ $policy->employees_count }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap">
                                <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-full {{ $policy->is_expired ? 'bg-red-100 text-red-600' : 'bg-emerald-100 text-emerald-600' }}">
                                    {{ $policy->is_expired ? __('messages.expired') : __('messages.active') }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-end">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('client.medical-insurance.policies.show', ['client_slug' => request('client_slug'), 'medical_insurance_policy' => $policy->id]) }}" 
                                       class="p-2 text-gray-400 hover:text-primary transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('client.medical-insurance.policies.edit', ['client_slug' => request('client_slug'), 'medical_insurance_policy' => $policy->id]) }}" 
                                       class="p-2 text-gray-400 hover:text-secondary transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form id="delete-form-{{ $policy->id }}" action="{{ route('client.medical-insurance.policies.destroy', ['client_slug' => request('client_slug'), 'medical_insurance_policy' => $policy->id]) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" @click="confirmDelete('{{ $policy->id }}', '{{ $policy->policy_number }}')" class="p-2 text-gray-400 hover:text-red-500 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-8 py-32 text-center text-gray-400 font-bold">
                                {{ __('messages.no_records_found') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($policies->hasPages())
                <div class="p-8 border-t border-gray-50">
                    {{ $policies->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(id, number) {
    const isAr = "{{ app()->getLocale() }}" === 'ar';
    Swal.fire({
        title: isAr ? 'هل أنت متأكد؟' : 'Are you sure?',
        text: (isAr ? 'سيتم حذف بوليصة التأمين رقم ' : 'Insurance policy #') + number + (isAr ? ' بشكل نهائي مع كافة ارتباطات الموظفين.' : ' will be permanently deleted along with all employee assignments.'),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#f43f5e',
        cancelButtonColor: '#6b7280',
        confirmButtonText: isAr ? 'نعم، احذفها' : 'Yes, delete it',
        cancelButtonText: isAr ? 'إلغاء' : 'Cancel',
        reverseButtons: isAr,
        borderRadius: '1.5rem',
        customClass: {
            popup: 'rounded-[2rem] border-none shadow-2xl',
            confirmButton: 'rounded-xl font-black px-6 py-3',
            cancelButton: 'rounded-xl font-bold px-6 py-3'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    })
}
</script>
@endpush
@endsection
