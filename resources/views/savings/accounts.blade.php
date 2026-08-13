@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Savings Accounts</h1>
    <a href="{{ route('savings.accounts.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">+ Open Account</a>
</div>

@if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
        {{ session('error') }}
    </div>
@endif

<div class="bg-white rounded shadow overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-3 text-left">Account #</th>
                <th class="p-3 text-left">Member</th>
                <th class="p-3 text-left">Product</th>
                <th class="p-3 text-left">Balance</th>
                <th class="p-3 text-left">Status</th>
                <th class="p-3 text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($accounts as $account)
            <tr class="border-t">
                <td class="p-3">{{ $account->account_number }}</td>
                <td class="p-3">{{ $account->member->first_name }} {{ $account->member->last_name }}</td>
                <td class="p-3">{{ $account->savingsProduct->name }}</td>
                <td class="p-3 font-bold">{{ number_format($account->balance, 2) }}</td>
                <td class="p-3">
                    @if($account->is_active)
                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded">Active</span>
                    @else
                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded">Closed</span>
                    @endif
                </td>
                <td class="p-3">
                    <a href="{{ route('savings.show', $account->id) }}" class="text-blue-500 hover:underline">View</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="p-3 text-center text-gray-500">No accounts found. <a href="{{ route('savings.accounts.create') }}" class="text-blue-500 hover:underline">Open your first account</a></td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection