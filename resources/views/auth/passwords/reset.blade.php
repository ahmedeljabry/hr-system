<x-auth-split-layout :title="__('messages.reset_password')">
    <form method="POST" action="{{ route('password.update') ?? '#' }}" class="space-y-6 w-full">
        @csrf

        <input type="hidden" name="token" value="{{ $token ?? '' }}">

        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.email') }}</label>
            <x-input type="email" id="email" name="email" value="{{ $email ?? old('email') }}" required autofocus />
            <x-validation-feedback field="email" />
        </div>

        <div>
            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.password') }}</label>
            <x-input type="password" id="password" name="password" required />
            <x-validation-feedback field="password" />
        </div>
        
        <div>
            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.confirm_password') }}</label>
            <x-input type="password" id="password_confirmation" name="password_confirmation" required />
        </div>

        <div class="pt-4">
            <x-button class="w-full">
                {{ __('messages.reset_password') }}
            </x-button>
        </div>
    </form>
</x-auth-split-layout>
