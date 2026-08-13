@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Account Details</h1>
    <a href="{{ route('savings.accounts') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">← Back</a>
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

<!-- Account Summary -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <div class="bg-white rounded shadow p-6">
        <h3 class="text-sm text-gray-500">Account Number</h3>
        <p class="text-xl font-bold">{{ $account->account_number }}</p>
    </div>
    <div class="bg-white rounded shadow p-6">
        <h3 class="text-sm text-gray-500">Balance</h3>
        <p class="text-2xl font-bold text-green-600">{{ number_format($account->balance, 2) }}</p>
    </div>
</div>

<!-- Member Info -->
<div class="bg-white rounded shadow p-6 mb-6">
    <h2 class="text-lg font-bold mb-4">Member Information</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <p><strong>Name:</strong> {{ $account->member->first_name }} {{ $account->member->last_name }}</p>
        <p><strong>Email:</strong> {{ $account->member->email ?? 'N/A' }}</p>
        <p><strong>Phone:</strong> {{ $account->member->phone ?? 'N/A' }}</p>
        <p><strong>Product:</strong> {{ $account->savingsProduct->name }}</p>
        <p><strong>Type:</strong> {{ ucfirst($account->savingsProduct->type) }}</p>
        <p><strong>Interest Rate:</strong> {{ $account->savingsProduct->interest_rate }}%</p>
    </div>
</div>

<!-- New Transaction -->
<div class="bg-white rounded shadow p-6 mb-6">
    <h2 class="text-lg font-bold mb-4">New Transaction</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Deposit Form -->
        <form action="{{ route('savings.deposit', $account->id) }}" method="POST" class="flex gap-2">
            @csrf
            <input type="number" name="amount" class="border rounded px-3 py-2 w-full" placeholder="Amount" step="0.01" required>
            <input type="text" name="description" class="border rounded px-3 py-2 w-full" placeholder="Description">
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Deposit</button>
        </form>
        <!-- Withdraw Form -->
        <form action="{{ route('savings.withdraw', $account->id) }}" method="POST" class="flex gap-2">
            @csrf
            <input type="number" name="amount" class="border rounded px-3 py-2 w-full" placeholder="Amount" step="0.01" required>
            <input type="text" name="description" class="border rounded px-3 py-2 w-full" placeholder="Description">
            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Withdraw</button>
        </form>
    </div>
</div>

<!-- Transaction History -->
<div class="bg-white rounded shadow">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-lg font-bold">Transaction History</h2>
    </div>
    <div class="p-6">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-3 text-left">Date</th>
                    <th class="p-3 text-left">Type</th>
                    <th class="p-3 text-left">Amount</th>
                    <th class="p-3 text-left">Balance</th>
                    <th class="p-3 text-left">Description</th>
                    <th class="p-3 text-left">Receipt</th>
                </tr>
            </thead>
            <tbody>
                @forelse($account->transactions as $transaction)
                <tr class="border-t">
                    <td class="p-3">{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                    <td class="p-3 capitalize">
                        @if($transaction->type == 'deposit')
                            <span class="text-green-600">Deposit</span>
                        @elseif($transaction->type == 'withdrawal')
                            <span class="text-red-600">Withdrawal</span>
                        @else
                            {{ $transaction->type }}
                        @endif
                    </td>
                    <td class="p-3 font-bold">
                        @if($transaction->type == 'deposit')
                            <span class="text-green-600">+{{ number_format($transaction->amount, 2) }}</span>
                        @else
                            <span class="text-red-600">-{{ number_format($transaction->amount, 2) }}</span>
                        @endif
                    </td>
                    <td class="p-3">{{ number_format($transaction->balance_after, 2) }}</td>
                    <td class="p-3">{{ $transaction->description ?? 'N/A' }}</td>
                    <td class="p-3">
                        @php
                            $receipt = \App\Models\Receipt::where('member_id', $account->member_id)
                                ->where('amount', $transaction->amount)
                                ->where('receipt_date', $transaction->created_at->format('Y-m-d'))
                                ->first();
                        @endphp
                        @if($receipt)
                            <a href="{{ route('receipts.show', $receipt->id) }}" class="text-blue-500 hover:underline text-sm">
                                📄 Receipt
                            </a>
                        @else
                            <span class="text-gray-400 text-sm">N/A</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-3 text-center text-gray-500">No transactions yet</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Receipt Link for All Transactions -->
<div class="mt-4 text-right">
    <a href="{{ route('receipts.index') }}" class="text-blue-500 hover:underline">
        📄 View All Receipts
    </a>
</div>
@endsection