@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-6">Profit & Loss Statement</h1>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Income Section -->
    <div class="bg-white rounded shadow p-6">
        <h2 class="text-lg font-bold text-green-600 mb-4">📈 Income</h2>
        <div class="space-y-3">
            <div class="flex justify-between items-center border-b pb-2">
                <span class="text-gray-700">Loan Interest</span>
                <span class="font-bold">{{ number_format($loanInterest ?? 0, 2) }}</span>
            </div>
            <div class="flex justify-between items-center border-b pb-2">
                <span class="text-gray-700">Processing Fees</span>
                <span class="font-bold">{{ number_format($processingFees ?? 0, 2) }}</span>
            </div>
            <div class="flex justify-between items-center border-b pb-2">
                <span class="text-gray-700">Penalties</span>
                <span class="font-bold">{{ number_format($penalties ?? 0, 2) }}</span>
            </div>
            <div class="flex justify-between items-center pt-2 mt-2 border-t-2 border-gray-300">
                <span class="font-bold text-lg">Total Income</span>
                <span class="font-bold text-lg text-green-600">{{ number_format($totalIncome ?? 0, 2) }}</span>
            </div>
        </div>
    </div>

    <!-- Expenses Section -->
    <div class="bg-white rounded shadow p-6">
        <h2 class="text-lg font-bold text-red-600 mb-4">📉 Expenses</h2>
        <div class="space-y-3">
            <div class="flex justify-between items-center border-b pb-2">
                <span class="text-gray-700">Savings Interest</span>
                <span class="font-bold">{{ number_format($savingsInterest ?? 0, 2) }}</span>
            </div>
            <div class="flex justify-between items-center border-b pb-2">
                <span class="text-gray-700">Member Withdrawals</span>
                <span class="font-bold">{{ number_format($withdrawals ?? 0, 2) }}</span>
            </div>
            <div class="flex justify-between items-center pt-2 mt-2 border-t-2 border-gray-300">
                <span class="font-bold text-lg">Total Expenses</span>
                <span class="font-bold text-lg text-red-600">{{ number_format($totalExpenses ?? 0, 2) }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Net Profit/Loss -->
<div class="bg-white rounded shadow p-6 mt-6 text-center">
    <h2 class="text-lg font-bold text-gray-700 mb-2">💰 Net Profit / Loss</h2>
    <p class="text-4xl font-bold {{ ($netProfit ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
        {{ number_format($netProfit ?? 0, 2) }}
    </p>
    <p class="text-sm mt-2">
        @if(($netProfit ?? 0) >= 0)
            <span class="text-green-600">✅ Profit</span>
        @else
            <span class="text-red-600">❌ Loss</span>
        @endif
    </p>
</div>

<!-- Summary -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
    <div class="bg-green-50 rounded shadow p-4 text-center border border-green-200">
        <p class="text-sm text-gray-600">Total Income</p>
        <p class="text-xl font-bold text-green-600">{{ number_format($totalIncome ?? 0, 2) }}</p>
    </div>
    <div class="bg-red-50 rounded shadow p-4 text-center border border-red-200">
        <p class="text-sm text-gray-600">Total Expenses</p>
        <p class="text-xl font-bold text-red-600">{{ number_format($totalExpenses ?? 0, 2) }}</p>
    </div>
    <div class="bg-blue-50 rounded shadow p-4 text-center border border-blue-200">
        <p class="text-sm text-gray-600">Net Profit/Loss</p>
        <p class="text-xl font-bold {{ ($netProfit ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
            {{ number_format($netProfit ?? 0, 2) }}
        </p>
    </div>
</div>

<!-- Export Buttons -->
<div class="mt-6 flex gap-2">
    <a href="{{ route('reports.profit-loss.pdf') }}" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">📄 Export PDF</a>
</div>
@endsection