@extends('layouts.company')

@section('title', 'Dashboard')

@section('content')
<div class="container mx-auto">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <p class="text-sm text-gray-500">Total Members</p>
            <p class="text-2xl font-bold text-blue-600">{{ $stats['members'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <p class="text-sm text-gray-500">Total Savings</p>
            <p class="text-2xl font-bold text-green-600">UGX {{ number_format($stats['savings'], 2) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
            <p class="text-sm text-gray-500">Total Loans</p>
            <p class="text-2xl font-bold text-purple-600">UGX {{ number_format($stats['loans'], 2) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
            <p class="text-sm text-gray-500">Active Loans</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $stats['active_loans'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
            <p class="text-sm text-gray-500">Overdue Loans</p>
            <p class="text-2xl font-bold text-red-600">{{ $stats['overdue_loans'] }}</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <a href="{{ route('company.members') }}" 
           class="bg-blue-500 hover:bg-blue-600 text-white p-4 rounded-lg text-center transition">
            👥 Manage Members
        </a>
        <a href="{{ route('company.savings') }}" 
           class="bg-green-500 hover:bg-green-600 text-white p-4 rounded-lg text-center transition">
            💰 Manage Savings
        </a>
        <a href="{{ route('company.loans') }}" 
           class="bg-purple-500 hover:bg-purple-600 text-white p-4 rounded-lg text-center transition">
            💳 Manage Loans
        </a>
        <a href="{{ route('company.reports') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white p-4 rounded-lg text-center transition">
            📊 Generate Reports
        </a>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Recent Members -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-bold text-lg mb-4">👥 Recent Members</h3>
            @if($recentMembers->count() > 0)
                <ul class="divide-y divide-gray-200">
                    @foreach($recentMembers as $member)
                        <li class="py-2 flex justify-between">
                            <span>{{ $member->first_name }} {{ $member->last_name }}</span>
                            <span class="text-sm text-gray-500">{{ $member->created_at->diffForHumans() }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-500 text-center py-4">No members yet</p>
            @endif
        </div>

        <!-- Recent Loans -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-bold text-lg mb-4">💳 Recent Loans</h3>
            @if($recentLoans->count() > 0)
                <ul class="divide-y divide-gray-200">
                    @foreach($recentLoans as $loan)
                        <li class="py-2 flex justify-between">
                            <span>#{{ $loan->id }} - UGX {{ number_format($loan->amount, 2) }}</span>
                            <span class="text-sm text-gray-500">{{ $loan->status }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-500 text-center py-4">No loans yet</p>
            @endif
        </div>
    </div>
</div>
@endsection