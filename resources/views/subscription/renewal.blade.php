@extends('layouts.app')

@section('content')
<div class="flex flex-col items-center justify-center py-16">
    <div class="w-full max-w-lg bg-white rounded-3xl shadow-2xl border border-red-50 overflow-hidden transform transition-all hover:scale-[1.01]">
        <div class="bg-red-500 p-8 text-center text-white relative">
            <svg class="w-20 h-20 mx-auto mb-4 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <h1 class="text-3xl font-extrabold mb-2">{{ __('messages.attention_subscription_inactive') }}</h1>
            <p class="text-red-100 opacity-90 tracking-widest text-sm font-bold uppercase">{{ __('messages.subscription_access_restricted') }}</p>
        </div>
        
        <div class="p-10 text-center">
            <p class="text-gray-700 text-lg leading-relaxed mb-6 font-bold">
                {!! __('messages.subscription_inactive_notice', ['company' => '<span class="text-red-600">(' . (Auth::user()->client?->name ?? '') . ')</span>']) !!}
            </p>
            
            <div class="bg-gray-50 border border-gray-100 rounded-2xl p-6 mb-8 text-start">
                <h3 class="text-gray-400 uppercase text-xs font-bold tracking-widest mb-4">{{ __('messages.subscription_details') }}</h3>
                <div class="flex justify-between items-center mb-3">
                    <span class="text-sm font-semibold text-gray-500">{{ __('messages.status') }}</span>
                    <span class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-xs font-bold border border-red-200">
                        {{ __('messages.subscription_' . (Auth::user()->client?->isSuspended() ? 'suspended' : 'not_active')) }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm font-semibold text-gray-500">{{ __('messages.end_date') }}</span>
                    <span class="text-gray-800 font-bold">{{ Auth::user()->client?->subscription_end ? Auth::user()->client->subscription_end->format('Y-m-d') : '---' }}</span>
                </div>
            </div>

            <p class="text-gray-500 text-sm mb-10 leading-loose">
                {{ __('messages.subscription_renewal_instructions') }}
            </p>
            
            <div class="flex gap-4">
                <a href="mailto:support@hr-system.com" class="flex-1 bg-gray-900 text-white flex items-center justify-center font-bold py-4 rounded-2xl hover:bg-black transition text-sm">{{ __('messages.contact_us') }}</a>
                <form action="{{ route('logout') }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full bg-white text-gray-700 border border-gray-200 font-bold py-4 rounded-2xl hover:bg-gray-50 transition text-sm">{{ __('messages.logout') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
