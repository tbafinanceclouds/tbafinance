@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Cashbook Entry Details</h1>
    <a href="{{ route('cashbook.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">← Back</a>
</div>

@if($transaction)
<div class="bg-white rounded shadow p-6 max-w-2xl">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <p class="text-sm text-gray-500">Transaction Date</p>
            <p class="font-bold">{{ $transaction->transaction_date->format('Y-m-d') }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">Type</p>
            <p class="font-bold">
                @if($transaction->type == 'cash_in')
                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded">Cash In</span>
                @else
                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded">Cash Out</span>
                @endif
            </p>
        </div>
        <div>
            <p class="text-sm text-gray-500">Category</p>
            <p class="font-bold">{{ $transaction->category_label }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">Payment Method</p>
            <p class="font-bold capitalize">{{ $transaction->payment_method }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">Amount</p>
            <p class="font-bold {{ $transaction->type == 'cash_in' ? 'text-green-600' : 'text-red-600' }}">
                {{ $transaction->amount_with_sign }}
            </p>
        </div>
        <div>
            <p class="text-sm text-gray-500">Balance After</p>
            <p class="font-bold">{{ number_format($transaction->balance, 2) }}</p>
        </div>
        <div class="col-span-2">
            <p class="text-sm text-gray-500">Reference</p>
            <p class="font-bold">{{ $transaction->reference ?? 'N/A' }}</p>
        </div>
        <div class="col-span-2">
            <p class="text-sm text-gray-500">Description</p>
            <p class="font-bold">{{ $transaction->description }}</p>
        </div>
        <div class="col-span-2">
            <p class="text-sm text-gray-500">Created By</p>
            <p class="font-bold">{{ $transaction->creator->name ?? 'N/A' }}</p>
        </div>
    </div>
</div>
@else
<div class="bg-white rounded shadow p-6 text-center">
    <p class="text-gray-500">Transaction not found.</p>
    <a href="{{ route('cashbook.index') }}" class="text-blue-500 hover:underline">← Back to Cashbook</a>
</div>
@endif
@endsection