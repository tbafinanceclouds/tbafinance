@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">All Receipts</h1>
</div>

<div class="bg-white rounded shadow overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-3 text-left">#</th>
                <th class="p-3 text-left">Receipt Number</th>
                <th class="p-3 text-left">Member</th>
                <th class="p-3 text-left">Type</th>
                <th class="p-3 text-left">Amount</th>
                <th class="p-3 text-left">Date</th>
                <th class="p-3 text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($receipts as $receipt)
            <tr class="border-t">
                <td class="p-3">{{ $receipt->id }}</td>
                <td class="p-3">{{ $receipt->receipt_number }}</td>
                <td class="p-3">{{ $receipt->member->first_name }} {{ $receipt->member->last_name }}</td>
                <td class="p-3">{{ $receipt->type_label }}</td>
                <td class="p-3 font-bold">{{ number_format($receipt->amount, 2) }}</td>
                <td class="p-3">{{ $receipt->receipt_date->format('Y-m-d') }}</td>
                <td class="p-3">
                    <a href="{{ route('receipts.show', $receipt->id) }}" class="text-blue-500 hover:underline">View</a>
                    <a href="{{ route('receipts.pdf', $receipt->id) }}" class="text-red-500 hover:underline ml-2">PDF</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="p-4">
        {{ $receipts->links() }}
    </div>
</div>
@endsection