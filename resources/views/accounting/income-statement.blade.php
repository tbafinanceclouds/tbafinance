@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-6">Income Statement</h1>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Income -->
    <div class="bg-white rounded shadow p-6">
        <h2 class="text-lg font-bold text-green-600 mb-4">Income</h2>
        @foreach($income as $item)
            <div class="flex justify-between border-b py-2">
                <span>{{ $item->account_name }}</span>
                <span>{{ number_format($item->balance, 2) }}</span>
            </div>
        @endforeach
        <div class="flex justify-between font-bold border-t-2 pt-2 mt-2">
            <span>Total Income</span>
            <span>{{ number_format($income->sum('balance'), 2) }}</span>
        </div>
    </div>

    <!-- Expenses -->
    <div class="bg-white rounded shadow p-6">
        <h2 class="text-lg font-bold text-red-600 mb-4">Expenses</h2>
        @foreach($expenses as $expense)
            <div class="flex justify-between border-b py-2">
                <span>{{ $expense->account_name }}</span>
                <span>{{ number_format($expense->balance, 2) }}</span>
            </div>
        @endforeach
        <div class="flex justify-between font-bold border-t-2 pt-2 mt-2">
            <span>Total Expenses</span>
            <span>{{ number_format($expenses->sum('balance'), 2) }}</span>
        </div>
    </div>
</div>

<!-- Net Profit / Loss -->
<div class="bg-white rounded shadow p-6 mt-6 text-center">
    <h2 class="text-lg font-bold">Net Profit / Loss</h2>
    <p class="text-3xl font-bold {{ ($income->sum('balance') - $expenses->sum('balance')) >= 0 ? 'text-green-600' : 'text-red-600' }}">
        {{ number_format($income->sum('balance') - $expenses->sum('balance'), 2) }}
    </p>
    <div class="mt-6 text-center">
        <form action="{{ route('accounting.closing-entry') }}" method="POST">
            @csrf
            <button type="submit" class="bg-blue-500 text-white px-6 py-3 rounded hover:bg-blue-600">
                🔒 Create Closing Entry
            </button>
        </form>
        <p class="text-sm text-gray-500 mt-2">This will close all income and expense accounts for the period.</p>
    </div>
</div>
@endsection