@props(['title' => '', 'id' => 'slide-over'])

<div 
    x-data="{ 
        open: false,
        openSlideOver() { this.open = true; document.body.classList.add('overflow-hidden'); },
        closeSlideOver() { this.open = false; document.body.classList.remove('overflow-hidden'); }
    }"
    @open-slide-over-{{ $id }}.window="openSlideOver()"
    @close-slide-over-{{ $id }}.window="closeSlideOver()"
    @keydown.escape.window="closeSlideOver()"
    class="relative z-50"
    aria-labelledby="slide-over-title"
    role="dialog"
    aria-modal="true"
    x-cloak
>
    <!-- Background backdrop -->
    <div 
        x-show="open" 
        x-transition:enter="ease-in-out duration-500" 
        x-transition:enter-start="opacity-0" 
        x-transition:enter-end="opacity-100" 
        x-transition:leave="ease-in-out duration-500" 
        x-transition:leave-start="opacity-100" 
        x-transition:leave-end="opacity-0" 
        class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
        @click="closeSlideOver()"
    ></div>

    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute inset-0 overflow-hidden">
            <div class="pointer-events-none fixed inset-y-0 right-0 rtl:left-0 rtl:right-auto flex max-w-full pl-10 rtl:pr-10 rtl:pl-0">
                <!-- Slide-over panel -->
                <div 
                    x-show="open" 
                    x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700" 
                    x-transition:enter-start="translate-x-full rtl:-translate-x-full" 
                    x-transition:enter-end="translate-x-0" 
                    x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700" 
                    x-transition:leave-start="translate-x-0" 
                    x-transition:leave-end="translate-x-full rtl:-translate-x-full" 
                    class="pointer-events-auto relative w-screen max-w-md bg-white shadow-xl flex flex-col"
                    @click.stop
                >
                    <!-- Close button in upper corner -->
                    <div class="absolute top-0 left-0 rtl:right-0 rtl:left-auto -ml-8 rtl:-mr-8 rtl:ml-0 flex pt-4 pr-2 rtl:pl-2 rtl:pr-0 sm:-ml-10 sm:rtl:-mr-10 sm:pr-4 sm:rtl:pl-4">
                        <button 
                            type="button" 
                            class="rounded-md text-gray-300 hover:text-white focus:outline-none focus:ring-2 focus:ring-white" 
                            @click="closeSlideOver()"
                        >
                            <span class="sr-only">{{ __('messages.close_panel') }}</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="flex flex-col h-full overflow-y-auto w-full">
                        <div class="px-4 py-6 sm:px-6 border-b border-gray-100 bg-gray-50">
                            <h2 class="text-lg font-bold leading-6 text-gray-900" id="slide-over-title">{{ $title }}</h2>
                        </div>
                        <div class="relative flex-1 px-4 py-6 sm:px-6 w-full">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
