@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 max-w-2xl">
    <div class="flex items-center mb-6">
        <a href="{{ route('collaterals.index') }}" class="text-blue-500 mr-4">← Back</a>
        <h1 class="text-2xl font-bold">Add Collateral</h1>
    </div>

    <div class="bg-white rounded shadow p-6">
        <form action="{{ route('collaterals.store') }}" method="POST">
            @csrf

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Loan *</label>
                    <select name="loan_id" class="w-full border rounded px-3 py-2 @error('loan_id') border-red-500 @enderror" required>
                        <option value="">Select Loan</option>
                        @foreach($loans as $loan)
                            <option value="{{ $loan->id }}" {{ old('loan_id', $selectedLoan->id ?? '') == $loan->id ? 'selected' : '' }}>
                                #{{ $loan->id }} - {{ $loan->member->full_name ?? 'Unknown' }} - UGX {{ number_format($loan->amount, 2) }}
                            </option>
                        @endforeach
                    </select>
                    @error('loan_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Member *</label>
                    <select name="member_id" class="w-full border rounded px-3 py-2 @error('member_id') border-red-500 @enderror" required>
                        <option value="">Select Member</option>
                        @foreach($members as $member)
                            <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
                                {{ $member->full_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('member_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Collateral Type *</label>
                    <select name="collateral_type_id" class="w-full border rounded px-3 py-2 @error('collateral_type_id') border-red-500 @enderror" required>
                        <option value="">Select Type</option>
                        @foreach($types as $type)
                            <option value="{{ $type->id }}" {{ old('collateral_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('collateral_type_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Collateral Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" 
                           class="w-full border rounded px-3 py-2 @error('name') border-red-500 @enderror" required>
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Description</label>
                    <textarea name="description" rows="3" 
                              class="w-full border rounded px-3 py-2 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Estimated Value (UGX) *</label>
                    <input type="number" name="estimated_value" value="{{ old('estimated_value') }}" 
                           step="0.01" min="0"
                           class="w-full border rounded px-3 py-2 @error('estimated_value') border-red-500 @enderror" required>
                    @error('estimated_value') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Serial / Registration Number</label>
                    <input type="text" name="serial_number" value="{{ old('serial_number') }}" 
                           class="w-full border rounded px-3 py-2 @error('serial_number') border-red-500 @enderror">
                    @error('serial_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Location</label>
                    <input type="text" name="location" value="{{ old('location') }}" 
                           class="w-full border rounded px-3 py-2 @error('location') border-red-500 @enderror">
                    @error('location') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Notes</label>
                    <textarea name="notes" rows="2" 
                              class="w-full border rounded px-3 py-2 @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                    @error('notes') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                    Save Collateral
                </button>
            </div>
        </form>
    </div>
</div>
@endsection