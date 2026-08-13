@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Cashbook</h1>
    <a href="{{ route('cashbook.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">+ New Entry</a>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded shadow p-6">
        <h3 class="text-sm text-gray-500">Today's Cash In</h3>
        <p class="text-2xl font-bold text-green-600">UGX {{ number_format($todayCashIn ?? 0, 2) }}</p>
    </div>
    <div class="bg-white rounded shadow p-6">
        <h3 class="text-sm text-gray-500">Today's Cash Out</h3>
        <p class="text-2xl font-bold text-red-600">UGX {{ number_format($todayCashOut ?? 0, 2) }}</p>
    </div>
    <div class="bg-white rounded shadow p-6">
        <h3 class="text-sm text-gray-500">Cash Balance</h3>
        <p class="text-2xl font-bold {{ ($balance ?? 0) >= 0 ? 'text-blue-600' : 'text-red-600' }}">
            UGX {{ number_format($balance ?? 0, 2) }}
        </p>
    </div>
</div>

@if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
        {{ session('success') }}
    </div>
@endif

<!-- Transactions Table -->
<div class="bg-white rounded shadow overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-3 text-left">Date</th>
                <th class="p-3 text-left">Type</th>
                <th class="p-3 text-left">Category</th>
                <th class="p-3 text-left">Description</th>
                <th class="p-3 text-left">Amount</th>
                <th class="p-3 text-left">Balance</th>
                <th class="p-3 text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $txn)
            <tr class="border-t">
                <td class="p-3">{{ $txn->transaction_date->format('Y-m-d') }}</td>
                <td class="p-3">
                    @if($txn->type == 'cash_in')
                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded">Cash In</span>
                    @else
                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded">Cash Out</span>
                    @endif
                </td>
                <td class="p-3">{{ $txn->category_label }}</td>
                <td class="p-3">{{ $txn->description }}</td>
                <td class="p-3 font-bold {{ $txn->type == 'cash_in' ? 'text-green-600' : 'text-red-600' }}">
                    {{ $txn->amount_with_sign }}
                </td>
                <td class="p-3">{{ number_format($txn->balance, 2) }}</td>
                <td class="p-3">
                    <a href="{{ route('cashbook.show', $txn->id) }}" class="text-blue-500 hover:underline">View</a>
                    <form action="{{ route('cashbook.destroy', $txn->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="p-3 text-center text-gray-500">No cashbook entries found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4">
        {{ $transactions->links() }}
    </div>
</div>
@endsection