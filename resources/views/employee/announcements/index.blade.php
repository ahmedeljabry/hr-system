@extends('layouts.employee')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ __('Announcements') }}</h1>
    <p class="text-gray-500">{{ __('Stay updated with the latest news and announcements from your company.') }}</p>
</div>

<div class="space-y-6">
    @forelse($announcements as $announcement)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center text-sm text-gray-500">
                <span class="font-medium text-gray-900">{{ __('Published') }}: {{ $announcement->published_at->format('M d, Y - h:i A') }}</span>
                <span>{{ $announcement->published_at->diffForHumans() }}</span>
            </div>
            <div class="p-6 md:p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-4">{{ $announcement->title }}</h2>
                <div class="prose max-w-none text-gray-700">
                    {!! nl2br(e($announcement->body)) !!}
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
            <div class="flex justify-center mb-4">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center text-gray-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                </div>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-1">{{ __('No announcements yet.') }}</h3>
            <p class="text-gray-500">{{ __('Check back later for news and updates from your company.') }}</p>
        </div>
    @endforelse

    @if($announcements->hasPages())
        <div class="mt-8">
            {{ $announcements->links() }}
        </div>
    @endif
</div>
@endsection
