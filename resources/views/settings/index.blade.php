@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-6">Company Settings</h1>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded shadow p-6 max-w-2xl">
        <!-- Logo Upload Section -->
        <div class="text-center mb-6 pb-6 border-b">
            <h3 class="text-lg font-medium mb-3">Company Logo</h3>
            
            @if($company->logo)
                <img src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->name }}" 
                     class="h-24 w-24 object-cover rounded-full mx-auto mb-2 border-2 border-gray-300">
            @else
                <div class="w-24 h-24 bg-gray-200 rounded-full mx-auto flex items-center justify-center text-gray-500 text-3xl mb-2 border-2 border-gray-300">
                    {{ substr($company->name, 0, 1) }}
                </div>
            @endif
            
            <form action="{{ route('settings.logo') }}" method="POST" enctype="multipart/form-data" class="mt-3">
                @csrf
                <div class="flex flex-col items-center gap-2">
                    <input type="file" name="logo" accept="image/*" class="text-sm border rounded px-3 py-1 w-full max-w-xs">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 text-sm">
                        Upload Logo
                    </button>
                </div>
                <p class="text-xs text-gray-400 mt-1">Max 2MB. JPG, PNG, GIF only</p>
            </form>
        </div>

        <!-- Settings Form -->
        <form action="{{ route('settings.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-4">
                <!-- Company Name -->
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Company Name</label>
                    <input type="text" name="name" value="{{ $company->name }}" 
                           class="w-full border rounded px-3 py-2" required>
                </div>

                <!-- Company Email -->
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Email</label>
                    <input type="email" name="email" value="{{ $company->email }}" 
                           class="w-full border rounded px-3 py-2" required>
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Phone</label>
                    <input type="text" name="phone" value="{{ $company->phone }}" 
                           class="w-full border rounded px-3 py-2">
                </div>

                <!-- Address -->
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Address</label>
                    <textarea name="address" rows="3" class="w-full border rounded px-3 py-2">{{ $company->address }}</textarea>
                </div>

                <!-- Currency -->
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Currency</label>
                    <select name="currency" class="w-full border rounded px-3 py-2">
                        <option value="UGX" {{ $company->currency == 'UGX' ? 'selected' : '' }}>UGX - Ugandan Shilling</option>
                        <option value="KES" {{ $company->currency == 'KES' ? 'selected' : '' }}>KES - Kenyan Shilling</option>
                        <option value="TZS" {{ $company->currency == 'TZS' ? 'selected' : '' }}>TZS - Tanzanian Shilling</option>
                        <option value="RWF" {{ $company->currency == 'RWF' ? 'selected' : '' }}>RWF - Rwandan Franc</option>
                        <option value="USD" {{ $company->currency == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                    </select>
                </div>

                <!-- Subscription Status -->
                <div class="border-t pt-4 mt-2">
                    <p class="text-sm text-gray-500">Subscription Status</p>
                    <p class="font-bold">
                        @if($company->subscription_expiry && strtotime($company->subscription_expiry) > time())
                            <span class="text-green-600">Active until {{ date('Y-m-d', strtotime($company->subscription_expiry)) }}</span>
                        @else
                            <span class="text-red-600">Expired</span>
                        @endif
                    </p>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection