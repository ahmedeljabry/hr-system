<x-auth-layout>
    <x-auth-split-layout :title="__('messages.register')">
        <x-slot name="branding">
            <h2 class="text-4xl font-outfit font-bold mb-4 text-primary">{{ __('messages.join_us') }}</h2>
            <p class="text-lg font-inter opacity-90 max-w-md mx-auto text-gray-300">
                {{ __('messages.register_subtitle') }}
            </p>
        </x-slot>

        <form method="POST" action="{{ route('register') }}" class="space-y-6 w-full mt-4">
            @csrf

            <x-step-indicator :current="1" :total="3" :labels="[__('messages.step_account'), __('messages.step_company'), __('messages.step_confirm')]" />

            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.name') }}</label>
                <x-input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus />
                <x-validation-feedback field="name" />
            </div>

            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.email') }}</label>
                <x-input type="email" id="email" name="email" value="{{ old('email') }}" required />
                <x-validation-feedback field="email" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.password') }}</label>
                    <x-input type="password" id="password" name="password" required />
                    <x-validation-feedback field="password" />
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.confirm_password') }}</label>
                    <x-input type="password" id="password_confirmation" name="password_confirmation" required />
                </div>
            </div>

            <div>
                <label for="company_name" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.company_name') }}</label>
                <x-input type="text" id="company_name" name="company_name" value="{{ old('company_name') }}" required />
                <x-validation-feedback field="company_name" />
            </div>

            <div class="pt-6">
                <x-button class="w-full">
                    {{ __('messages.register') }}
                </x-button>
            </div>
        </form>

        <div class="mt-8 text-center border-t border-gray-50 pt-6">
            <p class="text-sm text-gray-500">{{ __('messages.already_have_account') }} 
                <a href="{{ route('login') }}" class="text-secondary font-bold hover:text-primary transition-colors">{{ __('messages.login_here') }}</a>
            </p>
        </div>
    </x-auth-split-layout>
</x-auth-layout>
