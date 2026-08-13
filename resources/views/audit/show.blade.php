@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Audit Log Details</h1>
    <a href="{{ route('audit.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">← Back</a>
</div>

<div class="bg-white rounded shadow p-6 max-w-3xl">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <p class="text-sm text-gray-500">Log ID</p>
            <p class="font-bold">#{{ $log->id }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">Date/Time</p>
            <p class="font-bold">{{ $log->created_at->format('Y-m-d H:i:s') }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">User</p>
            <p class="font-bold">{{ $log->user->name ?? 'N/A' }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">Company</p>
            <p class="font-bold">{{ $log->company->name ?? 'N/A' }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">Action</p>
            <p class="font-bold capitalize">{{ $log->action }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">Module</p>
            <p class="font-bold">{{ ucfirst($log->module) }}</p>
        </div>
        <div class="col-span-2">
            <p class="text-sm text-gray-500">Description</p>
            <p class="font-bold">{{ $log->description }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">IP Address</p>
            <p class="font-bold">{{ $log->ip_address }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">User Agent</p>
            <p class="font-bold text-sm">{{ $log->user_agent }}</p>
        </div>
    </div>
</div>
@endsection