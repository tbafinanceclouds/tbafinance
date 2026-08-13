@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Members Report</h1>
    <div class="flex gap-2">
        <a href="{{ route('reports.members.pdf') }}" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">📄 PDF</a>
        <a href="{{ route('reports.members.csv') }}" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">📊 CSV</a>
    </div>
</div>

<div class="bg-white rounded shadow overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-3 text-left">ID</th>
                <th class="p-3 text-left">Name</th>
                <th class="p-3 text-left">Email</th>
                <th class="p-3 text-left">Phone</th>
                <th class="p-3 text-left">National ID</th>
                <th class="p-3 text-left">Date Registered</th>
            </tr>
        </thead>
        <tbody>
            @foreach($members as $member)
            <tr class="border-t">
                <td class="p-3">{{ $member->id }}</td>
                <td class="p-3">{{ $member->first_name }} {{ $member->last_name }}</td>
                <td class="p-3">{{ $member->email }}</td>
                <td class="p-3">{{ $member->phone }}</td>
                <td class="p-3">{{ $member->national_id }}</td>
                <td class="p-3">{{ $member->created_at->format('Y-m-d') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection