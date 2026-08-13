@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-6">Dashboard</h1>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-500 rounded-full text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Total Members</p>
                    <p class="text-2xl font-bold">{{ $totalMembers ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-500 rounded-full text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Total Savings</p>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($savingsData ?? 0, 2) }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-500 rounded-full text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Total Loans</p>
                    <p class="text-2xl font-bold text-purple-600">{{ number_format($loansData ?? 0, 2) }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-red-500 rounded-full text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Overdue Loans</p>
                    <p class="text-2xl font-bold text-red-600">{{ $overdueLoans ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Savings vs Loans Bar Chart -->
        <div class="bg-white rounded shadow p-6">
            <h2 class="text-lg font-bold mb-4">💰 Savings vs Loans</h2>
            <canvas id="savingsLoansChart" height="200"></canvas>
        </div>

        <!-- Income vs Expenses Pie Chart -->
        <div class="bg-white rounded shadow p-6">
            <h2 class="text-lg font-bold mb-4">📊 Income vs Expenses</h2>
            <canvas id="incomeExpensesChart" height="200"></canvas>
        </div>
    </div>

    <!-- Loan Portfolio Donut Chart & Recent Members -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded shadow p-6">
            <h2 class="text-lg font-bold mb-4">🏦 Loan Portfolio</h2>
            <canvas id="loanPortfolioChart" height="200"></canvas>
        </div>

        <!-- Recent Members -->
        <div class="bg-white rounded shadow">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-bold">📋 Recent Members</h2>
            </div>
            <div class="p-6">
                @if(isset($recentMembers) && $recentMembers->count() > 0)
                    <ul class="divide-y divide-gray-200">
                        @foreach($recentMembers as $member)
                            <li class="py-3 flex items-center justify-between">
                                <div>
                                    <p class="font-medium">{{ $member->first_name }} {{ $member->last_name }}</p>
                                    <p class="text-sm text-gray-500">{{ $member->email ?? 'No email' }}</p>
                                </div>
                                <span class="text-sm text-gray-400">{{ $member->created_at->diffForHumans() }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500 text-center py-4">No members added yet. <a href="{{ route('members.create') }}" class="text-blue-500 hover:underline">Add your first member</a></p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Savings vs Loans Bar Chart
        const ctx1 = document.getElementById('savingsLoansChart').getContext('2d');
        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: ['Savings', 'Loans'],
                datasets: [{
                    label: 'Amount (UGX)',
                    data: [{{ $savingsData ?? 0 }}, {{ $loansData ?? 0 }}],
                    backgroundColor: ['#22c55e', '#8b5cf6'],
                    borderColor: ['#16a34a', '#7c3aed'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'UGX ' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });

        // 2. Income vs Expenses Pie Chart
        const ctx2 = document.getElementById('incomeExpensesChart').getContext('2d');
        new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: ['Income', 'Expenses'],
                datasets: [{
                    data: [{{ $income ?? 0 }}, {{ $expenses ?? 0 }}],
                    backgroundColor: ['#22c55e', '#ef4444'],
                    borderColor: ['#16a34a', '#dc2626'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // 3. Loan Portfolio Donut Chart
        const ctx3 = document.getElementById('loanPortfolioChart').getContext('2d');
        new Chart(ctx3, {
            type: 'doughnut',
            data: {
                labels: ['Active', 'Paid', 'Overdue'],
                datasets: [{
                    data: [{{ $activeLoans ?? 0 }}, {{ $paidLoans ?? 0 }}, {{ $overdueLoans ?? 0 }}],
                    backgroundColor: ['#3b82f6', '#22c55e', '#ef4444'],
                    borderColor: ['#2563eb', '#16a34a', '#dc2626'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    });
</script>
@endsection