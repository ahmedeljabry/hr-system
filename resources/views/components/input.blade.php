@props(['disabled' => false, 'error' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'w-full ps-4 pe-4 py-3 bg-gray-50 dark:bg-gray-800 border ' . ($error ? 'border-red-500 text-red-600 placeholder-red-300 ring-red-100' : 'border-gray-100 dark:border-gray-700 text-text-main') . ' rounded-xl focus:ring-4 focus:ring-primary/20 focus:border-primary transition-all outline-none disabled:opacity-50 disabled:cursor-not-allowed']) !!}>
