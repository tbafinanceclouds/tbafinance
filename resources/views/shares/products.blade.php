@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Share Products</h1>
    <a href="{{ route('shares.products.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">+ Add Product</a>
</div>

<div class="bg-white rounded shadow overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-3 text-left">Name</th>
                <th class="p-3 text-left">Code</th>
                <th class="p-3 text-left">Type</th>
                <th class="p-3 text-left">Price</th>
                <th class="p-3 text-left">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr class="border-t">
                <td class="p-3">{{ $product->name }}</td>
                <td class="p-3">{{ $product->code }}</td>
                <td class="p-3">{{ ucfirst($product->type) }}</td>
                <td class="p-3">{{ number_format($product->price_per_share, 2) }}</td>
                <td class="p-3">
                    @if($product->is_active)
                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded">Active</span>
                    @else
                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded">Inactive</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection