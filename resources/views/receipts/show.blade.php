@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Receipt Details</h1>
    <div class="flex gap-2">
        <a href="{{ route('receipts.pdf', $receipt->id) }}" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
            📄 Download PDF
        </a>
        <a href="javascript:window.print()" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
            🖨️ Print
        </a>
    </div>
</div>

<!-- Receipt -->
<div class="bg-white rounded shadow p-8 max-w-3xl mx-auto" id="receipt">
    <!-- Header -->
    <div class="text-center border-b pb-6 mb-6">
        @if($receipt->company->logo)
            <img src="{{ asset('storage/' . $receipt->company->logo) }}" 
                 alt="{{ $receipt->company->name }}" 
                 class="h-16 mx-auto mb-2">
        @endif
        <h1 class="text-2xl font-bold">{{ $receipt->company->name }}</h1>
        <p class="text-gray-600">{{ $receipt->company->address }}</p>
        <p class="text-gray-600">Phone: {{ $receipt->company->phone }} | Email: {{ $receipt->company->email }}</p>
        <h2 class="text-xl font-bold mt-2 text-blue-600">RECEIPT</h2>
    </div>

    <!-- Receipt Details -->
    <div class="grid grid-cols-2 gap-4 mb-6">
        <div>
            <p class="text-sm text-gray-500">Receipt Number</p>
            <p class="font-bold">{{ $receipt->receipt_number }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">Date</p>
            <p class="font-bold">{{ $receipt->receipt_date->format('d M Y') }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">Member</p>
            <p class="font-bold">{{ $receipt->member->first_name }} {{ $receipt->member->last_name }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">Member ID</p>
            <p class="font-bold">#{{ $receipt->member->id }}</p>
        </div>
    </div>

    <!-- Transaction Details -->
    <table class="w-full mb-6">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-2 text-left">Description</th>
                <th class="p-2 text-left">Type</th>
                <th class="p-2 text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="p-2">{{ $receipt->description }}</td>
                <td class="p-2">{{ $receipt->type_label }}</td>
                <td class="p-2 text-right font-bold">{{ number_format($receipt->amount, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Total -->
    <div class="flex justify-end border-t pt-4">
        <div class="w-64">
            <div class="flex justify-between">
                <span class="font-bold">Total Amount</span>
                <span class="font-bold text-lg">{{ number_format($receipt->amount, 2) }}</span>
            </div>
            <p class="text-sm text-gray-500 mt-2">Payment Method: {{ ucfirst($receipt->payment_method) }}</p>
            @if($receipt->reference)
                <p class="text-sm text-gray-500">Reference: {{ $receipt->reference }}</p>
            @endif
        </div>
    </div>

    <!-- Footer -->
    <div class="border-t mt-6 pt-4 text-center text-gray-500 text-sm">
        <p>Thank you for your business!</p>
        <p>This is a computer-generated receipt. No signature required.</p>
        <p class="mt-2">Generated on: {{ now()->format('d M Y H:i') }}</p>
    </div>
</div>
@endsection

<style>
    @media print {
        .no-print { display: none !important; }
        #receipt { box-shadow: none !important; margin: 0 !important; padding: 20px !important; }
        body { background: white !important; }
    }
</style>