<x-auth-split-layout :title="__('messages.login')" :subtitle="__('messages.login_subtitle')">
    <form method="POST" action="{{ route('login') }}" class="space-y-6 w-full">
        @csrf

        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.email') }}</label>
            <x-input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus />
            <x-validation-feedback field="email" />
        </div>

        <div>
            <div class="flex justify-between items-center mb-2">
                <label for="password" class="block text-sm font-semibold text-gray-700">{{ __('messages.password') }}</label>
                <a href="{{ route('password.request') ?? '#' }}" class="text-xs text-primary hover:text-blue-700">{{ __('messages.forgot_password') }}</a>
            </div>
            <x-input type="password" id="password" name="password" required />
            <x-validation-feedback field="password" />
        </div>

        <div class="flex items-center">
            <input id="remember" name="remember" type="checkbox" 
                class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded transition-all">
            <label for="remember" class="ms-2 block text-sm text-gray-600">
                {{ __('messages.remember_me') }}
            </label>
        </div>

        <div class="pt-4">
            <x-button class="w-full">
                {{ __('messages.login') }}
            </x-button>
        </div>
    </form>

    <div class="mt-8 text-center border-t border-gray-50 pt-6">
        <p class="text-sm text-gray-500">{{ __('messages.no_account') }} 
            <a href="{{ route('register') }}" class="text-primary font-bold hover:underline">{{ __('messages.register_now') }}</a>
        </p>
    </div>
</x-auth-split-layout>
