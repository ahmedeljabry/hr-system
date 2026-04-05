@props(['title', 'subtitle' => ''])

<div class="flex h-screen w-full">
    <!-- Imagery Panel (Left side in LTR, Right side in RTL) -->
    <div class="hidden md:flex md:w-1/2 bg-secondary relative overflow-hidden items-center justify-center">
        <!-- Abstract background pattern -->
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M0 40V0H40" fill="none" stroke="primary" stroke-width="0.5"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid)"/>
            </svg>
        </div>
        
        <div class="relative z-10 px-12 text-center text-white">
            @if(isset($branding))
                {{ $branding }}
            @else
                <h2 class="text-4xl font-outfit font-bold mb-4 text-primary">{{ __('auth.welcome_back') ?? 'Welcome to the platform' }}</h2>
                <p class="text-lg font-inter opacity-90 max-w-md mx-auto text-gray-300">
                    {{ __('auth.platform_description') ?? 'Manage your workforce efficiently and elevate your HR operations.' }}
                </p>
            @endif
        </div>
    </div>

    <!-- Form Panel -->
    <div class="w-full md:w-1/2 flex flex-col justify-center items-center p-8 bg-surface overflow-y-auto">
        <div class="w-full max-w-md">
            <div class="mb-8 text-start">
                <a href="/" class="inline-block mb-6">
                    <x-application-logo class="w-20 h-20 fill-current text-primary" />
                </a>
                <h1 class="text-3xl font-outfit font-bold text-secondary mb-2">{{ $title }}</h1>
                @if($subtitle)
                    <p class="text-gray-500 font-inter">{{ $subtitle }}</p>
                @endif
            </div>

            {{ $slot }}
        </div>
    </div>
</div>
