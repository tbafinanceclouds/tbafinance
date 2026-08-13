@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Chart of Accounts</h1>
    <a href="{{ route('accounting.create-account') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">+ Add Account</a>
</div>

@if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded shadow overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-3 text-left">Code</th>
                <th class="p-3 text-left">Account Name</th>
                <th class="p-3 text-left">Type</th>
                <th class="p-3 text-left">Normal Balance</th>
                <th class="p-3 text-left">Status</th>
                <th class="p-3 text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($accounts as $account)
            <tr class="border-t">
                <td class="p-3">{{ $account->account_code }}</td>
                <td class="p-3">{{ $account->account_name }}</td>
                <td class="p-3 capitalize">{{ $account->account_type }}</td>
                <td class="p-3 capitalize">{{ $account->normal_balance }}</td>
                <td class="p-3">
                    @if($account->is_active)
                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded">Active</span>
                    @else
                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded">Inactive</span>
                    @endif
                </td>
                <td class="p-3">
                    <a href="{{ route('accounting.account-details', $account->id) }}" class="text-blue-500 hover:underline">View</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection