@props(['field', 'successMessage' => ''])

@error($field)
    <p 
        x-data="{ show: true }"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        class="text-sm text-red-600 mt-1 font-inter font-medium"
    >
        {{ $message }}
    </p>
@enderror

@if(!$errors->has($field) && $successMessage)
    <p 
        x-data="{ show: true }"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        class="text-sm text-green-600 mt-1 font-inter font-medium"
    >
        {{ $successMessage }}
    </p>
@endif
