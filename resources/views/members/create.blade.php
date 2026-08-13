@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-6">Add New Member</h1>

<div class="bg-white rounded shadow p-6 max-w-2xl">
    <form action="{{ route('members.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-700">First Name</label>
                <input type="text" name="first_name" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-gray-700">Last Name</label>
                <input type="text" name="last_name" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-gray-700">Email</label>
                <input type="email" name="email" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-gray-700">Phone</label>
                <input type="text" name="phone" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-gray-700">National ID</label>
                <input type="text" name="national_id" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-gray-700">Date of Birth</label>
                <input type="date" name="date_of_birth" class="w-full border rounded px-3 py-2">
            </div>
            <div class="col-span-2">
                <label class="block text-gray-700">Address</label>
                <textarea name="address" class="w-full border rounded px-3 py-2"></textarea>
            </div>
            <div class="col-span-2">
                <label class="block text-gray-700">Employer</label>
                <input type="text" name="employer" class="w-full border rounded px-3 py-2">
            </div>
        </div>

        <div class="mt-4 flex gap-2">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Save Member</button>
            <a href="{{ route('members.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</a>
        </div>
    </form>
</div>
@endsection