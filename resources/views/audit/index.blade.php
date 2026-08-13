@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Audit Logs</h1>
</div>

<!-- Filters -->
<div class="bg-white rounded shadow p-4 mb-6">
    <form method="GET" action="{{ route('audit.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div>
            <label class="block text-sm text-gray-600">Module</label>
            <select name="module" class="w-full border rounded px-3 py-2">
                <option value="">All Modules</option>
                @foreach($modules as $module)
                    <option value="{{ $module }}" {{ request('module') == $module ? 'selected' : '' }}>
                        {{ ucfirst($module) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm text-gray-600">Action</label>
            <select name="action" class="w-full border rounded px-3 py-2">
                <option value="">All Actions</option>
                @foreach($actions as $action)
                    <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                        {{ ucfirst($action) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm text-gray-600">User</label>
            <select name="user_id" class="w-full border rounded px-3 py-2">
                <option value="">All Users</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm text-gray-600">Date From</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full border rounded px-3 py-2">
        </div>
        <div>
            <label class="block text-sm text-gray-600">Date To</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full border rounded px-3 py-2">
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Filter</button>
            <a href="{{ route('audit.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Reset</a>
        </div>
    </form>
</div>

<!-- Logs Table -->
<div class="bg-white rounded shadow overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-3 text-left">#</th>
                <th class="p-3 text-left">User</th>
                <th class="p-3 text-left">Action</th>
                <th class="p-3 text-left">Module</th>
                <th class="p-3 text-left">Description</th>
                <th class="p-3 text-left">IP Address</th>
                <th class="p-3 text-left">Date/Time</th>
                <th class="p-3 text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
            <tr class="border-t">
                <td class="p-3">{{ $log->id }}</td>
                <td class="p-3">{{ $log->user->name ?? 'N/A' }}</td>
                <td class="p-3">
                    <span class="bg-{{ $log->action_color }}-100 text-{{ $log->action_color }}-800 px-2 py-1 rounded text-sm capitalize">
                        {{ $log->action }}
                    </span>
                </td>
                <td class="p-3">{{ ucfirst($log->module) }}</td>
                <td class="p-3">{{ $log->description }}</td>
                <td class="p-3">{{ $log->ip_address }}</td>
                <td class="p-3">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                <td class="p-3">
                    <a href="{{ route('audit.show', $log->id) }}" class="text-blue-500 hover:underline">View</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="p-3 text-center text-gray-500">No logs found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4">
        {{ $logs->links() }}
    </div>
</div>
@endsection