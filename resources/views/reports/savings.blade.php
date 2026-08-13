@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Savings Report</h1>
    <a href="{{ route('reports.savings.pdf') }}" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">📄 PDF</a>
</div>

<div class="bg-white rounded shadow overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-3 text-left">Account #</th>
                <th class="p-3 text-left">Member</th>
                <th class="p-3 text-left">Product</th>
                <th class="p-3 text-left">Balance</th>
            </tr>
        </thead>
        <tbody>
            @foreach($savings as $saving)
            <tr class="border-t">
                <td class="p-3">{{ $saving->account_number }}</td>
                <td class="p-3">{{ $saving->member->first_name }} {{ $saving->member->last_name }}</td>
                <td class="p-3">{{ $saving->savingsProduct->name }}</td>
                <td class="p-3 font-bold">{{ number_format($saving->balance, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection