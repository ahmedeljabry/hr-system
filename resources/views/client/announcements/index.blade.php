@extends('layouts.app')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">{{ __('Announcements') }}</h1>
        <p class="text-gray-600 text-sm mt-1">{{ __('Manage company-wide announcements for your employees.') }}</p>
    </div>
    <a href="{{ route('client.announcements.create') }}" class="bg-secondary text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-secondary/90 transition-colors">
        + {{ __('Create Announcement') }}
    </a>
</div>

@if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center">
        <svg class="w-5 h-5 mr-2 {{ app()->getLocale() == 'ar' ? 'ml-2 mr-0' : '' }}" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100 text-gray-500 text-sm uppercase tracking-wider">
                <th class="px-6 py-4 font-medium">{{ __('Title') }}</th>
                <th class="px-6 py-4 font-medium">{{ __('Published') }}</th>
                <th class="px-6 py-4 font-medium">{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($announcements as $announcement)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $announcement->title }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $announcement->published_at->format('Y-m-d H:i') }}</td>
                    <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                        <div class="flex items-center space-x-3 {{ app()->getLocale() == 'ar' ? 'space-x-reverse' : '' }}">
                            <a href="{{ route('client.announcements.edit', $announcement) }}" class="text-secondary hover:text-secondary/80 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <form action="{{ route('client.announcements.destroy', $announcement) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this announcement?') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                            <p class="text-sm">{{ __('No announcements yet.') }}</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    @if($announcements->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $announcements->links() }}
        </div>
    @endif
</div>
@endsection
