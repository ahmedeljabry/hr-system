@extends('layouts.app')

@section('content')
<div class="flex flex-col items-center justify-center py-12">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden transform transition-all hover:shadow-2xl">
        <div class="p-8">
            <div class="text-center mb-10">
                <h1 class="text-3xl font-extrabold text-blue-600 mb-2">{{ __('messages.login') }}</h1>
                <p class="text-gray-400 text-sm italic">مرحبًا بعودتك.. أدخل بياناتك للمتابعة</p>
            </div>

            @if ($errors->has('email'))
                <div class="mb-6 bg-red-50 border border-red-100 text-red-600 px-4 py-3 rounded-xl text-sm flex items-center">
                    <svg class="w-5 h-5 me-2 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                    <span>{{ $errors->first('email') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.email') }}</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none">
                </div>

                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label for="password" class="block text-sm font-semibold text-gray-700">{{ __('messages.password') }}</label>
                        <a href="#" class="text-xs text-blue-500 hover:text-blue-700">نسيت كلمة المرور؟</a>
                    </div>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none">
                </div>

                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" 
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded transition-all">
                    <label for="remember" class="ms-2 block text-sm text-gray-600">
                        {{ __('messages.remember_me') }}
                    </label>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-blue-600 text-white font-bold py-4 rounded-xl hover:bg-blue-700 transform transition-all active:scale-95 shadow-lg shadow-blue-200">
                        {{ __('messages.login') }}
                    </button>
                </div>
            </form>

            <div class="mt-8 text-center border-t border-gray-50 pt-6">
                <p class="text-sm text-gray-500">ليس لديك حساب؟ 
                    <a href="{{ route('register') }}" class="text-blue-600 font-bold hover:underline">سجل شركتك الآن</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
