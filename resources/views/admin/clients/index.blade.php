@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">{{ __('Clients') }}</h1>
        <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800 font-medium">
            ← {{ __('Back to Dashboard') }}
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-100 text-green-600 px-6 py-4 rounded-2xl shadow-sm flex items-center">
            <svg class="w-6 h-6 me-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-start">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-5 text-start">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'dir' => ($sort === 'name' && $dir === 'asc') ? 'desc' : 'asc']) }}" class="flex items-center text-xs font-extrabold text-gray-500 uppercase tracking-widest hover:text-gray-700">
                                {{ __('Company Name') }}
                                @if($sort === 'name')
                                    <span class="ml-1">{{ $dir === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-5 text-start">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'status', 'dir' => ($sort === 'status' && $dir === 'asc') ? 'desc' : 'asc']) }}" class="flex items-center text-xs font-extrabold text-gray-500 uppercase tracking-widest hover:text-gray-700">
                                {{ __('Subscription Status') }}
                                @if($sort === 'status')
                                    <span class="ml-1">{{ $dir === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-5 text-start">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'subscription_end', 'dir' => ($sort === 'subscription_end' && $dir === 'asc') ? 'desc' : 'asc']) }}" class="flex items-center text-xs font-extrabold text-gray-500 uppercase tracking-widest hover:text-gray-700">
                                {{ __('Subscription End') }}
                                @if($sort === 'subscription_end')
                                    <span class="ml-1">{{ $dir === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-5 text-start text-xs font-extrabold text-gray-500 uppercase tracking-widest">
                            {{ __('Employees') }}
                        </th>
                        <th class="px-6 py-5 text-end text-xs font-extrabold text-gray-500 uppercase tracking-widest">
                            {{ __('Actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($clients as $client)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-6 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="bg-blue-50 text-blue-600 w-10 h-10 rounded-xl flex items-center justify-center font-bold me-3">
                                    {{ substr($client->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-gray-900">{{ $client->name }}</div>
                                    <div class="text-xs text-gray-400">ID: #{{ $client->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-6 whitespace-nowrap">
                            <form action="{{ route('admin.clients.status', $client->id) }}" method="POST" class="inline">
                                @csrf @method('PATCH')
                                <select name="status" onchange="this.form.submit()" class="text-sm border border-gray-200 rounded-lg px-2 py-1 focus:ring-2 focus:ring-blue-400">
                                    <option value="active" {{ $client->status === 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                                    <option value="suspended" {{ $client->status === 'suspended' ? 'selected' : '' }}>{{ __('Suspended') }}</option>
                                    <option value="expired" {{ $client->status === 'expired' ? 'selected' : '' }}>{{ __('Expired') }}</option>
                                </select>
                            </form>
                        </td>
                        <td class="px-6 py-6 whitespace-nowrap text-sm text-gray-500">
                            {{ $client->subscription_end ? $client->subscription_end->format('Y-m-d') : '—' }}
                        </td>
                        <td class="px-6 py-6 whitespace-nowrap text-sm text-gray-900 font-bold">
                            {{ $client->employees_count }}
                        </td>
                        <td class="px-6 py-6 whitespace-nowrap text-end text-sm font-medium">
                            <a href="{{ route('admin.clients.show', $client->id) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                {{ __('View Employees') }}
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($clients->isEmpty())
            <div class="text-center py-12">
                <div class="text-gray-400 mb-4">
                    <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('No clients found.') }}</h3>
                <p class="text-gray-500">{{ __('Clients will appear here once they register.') }}</p>
            </div>
        @endif

        @if($clients->hasPages())
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                {{ $clients->links() }}
            </div>
        @endif
    </div>
</div>
@endsection