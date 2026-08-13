@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-6">Add New Account</h1>

<div class="bg-white rounded shadow p-6 max-w-2xl">
    <form action="{{ route('accounting.store-account') }}" method="POST">
        @csrf

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-700">Account Code</label>
                <input type="text" name="account_code" class="w-full border rounded px-3 py-2" required>
                <p class="text-sm text-gray-500">e.g., 1000, 2000, 3000</p>
            </div>
            <div>
                <label class="block text-gray-700">Account Name</label>
                <input type="text" name="account_name" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-gray-700">Account Type</label>
                <select name="account_type" class="w-full border rounded px-3 py-2" required>
                    <option value="asset">Asset</option>
                    <option value="liability">Liability</option>
                    <option value="equity">Equity</option>
                    <option value="income">Income</option>
                    <option value="expense">Expense</option>
                </select>
            </div>
            <div>
                <label class="block text-gray-700">Normal Balance</label>
                <select name="normal_balance" class="w-full border rounded px-3 py-2" required>
                    <option value="debit">Debit</option>
                    <option value="credit">Credit</option>
                </select>
            </div>
        </div>

        <div class="mt-4 flex gap-2">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Save Account</button>
            <a href="{{ route('accounting.chart') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</a>
        </div>
    </form>
</div>
@endsection