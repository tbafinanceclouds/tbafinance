@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-6">New Cashbook Entry</h1>

<div class="bg-white rounded shadow p-6 max-w-2xl">
    <form action="{{ route('cashbook.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-700">Transaction Date</label>
                <input type="date" name="transaction_date" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-gray-700">Type</label>
                <select name="type" class="w-full border rounded px-3 py-2" required>
                    <option value="cash_in">Cash In</option>
                    <option value="cash_out">Cash Out</option>
                </select>
            </div>
            <div>
                <label class="block text-gray-700">Category</label>
                <select name="category" class="w-full border rounded px-3 py-2" required>
                    <option value="deposit">Member Deposit</option>
                    <option value="loan_repayment">Loan Repayment</option>
                    <option value="income">Income</option>
                    <option value="withdrawal">Member Withdrawal</option>
                    <option value="expense">Expense</option>
                    <option value="loan_disbursement">Loan Disbursement</option>
                </select>
            </div>
            <div>
                <label class="block text-gray-700">Payment Method</label>
                <select name="payment_method" class="w-full border rounded px-3 py-2" required>
                    <option value="cash">Cash</option>
                    <option value="bank">Bank</option>
                    <option value="mobile_money">Mobile Money</option>
                    <option value="cheque">Cheque</option>
                </select>
            </div>
            <div class="col-span-2">
                <label class="block text-gray-700">Reference</label>
                <input type="text" name="reference" class="w-full border rounded px-3 py-2" placeholder="Reference number">
            </div>
            <div class="col-span-2">
                <label class="block text-gray-700">Description</label>
                <textarea name="description" class="w-full border rounded px-3 py-2" rows="2" required></textarea>
            </div>
            <div class="col-span-2">
                <label class="block text-gray-700">Amount</label>
                <input type="number" name="amount" step="0.01" class="w-full border rounded px-3 py-2" required>
            </div>
        </div>

        <div class="mt-4 flex gap-2">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Save Entry</button>
            <a href="{{ route('cashbook.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</a>
        </div>
    </form>
</div>
@endsection