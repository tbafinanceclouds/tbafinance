@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-6">Trial Balance</h1>

<div class="bg-white rounded shadow overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-3 text-left">Account Code</th>
                <th class="p-3 text-left">Account Name</th>
                <th class="p-3 text-left">Debit</th>
                <th class="p-3 text-left">Credit</th>
            </tr>
        </thead>
        <tbody>
            @foreach($accounts as $account)
                @if(isset($account->balance_amount) && $account->balance_amount > 0)
                <tr class="border-t">
                    <td class="p-3">{{ $account->account_code }}</td>
                    <td class="p-3">{{ $account->account_name }}</td>
                    <td class="p-3">
                        @if($account->balance_type == 'debit')
                            {{ number_format($account->balance_amount, 2) }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="p-3">
                        @if($account->balance_type == 'credit')
                            {{ number_format($account->balance_amount, 2) }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
                @endif
            @endforeach
        </tbody>
        <tfoot>
            <tr class="bg-gray-200 font-bold">
                <td colspan="2" class="p-3 text-right">Total</td>
                <td class="p-3">{{ number_format($totalDebits ?? 0, 2) }}</td>
                <td class="p-3">{{ number_format($totalCredits ?? 0, 2) }}</td>
            </tr>
        </tfoot>
    </table>
</div>
@endsection