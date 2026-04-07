@props(['apiUrl' => '/employee/notifications/api', 'readUrl' => '/employee/notifications'])

<div x-data="{ 
        open: false,
        notifications: [],
        isLoading: false,
        apiUrl: '{{ $apiUrl }}',
        readUrl: '{{ $readUrl }}',
        
        init() {
            this.$watch('open', value => {
                if(value) {
                    document.body.classList.add('overflow-hidden');
                    this.loadNotifications();
                } else {
                    document.body.classList.remove('overflow-hidden');
                }
            });
        },
        
        async loadNotifications() {
            if (this.notifications.length === 0) {
                this.isLoading = true;
                try {
                    const response = await fetch(this.apiUrl);
                    if (response.ok) {
                        const data = await response.json();
                        this.notifications = data.data;
                    }
                } catch (e) {
                    console.error('Failed to load notifications');
                } finally {
                    this.isLoading = false;
                }
            }
        },

        async markAsRead(id, index) {
            if(!this.notifications[index].read_at) {
                try {
                    await fetch(`${this.readUrl}/${id}/read`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=&quot;csrf-token&quot;]').content
                        }
                    });
                    this.notifications[index].read_at = new Date().toISOString();
                    this.$dispatch('notification-read');
                } catch(e) {}
            }
        }
    }" @toggle-notifications.window="open = !open" class="relative z-50" aria-labelledby="slide-over-title"
    role="dialog" aria-modal="true" x-cloak>
    <div x-show="open" x-transition:enter="ease-in-out duration-500" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in-out duration-500"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="open = false"></div>

    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute inset-0 overflow-hidden">
            <div
                class="pointer-events-none fixed inset-y-0 right-0 rtl:left-0 rtl:right-auto flex max-w-full pl-10 rtl:pr-10 rtl:pl-0 sm:pl-16 sm:rtl:pr-16 sm:rtl:pl-0">
                <div x-show="open" x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
                    x-transition:enter-start="translate-x-full rtl:-translate-x-full"
                    x-transition:enter-end="translate-x-0"
                    x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
                    x-transition:leave-start="translate-x-0"
                    x-transition:leave-end="translate-x-full rtl:-translate-x-full"
                    class="pointer-events-auto w-screen max-w-md">
                    <div class="flex h-full flex-col overflow-y-scroll bg-white shadow-xl">
                        <div class="px-4 py-6 sm:px-6 bg-gray-50 border-b border-gray-100">
                            <div class="flex items-center justify-between">
                                <h2 class="text-base font-bold leading-6 text-gray-900" id="slide-over-title">
                                    {{ __('messages.notifications') ?? 'Notifications' }}</h2>
                                <div class="ml-3 rtl:mr-3 rtl:ml-0 flex h-7 items-center">
                                    <button type="button"
                                        class="relative rounded-md text-gray-400 hover:text-gray-500 focus:outline-none"
                                        @click="open = false">
                                        <span class="absolute -inset-2.5"></span>
                                        <span class="sr-only">{{ __('messages.close_panel') }}</span>
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                            stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="relative mt-6 flex-1 px-4 sm:px-6">

                            <div x-show="isLoading" class="flex justify-center p-8">
                                <svg class="animate-spin h-8 w-8 text-primary" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </div>

                            <div x-show="!isLoading && notifications.length === 0">
                                <x-empty-state
                                    icon="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                                    title="{{ __('messages.no_notifications') }}"
                                    message="{{ __('messages.all_caught_up') }}" />
                            </div>

                            <ul x-show="!isLoading && notifications.length > 0" class="divide-y divide-gray-100">
                                <template x-for="(notification, index) in notifications" :key="notification.id">
                                    <li class="py-4 cursor-pointer hover:bg-gray-50 -mx-4 px-4 transition-colors rounded-xl"
                                        @click="markAsRead(notification.id, index)">
                                        <div class="flex space-x-3 rtl:space-x-reverse">
                                            <div :class="notification.read_at ? 'bg-gray-100' : 'bg-blue-100'"
                                                class="h-10 w-10 rounded-full flex items-center justify-center flex-shrink-0">
                                                <svg class="h-5 w-5"
                                                    :class="notification.read_at ? 'text-gray-500' : 'text-blue-600'"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <div class="flex-1 space-y-1">
                                                <div class="flex items-center justify-between">
                                                    <h3 class="text-sm font-medium"
                                                        :class="notification.read_at ? 'text-gray-900' : 'text-gray-900 font-bold'"
                                                        x-text="notification.title"></h3>
                                                    <p class="text-xs text-gray-500"
                                                        x-text="new Date(notification.created_at).toLocaleDateString()">
                                                    </p>
                                                </div>
                                                <p class="text-sm text-gray-500" x-text="notification.message"></p>
                                            </div>
                                        </div>
                                    </li>
                                </template>
                            </ul>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>