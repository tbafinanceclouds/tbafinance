@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 max-w-2xl">
    <div class="flex items-center mb-6">
        <a href="{{ route('guarantors.index') }}" class="text-blue-500 mr-4">← Back</a>
        <h1 class="text-2xl font-bold">Guarantor Details</h1>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded shadow p-6">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-500">ID</p>
                <p class="font-medium">#{{ $guarantor->id }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Status</p>
                <span class="px-2 py-1 text-xs rounded 
                    @if($guarantor->status == 'approved') bg-green-100 text-green-800
                    @elseif($guarantor->status == 'rejected') bg-red-100 text-red-800
                    @else bg-yellow-100 text-yellow-800 @endif">
                    {{ ucfirst($guarantor->status ?? 'pending') }}
                </span>
            </div>
            <div>
                <p class="text-sm text-gray-500">Loan</p>
                <p class="font-medium">
                    <a href="{{ route('loans.show', $guarantor->loan_id) }}" class="text-blue-500 hover:underline">
                        #{{ $guarantor->loan_id }}
                    </a>
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Guarantor</p>
                <p class="font-medium">
                    @if($guarantor->member)
                        {{ $guarantor->member->first_name ?? '' }} {{ $guarantor->member->last_name ?? '' }}
                    @else
                        <span class="text-red-500">Member deleted</span>
                    @endif
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Relationship</p>
                <p class="font-medium">{{ $guarantor->relationship ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Amount Guaranteed</p>
                <p class="font-medium">UGX {{ number_format($guarantor->amount_guaranteed ?? 0, 2) }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Guarantee Date</p>
                <p class="font-medium">{{ $guarantor->guarantee_date ? date('Y-m-d', strtotime($guarantor->guarantee_date)) : 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Approval Date</p>
                <p class="font-medium">{{ $guarantor->approval_date ? date('Y-m-d', strtotime($guarantor->approval_date)) : 'N/A' }}</p>
            </div>
            <div class="col-span-2">
                <p class="text-sm text-gray-500">Notes</p>
                <p class="font-medium">{{ $guarantor->notes ?? 'No notes' }}</p>
            </div>
        </div>

        <div class="mt-6 flex space-x-2 flex-wrap gap-2">
            @if(($guarantor->status ?? 'pending') == 'pending')
                <form action="{{ route('guarantors.approve', $guarantor) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600"
                            onclick="return confirm('Approve this guarantor?')">
                        ✅ Approve
                    </button>
                </form>
                <form action="{{ route('guarantors.reject', $guarantor) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600"
                            onclick="return confirm('Reject this guarantor?')">
                        ❌ Reject
                    </button>
                </form>
            @endif
            <a href="{{ route('guarantors.edit', $guarantor) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                ✏️ Edit
            </a>
            <form action="{{ route('guarantors.destroy', $guarantor) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600"
                        onclick="return confirm('Delete this guarantor?')">
                    🗑️ Delete
                </button>
            </form>
        </div>
    </div>
</div>
@endsection