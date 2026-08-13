@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 max-w-2xl">
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        ✅ Payment Successful!
    </div>

    <div class="bg-white rounded shadow p-6">
        <h2 class="text-xl font-bold mb-4">Transaction Details</h2>
        <div class="space-y-2">
            <p><strong>Transaction ID:</strong> {{ $transaction['id'] ?? 'N/A' }}</p>
            <p><strong>Amount:</strong> {{ $transaction['amount'] ?? 'N/A' }}</p>
            <p><strong>Currency:</strong> {{ $transaction['currency'] ?? 'UGX' }}</p>
            <p><strong>Status:</strong> {{ $transaction['status'] ?? 'Completed' }}</p>
        </div>

        <a href="{{ route('payments.index') }}" class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Back to Payments
        </a>
    </div>
</div>
@endsection
