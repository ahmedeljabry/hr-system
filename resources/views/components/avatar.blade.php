@props([])

@php
    $sizeClasses = match($size) {
        'sm' => 'w-8 h-8 text-xs',
        'md' => 'w-10 h-10 text-sm',
        'lg' => 'w-16 h-16 text-xl',
        'xl' => 'w-24 h-24 text-3xl',
        default => 'w-10 h-10 text-sm'
    };
@endphp

@if($image)
    <img src="{{ $image }}" alt="{{ $name }}" {{ $attributes->merge(['class' => "object-cover rounded-full $sizeClasses"]) }}>
@else
    <div {{ $attributes->merge(['class' => "flex items-center justify-center rounded-full font-bold text-white shrink-0 shadow-inner $sizeClasses"]) }} style="background-color: {{ $getColorHex() }}">
        {{ $getInitials() }}
    </div>
@endif