@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">🏠 Collateral</h1>
        <div class="flex space-x-2">
            <a href="{{ route('collaterals.types') }}" 
               class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                🏷️ Types
            </a>
            <a href="{{ route('collaterals.create') }}" 
               class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                + Add Collateral
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded shadow p-4">
            <p class="text-sm text-gray-500">Total</p>
            <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded shadow p-4">
            <p class="text-sm text-gray-500">Pending</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
        </div>
        <div class="bg-white rounded shadow p-4">
            <p class="text-sm text-gray-500">Verified</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['verified'] }}</p>
        </div>
        <div class="bg-white rounded shadow p-4">
            <p class="text-sm text-gray-500">Released</p>
            <p class="text-2xl font-bold text-blue-600">{{ $stats['released'] }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded shadow p-4 mb-6">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-sm font-medium mb-1">Loan</label>
                <input type="number" name="loan_id" value="{{ request('loan_id') }}" 
                       class="border rounded px-3 py-2 w-32" placeholder="Loan ID">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Status</label>
                <select name="status" class="border rounded px-3 py-2">
                    <option value="">All</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verified</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="released" {{ request('status') == 'released' ? 'selected' : '' }}>Released</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Type</label>
                <select name="type_id" class="border rounded px-3 py-2">
                    <option value="">All Types</option>
                    @foreach($types as $type)
                        <option value="{{ $type->id }}" {{ request('type_id') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Filter
                </button>
                <a href="{{ route('collaterals.index') }}" class="ml-2 bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Loan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Member</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estimated Value</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($collaterals as $collateral)
                <tr>
                    <td class="px-6 py-4">{{ $collateral->name }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded" 
                              style="background: {{ $collateral->collateralType->color }}20; color: {{ $collateral->collateralType->color }}">
                            {{ $collateral->collateralType->name }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('loans.show', $collateral->loan_id) }}" class="text-blue-500 hover:underline">
                            #{{ $collateral->loan_id }}
                        </a>
                    </td>
                    <td class="px-6 py-4">{{ $collateral->member->full_name ?? 'N/A' }}</td>
                    <td class="px-6 py-4">{{ $collateral->formatted_estimated_value }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded {{ $collateral->status_badge }}">
                            {{ $collateral->status_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('collaterals.show', $collateral) }}" class="text-blue-500 hover:text-blue-700 mr-2">View</a>
                        @if($collateral->isPending())
                            <a href="{{ route('collaterals.verify', $collateral) }}" class="text-green-500 hover:text-green-700 mr-2"
                               onclick="return confirm('Verify this collateral?')">Verify</a>
                            <a href="{{ route('collaterals.reject', $collateral) }}" class="text-red-500 hover:text-red-700 mr-2"
                               onclick="return confirm('Reject this collateral?')">Reject</a>
                        @endif
                        @if($collateral->isVerified())
                            <a href="{{ route('collaterals.release', $collateral) }}" class="text-blue-500 hover:text-blue-700 mr-2"
                               onclick="return confirm('Release this collateral?')">Release</a>
                        @endif
                        <form action="{{ route('collaterals.destroy', $collateral) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700" 
                                    onclick="return confirm('Delete this collateral?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        No collateral found.
                        <a href="{{ route('collaterals.create') }}" class="text-blue-500 hover:underline">
                            Add your first collateral
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $collaterals->links() }}
    </div>
</div>
@endsection