@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Loan Details</h1>
    <a href="{{ route('loans.applications') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">← Back</a>
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

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded shadow p-6">
        <h3 class="text-sm text-gray-500">Member</h3>
        <p class="font-bold">{{ $loan->member->first_name }} {{ $loan->member->last_name }}</p>
    </div>
    <div class="bg-white rounded shadow p-6">
        <h3 class="text-sm text-gray-500">Product</h3>
        <p class="font-bold">{{ $loan->loanProduct->name }}</p>
    </div>
    <div class="bg-white rounded shadow p-6">
        <h3 class="text-sm text-gray-500">Status</h3>
        <p class="font-bold capitalize">{{ $loan->status }}</p>
    </div>
    <div class="bg-white rounded shadow p-6">
        <h3 class="text-sm text-gray-500">Amount</h3>
        <p class="font-bold">{{ number_format($loan->amount, 2) }}</p>
    </div>
    <div class="bg-white rounded shadow p-6">
        <h3 class="text-sm text-gray-500">Interest Rate</h3>
        <p class="font-bold">{{ $loan->interest_rate }}%</p>
    </div>
    <div class="bg-white rounded shadow p-6">
        <h3 class="text-sm text-gray-500">Total Repayable</h3>
        <p class="font-bold text-green-600">{{ number_format($loan->total_repayable, 2) }}</p>
    </div>
    <div class="bg-white rounded shadow p-6">
        <h3 class="text-sm text-gray-500">Balance</h3>
        <p class="font-bold text-red-600">{{ number_format($loan->balance, 2) }}</p>
    </div>
    <div class="bg-white rounded shadow p-6">
        <h3 class="text-sm text-gray-500">Term</h3>
        <p class="font-bold">{{ $loan->term_months }} months</p>
    </div>
</div>

@if($loan->status == 'approved')
    <form action="{{ route('loans.disburse', $loan->id) }}" method="POST" class="mb-6">
        @csrf
        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Disburse Loan</button>
    </form>
@endif

<!-- Repayments -->
<div class="bg-white rounded shadow">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-lg font-bold">Repayment Schedule</h2>
    </div>
    <div class="p-6">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-3 text-left">#</th>
                    <th class="p-3 text-left">Due Date</th>
                    <th class="p-3 text-left">Amount Due</th>
                    <th class="p-3 text-left">Amount Paid</th>
                    <th class="p-3 text-left">Balance</th>
                    <th class="p-3 text-left">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($loan->repayments as $repayment)
                <tr class="border-t">
                    <td class="p-3">{{ $repayment->installment_number }}</td>
                    <td class="p-3">{{ $repayment->due_date->format('Y-m-d') }}</td>
                    <td class="p-3">{{ number_format($repayment->amount_due, 2) }}</td>
                    <td class="p-3">{{ number_format($repayment->amount_paid, 2) }}</td>
                    <td class="p-3">{{ number_format($repayment->balance, 2) }}</td>
                    <td class="p-3">
                        @if($repayment->status == 'paid')
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded">Paid</span>
                        @elseif($repayment->due_date < now())
                            <span class="bg-red-100 text-red-800 px-2 py-1 rounded">Overdue</span>
                        @else
                            <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded">Pending</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Make Repayment -->
@if($loan->status == 'disbursed' || $loan->status == 'active')
<div class="bg-white rounded shadow p-6 mt-6">
    <h2 class="text-lg font-bold mb-4">Make Repayment</h2>
    <form action="{{ route('loans.repay', $loan->id) }}" method="POST" class="flex gap-2">
        @csrf
        <input type="number" name="amount" class="border rounded px-3 py-2 w-48" placeholder="Amount" step="0.01" required>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Record Repayment</button>
    </form>
</div>
@endif
@endsection