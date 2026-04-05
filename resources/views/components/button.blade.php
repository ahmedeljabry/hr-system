@props(['variant' => 'primary'])

@php
    $variants = [
        /* Entelaka Style: Dark text on Lime Green for maximum visibility */
        'primary' => 'bg-primary hover:bg-[#8affaa] text-secondary shadow-[0_20px_50px_rgba(var(--color-primary-rgb),0.3)] hover:shadow-[0_25px_60px_rgba(var(--color-primary-rgb),0.5)] border-b-4 border-emerald-400 hover:border-emerald-300 transition-all duration-500 hover:-translate-y-1 active:translate-y-1 active:border-b-0',
        'secondary' => 'bg-white hover:bg-white/95 text-secondary border-b-4 border-gray-200 hover:border-gray-100 shadow-[0_15px_35px_rgba(0,0,0,0.05)] hover:-translate-y-1 active:translate-y-0.5 active:border-b-0 border border-gray-100',
        'danger' => 'bg-red-500 hover:bg-red-600 text-white shadow-[0_15px_35px_rgba(239,68,68,0.3)] border-b-4 border-red-700 hover:border-red-600 hover:-translate-y-1 active:translate-y-1 active:border-b-0',
    ];
    $variantClass = $variants[$variant] ?? $variants['primary'];
@endphp

<button {{ $attributes->merge(['class' => "inline-flex items-center justify-center px-10 py-4 font-black rounded-2xl transition-all duration-500 $variantClass"]) }}>
    {{ $slot }}
</button>
