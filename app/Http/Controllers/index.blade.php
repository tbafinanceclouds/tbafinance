@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">👥 Guarantors</h1>
        <a href="{{ route('guarantors.create') }}" 
           class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            + Add Guarantor
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filters -->
    <div class="bg-white rounded shadow p-4 mb-6">
        <form method="GET" class="flex flex-wrap gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Loan ID</label>
                <input type="number" name="loan_id" value="{{ request('loan_id') }}" 
                       class="border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Status</label>
                <select name="status" class="border rounded px-3 py-2">
                    <option value="">All</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Filter
                </button>
                <a href="{{ route('guarantors.index') }}" class="ml-2 bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Guarantors Table -->
    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Loan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Guarantor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Relationship</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($guarantors as $guarantor)
                <tr>
                    <td class="px-6 py-4">{{ $guarantor->id }}</td>
                    <td class="px-6 py-4">#{{ $guarantor->loan_id }}</td>
                    <td class="px-6 py-4">{{ $guarantor->member->full_name }}</td>
                    <td class="px-6 py-4">{{ $guarantor->relationship }}</td>
                    <td class="px-6 py-4">{{ number_format($guarantor->amount_guaranteed, 2) }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded 
                            @if($guarantor->status == 'approved') bg-green-100 text-green-800
                            @elseif($guarantor->status == 'rejected') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ ucfirst($guarantor->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('guarantors.show', $guarantor) }}" class="text-blue-500 hover:text-blue-700 mr-2">View</a>
                        @if($guarantor->isPending())
                            <a href="{{ route('guarantors.approve', $guarantor) }}" class="text-green-500 hover:text-green-700 mr-2"
                               onclick="return confirm('Approve this guarantor?')">Approve</a>
                            <a href="{{ route('guarantors.reject', $guarantor) }}" class="text-red-500 hover:text-red-700 mr-2"
                               onclick="return confirm('Reject this guarantor?')">Reject</a>
                        @endif
                        <form action="{{ route('guarantors.destroy', $guarantor) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700" 
                                    onclick="return confirm('Delete this guarantor?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        No guarantors found. <a href="{{ route('guarantors.create') }}" class="text-blue-500">Add your first guarantor</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $guarantors->links() }}
    </div>
</div>
@endsection