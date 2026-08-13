@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Loan Products</h1>
    <a href="{{ route('loans.products.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">+ Add Product</a>
</div>

@if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded shadow overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-3 text-left">Name</th>
                <th class="p-3 text-left">Type</th>
                <th class="p-3 text-left">Interest Rate</th>
                <th class="p-3 text-left">Max Term</th>
                <th class="p-3 text-left">Max Amount</th>
                <th class="p-3 text-left">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr class="border-t">
                <td class="p-3">{{ $product->name }}</td>
                <td class="p-3">{{ ucfirst($product->type) }}</td>
                <td class="p-3">{{ $product->interest_rate }}%</td>
                <td class="p-3">{{ $product->max_term_months }} months</td>
                <td class="p-3">{{ number_format($product->max_amount, 2) }}</td>
                <td class="p-3">
                    @if($product->is_active)
                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded">Active</span>
                    @else
                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded">Inactive</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="p-3 text-center text-gray-500">No loan products found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection