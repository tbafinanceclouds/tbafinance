@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-6">Balance Sheet</h1>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Assets -->
    <div class="bg-white rounded shadow p-6">
        <h2 class="text-lg font-bold text-green-600 mb-4">Assets</h2>
        @foreach($assets as $asset)
            <div class="flex justify-between border-b py-2">
                <span>{{ $asset->account_name }}</span>
                <span>{{ number_format($asset->balance, 2) }}</span>
            </div>
        @endforeach
        <div class="flex justify-between font-bold border-t-2 pt-2 mt-2">
            <span>Total Assets</span>
            <span>{{ number_format($assets->sum('balance'), 2) }}</span>
        </div>
    </div>

    <!-- Liabilities & Equity -->
    <div>
        <div class="bg-white rounded shadow p-6 mb-4">
            <h2 class="text-lg font-bold text-red-600 mb-4">Liabilities</h2>
            @foreach($liabilities as $liability)
                <div class="flex justify-between border-b py-2">
                    <span>{{ $liability->account_name }}</span>
                    <span>{{ number_format($liability->balance, 2) }}</span>
                </div>
            @endforeach
            <div class="flex justify-between font-bold border-t-2 pt-2 mt-2">
                <span>Total Liabilities</span>
                <span>{{ number_format($liabilities->sum('balance'), 2) }}</span>
            </div>
        </div>

        <div class="bg-white rounded shadow p-6">
            <h2 class="text-lg font-bold text-blue-600 mb-4">Equity</h2>
            @foreach($equity as $eq)
                <div class="flex justify-between border-b py-2">
                    <span>{{ $eq->account_name }}</span>
                    <span>{{ number_format($eq->balance, 2) }}</span>
                </div>
            @endforeach
            <div class="flex justify-between font-bold border-t-2 pt-2 mt-2">
                <span>Total Equity</span>
                <span>{{ number_format($equity->sum('balance'), 2) }}</span>
            </div>
        </div>
    </div>
</div>
@endsection