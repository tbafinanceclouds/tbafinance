@extends('layouts.company')

@section('content')
<div class="container mx-auto px-4 max-w-2xl">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">⚙️ Company Settings</h1>
        <a href="{{ route('company.dashboard') }}" class="text-blue-600 hover:text-blue-800">← Back</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('company.settings.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Company Name</label>
                    <p class="font-medium text-gray-900">{{ $company->name }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <p class="font-medium text-gray-900">{{ $company->email }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $company->phone) }}" 
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                    @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Address</label>
                    <textarea name="address" rows="2" 
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">{{ old('address', $company->address) }}</textarea>
                    @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Contact Person</label>
                    <input type="text" name="contact_person" value="{{ old('contact_person', $company->contact_person) }}" 
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                    @error('contact_person') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Business Type</label>
                    <p class="font-medium text-gray-900">{{ $company->business_type_label }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Registration Number</label>
                    <p class="font-medium text-gray-900">{{ $company->formatted_registration_number }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Subscription Plan</label>
                    <p class="font-medium text-gray-900">{{ $company->plan_name }}</p>
                </div>
            </div>

            <button type="submit" 
                    class="mt-6 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
                Update Settings
            </button>
        </form>
    </div>
</div>
@endsection