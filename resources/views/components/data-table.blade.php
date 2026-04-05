@props(['endpoint'])

<div 
    x-data="{
        endpoint: '{{ $endpoint }}',
        data: [],
        links: [],
        search: '',
        loading: true,
        selected: [],
        
        init() {
            this.fetchData(this.endpoint);
            this.$watch('search', Alpine.debounce(() => this.fetchData(this.endpoint), 300));
        },
        
        fetchData(url) {
            if (!url) return;
            this.loading = true;
            
            const separator = url.includes('?') ? '&' : '?';
            const fetchUrl = `${url}${separator}search=${encodeURIComponent(this.search)}`;
            
            fetch(fetchUrl, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(res => {
                this.data = res.data;
                this.links = res.links;
                this.loading = false;
            })
            .catch(() => this.loading = false);
        },

        toggleAll(e) {
            if (e.target.checked) {
                this.selected = this.data.map(item => item.id);
            } else {
                this.selected = [];
            }
        }
    }"
    class="w-full bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden relative"
>
    <!-- Header Tools -->
    <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center bg-gray-50/50">
        <div class="relative w-full sm:w-72">
            <input 
                type="text" 
                x-model="search" 
                placeholder="{{ __('Search...') }}"
                class="w-full px-4 py-2 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all outline-none"
            >
            <div class="absolute inset-y-0 inline-end-0 pe-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4 sm:mt-0">
            {{ $actions ?? '' }}
        </div>
    </div>

    <!-- Bulk Action Bar -->
    <x-bulk-action-bar />

    <!-- Table Wrapper -->
    <div class="overflow-x-auto min-h-[300px] relative">
        <!-- Loading Overlay -->
        <div x-show="loading" class="absolute inset-0 bg-white/70 z-10 flex items-center justify-center transition-opacity duration-300">
            <svg class="animate-spin h-8 w-8 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-sm md:text-base text-gray-500 uppercase tracking-wider">
                    <th class="px-6 py-4 font-semibold w-12">
                        <input type="checkbox" @change="toggleAll" class="rounded border-gray-300 text-primary shadow-sm focus:ring-primary w-4 h-4">
                    </th>
                    {{ $head }}
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm md:text-base text-gray-700">
                <template x-for="item in data" :key="item.id">
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <input type="checkbox" :value="item.id" x-model="selected" class="rounded border-gray-300 text-primary shadow-sm focus:ring-primary w-4 h-4">
                        </td>
                        <!-- Use interpolation or custom a template passed from parent. For a generic component, rendering an Alpine slot is complex without custom logic. Here we assume the parent supplies an Alpine template chunk -->
                        {{ $body }}
                    </tr>
                </template>
                <tr x-show="!loading && data.length === 0">
                    <td colspan="100%" class="px-6 py-12 text-center text-gray-500">
                        {{ __('No records found.') }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pagination Settings -->
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex flex-col md:flex-row items-center justify-between">
        <span class="text-sm text-gray-600 mb-4 md:mb-0">
            {{ __('Showing results dynamically') }}
        </span>
        <div class="flex items-center space-x-1 {{ document?.documentElement?.dir === 'rtl' ? 'space-x-reverse' : '' }}">
            <template x-for="link in links">
                <button 
                    @click.prevent="fetchData(link.url)" 
                    x-html="link.label" 
                    :disabled="!link.url || link.active"
                    :class="[
                        'px-3 py-1 rounded text-sm transition-colors',
                        link.active ? 'bg-primary text-white' : (link.url ? 'bg-white border text-gray-700 hover:bg-gray-50' : 'text-gray-400')
                    ]"
                ></button>
            </template>
        </div>
    </div>
</div>
