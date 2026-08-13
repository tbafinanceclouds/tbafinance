@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-6">Open Savings Account</h1>

<div class="bg-white rounded shadow p-6 max-w-2xl">
    <form action="{{ route('savings.accounts.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 gap-4">
            <div>
                <label class="block text-gray-700">Member</label>
                <select name="member_id" class="w-full border rounded px-3 py-2" required>
                    <option value="">Select Member</option>
                    @foreach($members as $member)
                        <option value="{{ $member->id }}">{{ $member->first_name }} {{ $member->last_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-gray-700">Savings Product</label>
                <select name="savings_product_id" class="w-full border rounded px-3 py-2" required>
                    <option value="">Select Product</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }} ({{ ucfirst($product->type) }})</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mt-4 flex gap-2">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Open Account</button>
            <a href="{{ route('savings.accounts') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</a>
        </div>
    </form>
</div>
@endsection