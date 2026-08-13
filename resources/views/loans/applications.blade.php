@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Loan Applications</h1>
    <a href="{{ route('loans.applications.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">+ New Application</a>
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
                <th class="p-3 text-left">Amount</th>
                <th class="p-3 text-left">Term</th>
                <th class="p-3 text-left">Status</th>
                <th class="p-3 text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($loans as $loan)
            <tr class="border-t">
                <td class="p-3">{{ $loan->member->first_name }} {{ $loan->member->last_name }}</td>
                <td class="p-3">{{ $loan->loanProduct->name }}</td>
                <td class="p-3">{{ number_format($loan->amount, 2) }}</td>
                <td class="p-3">{{ $loan->term_months }} months</td>
                <td class="p-3">
                    @php
                        $colors = [
                            'pending' => 'yellow',
                            'approved' => 'blue',
                            'disbursed' => 'green',
                            'completed' => 'green',
                            'defaulted' => 'red',
                            'rejected' => 'red',
                        ];
                    @endphp
                    <span class="bg-{{ $colors[$loan->status] ?? 'gray' }}-100 text-{{ $colors[$loan->status] ?? 'gray' }}-800 px-2 py-1 rounded capitalize">
                        {{ $loan->status }}
                    </span>
                </td>
                <td class="p-3">
                    <a href="{{ route('loans.show', $loan->id) }}" class="text-blue-500 hover:underline">View</a>
                    @if($loan->status == 'pending')
                        <form action="{{ route('loans.approve', $loan->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-green-500 hover:underline ml-2">Approve</button>
                        </form>
                        <form action="{{ route('loans.reject', $loan->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-red-500 hover:underline ml-2">Reject</button>
                        </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="p-3 text-center text-gray-500">No loan applications found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection