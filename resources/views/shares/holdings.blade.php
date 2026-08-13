@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Share Holdings</h1>
    <a href="{{ route('shares.buy') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
        + Buy Shares
    </a>
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
                <th class="p-3 text-left">Member</th>
                <th class="p-3 text-left">Product</th>
                <th class="p-3 text-left">Shares</th>
                <th class="p-3 text-left">Total Value</th>
                <th class="p-3 text-left">Purchase Date</th>
                <th class="p-3 text-left">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($holdings as $holding)
            <tr class="border-t">
                <td class="p-3">{{ $holding->member->first_name }} {{ $holding->member->last_name }}</td>
                <td class="p-3">{{ $holding->shareProduct->name }}</td>
                <td class="p-3">{{ $holding->shares }}</td>
                <td class="p-3">{{ number_format($holding->total_value, 2) }}</td>
                <td class="p-3">{{ $holding->purchase_date->format('Y-m-d') }}</td>
                <td class="p-3">
                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded">{{ ucfirst($holding->status) }}</span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="p-3 text-center text-gray-500">No share holdings found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection