@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 max-w-2xl">
    <div class="flex items-center mb-6">
        <a href="{{ route('payments.index') }}" class="text-blue-500 mr-4">← Back</a>
        <h1 class="text-2xl font-bold">💰 Mobile Money Payment</h1>
    </div>

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded shadow p-6">
        <form action="{{ route('payments.initiate') }}" method="POST">
            @csrf

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Amount (UGX) *</label>
                    <input type="number" name="amount" value="{{ old('amount') }}" 
                           class="w-full border rounded px-3 py-2 @error('amount') border-red-500 @enderror" required>
                    @error('amount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Network *</label>
                    <select name="network" class="w-full border rounded px-3 py-2 @error('network') border-red-500 @enderror" required>
                        <option value="">Select Network</option>
                        <option value="MTN" {{ old('network') == 'MTN' ? 'selected' : '' }}>MTN Mobile Money</option>
                        <option value="AIRTEL" {{ old('network') == 'AIRTEL' ? 'selected' : '' }}>Airtel Money</option>
                    </select>
                    @error('network') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Phone Number *</label>
                    <input type="text" name="msisdn" value="{{ old('msisdn') }}" 
                           placeholder="2567XXXXXXXX"
                           class="w-full border rounded px-3 py-2 @error('msisdn') border-red-500 @enderror" required>
                    <p class="text-xs text-gray-500 mt-1">Format: 2567XXXXXXXX (no + sign)</p>
                    @error('msisdn') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Customer Name *</label>
                    <input type="text" name="customer_name" value="{{ old('customer_name') }}" 
                           class="w-full border rounded px-3 py-2 @error('customer_name') border-red-500 @enderror" required>
                    @error('customer_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Email *</label>
                    <input type="email" name="customer_email" value="{{ old('customer_email') }}" 
                           class="w-full border rounded px-3 py-2 @error('customer_email') border-red-500 @enderror" required>
                    @error('customer_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Description</label>
                    <input type="text" name="description" value="{{ old('description', 'SACCO Payment') }}" 
                           class="w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Member (Optional)</label>
                    <select name="member_id" class="w-full border rounded px-3 py-2">
                        <option value="">Select Member</option>
                        @foreach($members ?? [] as $member)
                            <option value="{{ $member->id }}">{{ $member->full_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <button type="submit" class="mt-6 bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600 w-full">
                Pay Now
            </button>
        </form>
    </div>

    <div class="mt-4 p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded">
        <p class="font-medium">⚠️ Testing Mode</p>
        <p class="text-sm">Use test phone number: <strong>256771234567</strong></p>
        <p class="text-sm">Test OTP: <strong>123456</strong></p>
    </div>
</div>
@endsection