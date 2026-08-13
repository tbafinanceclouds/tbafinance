@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Loans Report</h1>
    <a href="{{ route('reports.loans.pdf') }}" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">📄 PDF</a>
</div>

<div class="bg-white rounded shadow overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-3 text-left">Member</th>
                <th class="p-3 text-left">Product</th>
                <th class="p-3 text-left">Amount</th>
                <th class="p-3 text-left">Balance</th>
                <th class="p-3 text-left">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($loans as $loan)
            <tr class="border-t">
                <td class="p-3">{{ $loan->member->first_name }} {{ $loan->member->last_name }}</td>
                <td class="p-3">{{ $loan->loanProduct->name }}</td>
                <td class="p-3">{{ number_format($loan->amount, 2) }}</td>
                <td class="p-3 font-bold">{{ number_format($loan->balance, 2) }}</td>
                <td class="p-3 capitalize">{{ $loan->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection