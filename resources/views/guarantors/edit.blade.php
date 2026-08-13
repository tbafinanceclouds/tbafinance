@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 max-w-2xl">
    <div class="flex items-center mb-6">
        <a href="{{ route('guarantors.index') }}" class="text-blue-500 mr-4">← Back</a>
        <h1 class="text-2xl font-bold">Edit Guarantor</h1>
    </div>

    <div class="bg-white rounded shadow p-6">
        <form action="{{ route('guarantors.update', $guarantor) }}" method="POST">
            @csrf @method('PUT')

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Loan</label>
                    <p class="font-medium">#{{ $guarantor->loan_id }}</p>
                    <input type="hidden" name="loan_id" value="{{ $guarantor->loan_id }}">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Guarantor</label>
                    <p class="font-medium">
                        @if($guarantor->member)
                            {{ $guarantor->member->first_name ?? '' }} {{ $guarantor->member->last_name ?? '' }}
                        @else
                            <span class="text-red-500">Member deleted</span>
                        @endif
                    </p>
                    <input type="hidden" name="member_id" value="{{ $guarantor->member_id }}">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Relationship *</label>
                    <input type="text" name="relationship" value="{{ old('relationship', $guarantor->relationship) }}" 
                           placeholder="e.g., Spouse, Parent, Colleague"
                           class="w-full border rounded px-3 py-2 @error('relationship') border-red-500 @enderror" required>
                    @error('relationship') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Amount Guaranteed (UGX) *</label>
                    <input type="number" name="amount_guaranteed" value="{{ old('amount_guaranteed', $guarantor->amount_guaranteed) }}" 
                           step="0.01" min="0"
                           class="w-full border rounded px-3 py-2 @error('amount_guaranteed') border-red-500 @enderror" required>
                    @error('amount_guaranteed') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Status</label>
                    <p class="font-medium">
                        <span class="px-2 py-1 text-xs rounded 
                            @if($guarantor->status == 'approved') bg-green-100 text-green-800
                            @elseif($guarantor->status == 'rejected') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ ucfirst($guarantor->status ?? 'pending') }}
                        </span>
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Notes</label>
                    <textarea name="notes" rows="3" 
                              class="w-full border rounded px-3 py-2 @error('notes') border-red-500 @enderror">{{ old('notes', $guarantor->notes) }}</textarea>
                    @error('notes') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                    Update Guarantor
                </button>
            </div>
        </form>
    </div>
</div>
@endsection