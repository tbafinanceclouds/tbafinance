@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-6">Add Loan Product</h1>

<div class="bg-white rounded shadow p-6 max-w-2xl">
    <form action="{{ route('loans.products.store') }}" method="POST">
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
                    <option value="emergency">Emergency</option>
                    <option value="business">Business</option>
                    <option value="education">Education</option>
                </select>
            </div>
            <div>
                <label class="block text-gray-700">Interest Rate (%)</label>
                <input type="number" name="interest_rate" step="0.01" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-gray-700">Max Term (Months)</label>
                <input type="number" name="max_term_months" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-gray-700">Max Amount</label>
                <input type="number" name="max_amount" step="0.01" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-gray-700">Processing Fee</label>
                <input type="number" name="processing_fee" step="0.01" class="w-full border rounded px-3 py-2" value="0">
            </div>
            <div class="col-span-2">
                <label class="flex items-center">
                    <input type="checkbox" name="requires_guarantor" class="mr-2">
                    Requires Guarantor
                </label>
            </div>
        </div>

        <div class="mt-4 flex gap-2">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Save Product</button>
            <a href="{{ route('loans.products') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</a>
        </div>
    </form>
</div>
@endsection