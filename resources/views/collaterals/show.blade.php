@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 max-w-2xl">
    <div class="flex items-center mb-6">
        <a href="{{ route('collaterals.index') }}" class="text-blue-500 mr-4">← Back</a>
        <h1 class="text-2xl font-bold">Collateral Details</h1>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded shadow p-6">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-500">Name</p>
                <p class="font-medium">{{ $collateral->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Status</p>
                <span class="px-2 py-1 text-xs rounded {{ $collateral->status_badge }}">
                    {{ $collateral->status_label }}
                </span>
            </div>
            <div>
                <p class="text-sm text-gray-500">Type</p>
                <p class="font-medium">{{ $collateral->collateralType->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Loan</p>
                <p class="font-medium">
                    <a href="{{ route('loans.show', $collateral->loan_id) }}" class="text-blue-500 hover:underline">
                        #{{ $collateral->loan_id }}
                    </a>
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Member</p>
                <p class="font-medium">{{ $collateral->member->full_name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Estimated Value</p>
                <p class="font-medium">{{ $collateral->formatted_estimated_value }}</p>
            </div>
            @if($collateral->verified_value)
                <div>
                    <p class="text-sm text-gray-500">Verified Value</p>
                    <p class="font-medium">{{ $collateral->formatted_verified_value }}</p>
                </div>
            @endif
            <div>
                <p class="text-sm text-gray-500">Serial Number</p>
                <p class="font-medium">{{ $collateral->serial_number ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Location</p>
                <p class="font-medium">{{ $collateral->location ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Verification Date</p>
                <p class="font-medium">{{ $collateral->verification_date ? $collateral->verification_date->format('Y-m-d') : 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Verified By</p>
                <p class="font-medium">{{ $collateral->verifier->name ?? 'N/A' }}</p>
            </div>
            <div class="col-span-2">
                <p class="text-sm text-gray-500">Description</p>
                <p class="font-medium">{{ $collateral->description ?? 'No description' }}</p>
            </div>
            <div class="col-span-2">
                <p class="text-sm text-gray-500">Notes</p>
                <p class="font-medium">{{ $collateral->notes ?? 'No notes' }}</p>
            </div>
        </div>

        <div class="mt-6 flex flex-wrap gap-2 border-t pt-6">
            @if($collateral->isPending())
                <form action="{{ route('collaterals.verify', $collateral) }}" method="POST" class="inline">
                    @csrf
                    <div class="flex items-center gap-2">
                        <input type="number" name="verified_value" placeholder="Verified value" 
                               class="border rounded px-2 py-1 text-sm w-32">
                        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                            ✅ Verify
                        </button>
                    </div>
                </form>
                <form action="{{ route('collaterals.reject', $collateral) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600"
                            onclick="return confirm('Reject this collateral?')">
                        ❌ Reject
                    </button>
                </form>
            @endif

            @if($collateral->isVerified())
                <form action="{{ route('collaterals.release', $collateral) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
                            onclick="return confirm('Release this collateral?')">
                        🔓 Release
                    </button>
                </form>
            @endif

            <a href="{{ route('collaterals.edit', $collateral) }}" 
               class="bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600">
                ✏️ Edit
            </a>

            <form action="{{ route('collaterals.destroy', $collateral) }}" method="POST" class="inline">
                @csrf @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600"
                        onclick="return confirm('Delete this collateral?')">
                    🗑️ Delete
                </button>
            </form>
        </div>
    </div>
</div>
@endsection