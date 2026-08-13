@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-6">Add Share Product</h1>

<div class="bg-white rounded shadow p-6 max-w-2xl">
    <form action="{{ route('shares.products.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-2 gap-4">
            <div class="col-span-2">
                <label class="block text-gray-700">Product Name</label>
                <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-gray-700">Code</label>
                <input type="text" name="code" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-gray-700">Type</label>
                <select name="type" class="w-full border rounded px-3 py-2">
                    <option value="ordinary">Ordinary</option>
                    <option value="preference">Preference</option>
                    <option value="class_a">Class A</option>
                    <option value="class_b">Class B</option>
                </select>
            </div>
            <div>
                <label class="block text-gray-700">Price Per Share</label>
                <input type="number" name="price_per_share" step="0.01" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-gray-700">Min Shares</label>
                <input type="number" name="min_shares" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-gray-700">Max Shares</label>
                <input type="number" name="max_shares" class="w-full border rounded px-3 py-2">
            </div>
            <div class="col-span-2">
                <label class="block text-gray-700">Dividend Rate (%)</label>
                <input type="number" name="dividend_rate" step="0.01" class="w-full border rounded px-3 py-2" value="0">
            </div>
        </div>

        <div class="mt-4 flex gap-2">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Save Product</button>
            <a href="{{ route('shares.products') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</a>
        </div>
    </form>
</div>
@endsection