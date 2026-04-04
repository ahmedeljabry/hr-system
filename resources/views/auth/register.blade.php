@extends('layouts.app')

@section('content')
<div class="flex flex-col items-center justify-center py-12">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden transform transition-all hover:shadow-2xl">
        <div class="p-8">
            <div class="text-center mb-10">
                <h1 class="text-3xl font-extrabold text-blue-600 mb-2">{{ __('messages.register') }}</h1>
                <p class="text-gray-400 text-sm italic">انضم إلينا وابدأ إدارة مواردك البشرية بكفاءة</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.name') }}</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.email') }}</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.password') }}</label>
                        <input type="password" id="password" name="password" required
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none @error('password') border-red-500 @enderror">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.confirm_password') }}</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all">
                    </div>
                </div>
                @error('password')
                    <p class="text-xs text-red-500 mt-[-1rem]">{{ $message }}</p>
                @enderror

                <div>
                    <label for="company_name" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.company_name') }}</label>
                    <input type="text" id="company_name" name="company_name" value="{{ old('company_name') }}" required
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none @error('company_name') border-red-500 @enderror">
                    @error('company_name')
                        <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-blue-600 text-white font-bold py-4 rounded-xl hover:bg-blue-700 transform transition-all active:scale-95 shadow-lg shadow-blue-200">
                        {{ __('messages.register') }}
                    </button>
                </div>
            </form>

            <div class="mt-8 text-center border-t border-gray-50 pt-6">
                <p class="text-sm text-gray-500">لديك حساب بالفعل؟ 
                    <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline">سجل دخولك هنا</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
