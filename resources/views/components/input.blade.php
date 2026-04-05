@props(['disabled' => false, 'error' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'block w-full bg-gray-50 border-2 ' . ($error ? 'border-red-500 text-red-600 focus:border-red-500 ring-red-100' : 'border-transparent focus:border-primary focus:bg-white') . ' rounded-2xl py-4 px-6 text-secondary font-bold transition-all duration-300 outline-none disabled:opacity-50 disabled:cursor-not-allowed placeholder:text-gray-300']) !!}>
