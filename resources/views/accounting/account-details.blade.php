@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Account Details</h1>
    <a href="{{ route('accounting.chart') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">← Back</a>
</div>

<!-- Account Summary -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded shadow p-6">
        <h3 class="text-sm text-gray-500">Account Name</h3>
        <p class="text-xl font-bold">{{ $account->account_name }}</p>
    </div>
    <div class="bg-white rounded shadow p-6">
        <h3 class="text-sm text-gray-500">Account Code</h3>
        <p class="text-xl font-bold">{{ $account->account_code }}</p>
    </div>
    <div class="bg-white rounded shadow p-6">
        <h3 class="text-sm text-gray-500">Account Type</h3>
        <p class="text-xl font-bold capitalize">{{ $account->account_type }}</p>
    </div>
</div>

<!-- Transactions Table -->
<div class="bg-white rounded shadow overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-3 text-left">Date</th>
                <th class="p-3 text-left">Reference</th>
                <th class="p-3 text-left">Description</th>
                <th class="p-3 text-left">Debit</th>
                <th class="p-3 text-left">Credit</th>
                <th class="p-3 text-left">Balance</th>
            </tr>
        </thead>
        <tbody>
            @php $runningBalance = 0; @endphp
            @foreach($transactions as $txn)
            @php
                $runningBalance += $txn->debit - $txn->credit;
            @endphp
            <tr class="border-t">
                <td class="p-3">{{ $txn->journalEntry->entry_date->format('Y-m-d') }}</td>
                <td class="p-3">{{ $txn->journalEntry->reference }}</td>
                <td class="p-3">{{ $txn->description ?? $txn->journalEntry->description }}</td>
                <td class="p-3">{{ number_format($txn->debit, 2) }}</td>
                <td class="p-3">{{ number_format($txn->credit, 2) }}</td>
                <td class="p-3">{{ number_format($runningBalance, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="bg-gray-200 font-bold">
                <td colspan="5" class="p-3 text-right">Balance</td>
                <td class="p-3">{{ number_format($account->balance, 2) }}</td>
            </tr>
        </tfoot>
    </table>
</div>
@endsection