@props(['active' => false, 'badge' => 0])

@php
$classes = $active
            ? 'flex items-center px-3 py-2 text-sm font-medium rounded-lg bg-primary/10 text-primary transition-colors group'
            : 'flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors group';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    <div class="relative flex items-center justify-center">
        {{ $icon }}
        
        @if($badge > 0)
            <span x-show="collapsed" class="absolute -top-1 -right-1 flex items-center justify-center w-3 h-3 text-[10px] font-bold text-white bg-red-500 rounded-full">
                {{ $badge > 99 ? '99+' : $badge }}
            </span>
        @endif
    </div>

    <span x-show="!collapsed" class="ms-3 flex-1 whitespace-nowrap transition-opacity duration-300">
        {{ $slot }}
    </span>

    @if($badge > 0)
        <span x-show="!collapsed" class="inline-flex items-center justify-center px-2 py-0.5 ms-3 text-xs font-medium text-red-600 bg-red-100 rounded-full">
            {{ $badge }}
        </span>
    @endif
</a>
