@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">⚙️ Manage Pricing Plans</h1>
        <a href="{{ route('subscription.index') }}" class="text-blue-500 hover:text-blue-700 mr-4">← Back to Pricing</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($plans as $plan)
        <div class="bg-white rounded shadow p-6">
            <div class="flex justify-between items-start">
                <h2 class="text-xl font-bold">{{ $plan->name }}</h2>
                <span class="px-2 py-1 text-xs rounded {{ $plan->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $plan->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
            <p class="text-2xl font-bold text-blue-600 mt-2">{{ $plan->formatted_price }}</p>
            <p class="text-sm text-gray-500">/ {{ $plan->billing_period }}</p>
            <ul class="mt-4 space-y-1">
                @foreach($plan->features_list as $feature)
                    <li class="text-sm text-gray-700">✓ {{ $feature }}</li>
                @endforeach
            </ul>
            <div class="mt-4 pt-4 border-t flex space-x-2">
                <!-- ✅ FIXED: Proper edit link -->
                <a href="{{ route('subscription.edit', $plan) }}" class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">
                    ✏️ Edit
                </a>
                <form action="{{ route('subscription.delete', $plan) }}" method="POST" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600"
                            onclick="return confirm('Delete this plan?')">
                        🗑️ Delete
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-8 bg-white rounded shadow p-6">
        <h2 class="text-xl font-bold mb-4">➕ Create New Plan</h2>
        <form action="{{ route('subscription.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium">Plan Name *</label>
                    <input type="text" name="name" required class="w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium">Price (UGX) *</label>
                    <input type="number" name="price" required class="w-full border rounded px-3 py-2">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium">Description</label>
                    <textarea name="description" rows="2" class="w-full border rounded px-3 py-2"></textarea>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium">Features (one per line)</label>
                    <textarea name="features" rows="4" class="w-full border rounded px-3 py-2" 
                              placeholder="Up to 100 members&#10;1 user account&#10;Basic reports"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium">Max Members</label>
                    <input type="number" name="max_members" value="0" class="w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium">Max Users</label>
                    <input type="number" name="max_users" value="0" class="w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium">Billing Period</label>
                    <select name="billing_period" class="w-full border rounded px-3 py-2">
                        <option value="monthly">Monthly</option>
                        <option value="yearly">Yearly</option>
                    </select>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" checked class="mr-2">
                    <span class="text-sm font-medium">Active</span>
                </div>
            </div>
            <button type="submit" class="mt-4 bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                Create Plan
            </button>
        </form>
    </div>
</div>
@endsection
