@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-6">Buy Shares</h1>

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

<div class="bg-white rounded shadow p-6 max-w-2xl">
    <form action="{{ route('shares.purchase') }}" method="POST" id="buySharesForm">
        @csrf

        <div class="grid grid-cols-1 gap-4">
            <!-- Member Dropdown -->
            <div>
                <label class="block text-gray-700 font-medium mb-2">Member</label>
                <select name="member_id" id="member_id" class="w-full border rounded px-3 py-2" required>
                    <option value="">Select Member</option>
                    @foreach($members as $member)
                        <option value="{{ $member->id }}">{{ $member->first_name }} {{ $member->last_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Share Product Dropdown -->
            <div>
                <label class="block text-gray-700 font-medium mb-2">Share Product</label>
                <select name="share_product_id" id="share_product_id" class="w-full border rounded px-3 py-2" required>
                    <option value="">Select Product</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }} (UGX {{ number_format($product->price_per_share, 2) }})</option>
                    @endforeach
                </select>
            </div>

            <!-- Number of Shares -->
            <div>
                <label class="block text-gray-700 font-medium mb-2">Number of Shares</label>
                <input type="number" name="shares" id="shares" class="w-full border rounded px-3 py-2" min="1" required>
                <p class="text-sm text-gray-500 mt-1">Min: 1 share</p>
            </div>
        </div>

        <!-- Buttons -->
        <div class="mt-6 flex gap-2">
            <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600 text-lg font-medium">
                🔹 Buy Shares
            </button>
            <a href="{{ route('shares.holdings') }}" class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600 text-lg font-medium">
                Cancel
            </a>
        </div>
    </form>
</div>

<!-- Quick Info -->
<div class="mt-6 bg-white rounded shadow p-6">
    <h2 class="text-lg font-bold mb-2">📋 Quick Guide</h2>
    <ul class="list-disc pl-5 text-gray-600">
        <li>Select a member from the dropdown</li>
        <li>Choose a share product</li>
        <li>Enter the number of shares (minimum 1)</li>
        <li>Click <strong>"Buy Shares"</strong></li>
    </ul>
</div>

<div class="mt-4">
    <a href="{{ route('shares.holdings') }}" class="text-blue-500 hover:underline">
        ← View Share Holdings
    </a>
</div>
@endsection