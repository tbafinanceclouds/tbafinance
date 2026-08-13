@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-6">Add Savings Product</h1>

<div class="bg-white rounded shadow p-6 max-w-2xl">
    <form action="{{ route('savings.products.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-2 gap-4">
            <div class="col-span-2">
                <label class="block text-gray-700">Product Name</label>
                <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-gray-700">Type</label>
                <select name="type" class="w-full border rounded px-3 py-2">
                    <option value="regular">Regular</option>
                    <option value="fixed">Fixed</option>
                    <option value="emergency">Emergency</option>
                </select>
            </div>
            <div>
                <label class="block text-gray-700">Interest Rate (%)</label>
                <input type="number" name="interest_rate" step="0.01" class="w-full border rounded px-3 py-2" value="0">
            </div>
            <div class="col-span-2">
                <label class="block text-gray-700">Minimum Balance</label>
                <input type="number" name="minimum_balance" step="0.01" class="w-full border rounded px-3 py-2" value="0">
            </div>
        </div>

        <div class="mt-4 flex gap-2">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Save Product</button>
            <a href="{{ route('savings.products') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</a>
        </div>
    </form>
</div>
@endsection
