@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Journal Entries</h1>
    <a href="{{ route('accounting.create-journal') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">+ New Entry</a>
</div>

@if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
        {{ session('error') }}
    </div>
@endif

<div class="bg-white rounded shadow overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-3 text-left">Date</th>
                <th class="p-3 text-left">Reference</th>
                <th class="p-3 text-left">Description</th>
                <th class="p-3 text-left">Debit</th>
                <th class="p-3 text-left">Credit</th>
                <th class="p-3 text-left">Status</th>
                <th class="p-3 text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($entries as $entry)
            <tr class="border-t">
                <td class="p-3">{{ $entry->entry_date->format('Y-m-d') }}</td>
                <td class="p-3">{{ $entry->reference }}</td>
                <td class="p-3">{{ $entry->description }}</td>
                <td class="p-3">{{ number_format($entry->total_debit, 2) }}</td>
                <td class="p-3">{{ number_format($entry->total_credit, 2) }}</td>
                <td class="p-3">
                    @if($entry->status == 'posted')
                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded">Posted</span>
                    @else
                        <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded">Draft</span>
                    @endif
                </td>
                <td class="p-3">
                    @if($entry->status == 'draft')
                        <form action="{{ route('accounting.journal.post', $entry->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-green-500 hover:underline">Post</button>
                        </form>
                        <form action="{{ route('accounting.journal-entries.destroy', $entry->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:underline" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection