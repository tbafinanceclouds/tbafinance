@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 max-w-2xl">
    <div class="flex items-center mb-6">
        <a href="{{ route('subscription.admin') }}" class="text-blue-500 mr-4">← Back</a>
        <h1 class="text-2xl font-bold">✏️ Edit Pricing Plan</h1>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded shadow p-6">
        <form action="{{ route('subscription.update', $plan) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Plan Name *</label>
                    <input type="text" name="name" value="{{ old('name', $plan->name) }}" 
                           class="w-full border rounded px-3 py-2" required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Description</label>
                    <textarea name="description" rows="2" 
                              class="w-full border rounded px-3 py-2">{{ old('description', $plan->description) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Price (UGX) *</label>
                    <input type="number" name="price" value="{{ old('price', $plan->price) }}" 
                           step="0.01" min="0"
                           class="w-full border rounded px-3 py-2" required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Billing Period</label>
                    <select name="billing_period" class="w-full border rounded px-3 py-2">
                        <option value="monthly" {{ $plan->billing_period == 'monthly' ? 'selected' : '' }}>Monthly</option>
                        <option value="yearly" {{ $plan->billing_period == 'yearly' ? 'selected' : '' }}>Yearly</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Features (one per line)</label>
                    <textarea name="features" rows="4" 
                              class="w-full border rounded px-3 py-2">{{ is_array($plan->features) ? implode("\n", $plan->features) : '' }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Enter each feature on a new line</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Max Members</label>
                        <input type="number" name="max_members" value="{{ old('max_members', $plan->max_members) }}" 
                               class="w-full border rounded px-3 py-2">
                        <p class="text-xs text-gray-500">0 = Unlimited</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Max Users</label>
                        <input type="number" name="max_users" value="{{ old('max_users', $plan->max_users) }}" 
                               class="w-full border rounded px-3 py-2">
                        <p class="text-xs text-gray-500">0 = Unlimited</p>
                    </div>
                </div>

                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $plan->is_active) ? 'checked' : '' }} 
                               class="mr-2">
                        <span class="text-sm font-medium">Active</span>
                    </label>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-2">
                <a href="{{ route('subscription.admin') }}" class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                    Update Plan
                </button>
            </div>
        </form>
    </div>
    <!-- After Price field -->
<div>
    <label class="block text-sm font-medium mb-1">Yearly Price (UGX)</label>
    <input type="number" name="yearly_price" value="{{ old('yearly_price', $plan->yearly_price) }}" 
           step="0.01" min="0"
           class="w-full border rounded px-3 py-2">
    <p class="text-xs text-gray-500 mt-1">Leave blank if same as monthly × 12</p>
</div>

<div>
    <label class="block text-sm font-medium mb-1">Trial Period (Days)</label>
    <input type="number" name="trial_days" value="{{ old('trial_days', $plan->trial_days) }}" 
           min="0"
           class="w-full border rounded px-3 py-2">
    <p class="text-xs text-gray-500 mt-1">0 = No trial</p>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium mb-1">Promo Code</label>
        <input type="text" name="promo_code" value="{{ old('promo_code', $plan->promo_code) }}" 
               class="w-full border rounded px-3 py-2">
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Promo Discount (%)</label>
        <input type="number" name="promo_discount" value="{{ old('promo_discount', $plan->promo_discount) }}" 
               min="0" max="100"
               class="w-full border rounded px-3 py-2">
    </div>
</div>

<div>
    <label class="block text-sm font-medium mb-1">Promo Expiry Date</label>
    <input type="datetime-local" name="promo_expires_at" 
           value="{{ old('promo_expires_at', $plan->promo_expires_at ? date('Y-m-d\TH:i', strtotime($plan->promo_expires_at)) : '') }}" 
           class="w-full border rounded px-3 py-2">
    <p class="text-xs text-gray-500 mt-1">Leave blank for no expiry</p>
</div>

<div>
    <label class="flex items-center">
        <input type="checkbox" name="is_popular" value="1" 
               {{ old('is_popular', $plan->is_popular) ? 'checked' : '' }} class="mr-2">
        <span class="text-sm font-medium">⭐ Popular (shows badge)</span>
    </label>
</div>

<div>
    <label class="block text-sm font-medium mb-1">Max Features</label>
    <input type="number" name="max_features" value="{{ old('max_features', $plan->max_features) }}" 
           min="0"
           class="w-full border rounded px-3 py-2">
    <p class="text-xs text-gray-500 mt-1">0 = Unlimited</p>
</div>

    <!-- Preview -->
    <div class="mt-6 bg-gray-50 border rounded p-4">
        <h3 class="font-semibold text-sm text-gray-600 mb-2">📋 Current Plan</h3>
        <p><strong>Name:</strong> {{ $plan->name }}</p>
        <p><strong>Price:</strong> UGX {{ number_format($plan->price, 0) }}</p>
    </div>
</div>
@endsection
