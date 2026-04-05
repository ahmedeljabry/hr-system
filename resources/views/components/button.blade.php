@props(['variant' => 'primary'])

@php
    $variants = [
        'primary' => 'bg-primary text-white hover:bg-blue-600 shadow-blue-200',
        'secondary' => 'bg-white text-text-main border border-gray-200 hover:bg-gray-50',
        'danger' => 'bg-red-500 text-white hover:bg-red-600 shadow-red-100',
    ];
    $variantClass = $variants[$variant] ?? $variants['primary'];
@endphp

<button {{ $attributes->merge(['class' => "inline-flex items-center justify-center ps-6 pe-6 py-3 font-bold rounded-xl transition-all duration-300 transform active:scale-95 shadow-lg $variantClass"]) }}>
    {{ $slot }}
</button>
