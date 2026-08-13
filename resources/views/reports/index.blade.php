@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-6">Reports Dashboard</h1>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded shadow p-6">
        <h3 class="text-sm text-gray-500">Total Members</h3>
        <p class="text-2xl font-bold">{{ $totalMembers ?? 0 }}</p>
    </div>
    <div class="bg-white rounded shadow p-6">
        <h3 class="text-sm text-gray-500">Total Savings</h3>
        <p class="text-2xl font-bold text-green-600">{{ number_format($totalSavings ?? 0, 2) }}</p>
    </div>
    <div class="bg-white rounded shadow p-6">
        <h3 class="text-sm text-gray-500">Total Loans</h3>
        <p class="text-2xl font-bold text-blue-600">{{ number_format($totalLoans ?? 0, 2) }}</p>
    </div>
    <div class="bg-white rounded shadow p-6">
        <h3 class="text-sm text-gray-500">Total Repayments</h3>
        <p class="text-2xl font-bold text-green-600">{{ number_format($totalRepayments ?? 0, 2) }}</p>
    </div>
    <div class="bg-white rounded shadow p-6">
        <h3 class="text-sm text-gray-500">Overdue Loans</h3>
        <p class="text-2xl font-bold text-red-600">{{ $overdueLoans ?? 0 }}</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <a href="{{ route('reports.members') }}" class="bg-white rounded shadow p-6 hover:shadow-lg transition">
        <h3 class="text-lg font-bold">👥 Members Report</h3>
        <p class="text-gray-500">View and export member list</p>
    </a>
    <a href="{{ route('reports.savings') }}" class="bg-white rounded shadow p-6 hover:shadow-lg transition">
        <h3 class="text-lg font-bold">💰 Savings Report</h3>
        <p class="text-gray-500">View and export savings data</p>
    </a>
    <a href="{{ route('reports.loans') }}" class="bg-white rounded shadow p-6 hover:shadow-lg transition">
        <h3 class="text-lg font-bold">🏦 Loans Report</h3>
        <p class="text-gray-500">View and export loan portfolio</p>
    </a>
    <a href="{{ route('reports.profit-loss') }}" class="bg-white rounded shadow p-6 hover:shadow-lg transition">
        <h3 class="text-lg font-bold">📊 Profit & Loss</h3>
        <p class="text-gray-500">View income, expenses, and net profit</p>
    </a>
</div>
@endsection