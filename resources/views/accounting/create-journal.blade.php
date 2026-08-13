@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-6">Create Journal Entry</h1>

<div class="bg-white rounded shadow p-6 max-w-2xl">
    <form action="{{ route('accounting.store-journal') }}" method="POST">
        @csrf

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-700">Entry Date</label>
                <input type="date" name="entry_date" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-gray-700">Reference</label>
                <input type="text" name="reference" class="w-full border rounded px-3 py-2">
            </div>
        </div>

        <div class="mt-4">
            <label class="block text-gray-700">Description</label>
            <textarea name="description" class="w-full border rounded px-3 py-2" rows="2" required></textarea>
        </div>

        <div class="mt-4">
            <h3 class="text-lg font-bold mb-2">Journal Lines</h3>
            <table class="w-full" id="journal-lines">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="p-2 text-left">Account</th>
                        <th class="p-2 text-left">Debit</th>
                        <th class="p-2 text-left">Credit</th>
                        <th class="p-2 text-left">Description</th>
                    </tr>
                </thead>
                <tbody id="lines-body">
                    <tr>
                        <td>
                            <select name="account_id[]" class="w-full border rounded px-2 py-1">
                                <option value="">Select Account</option>
                                @foreach($accounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->account_code }} - {{ $account->account_name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="number" name="debit[]" step="0.01" class="w-full border rounded px-2 py-1" value="0"></td>
                        <td><input type="number" name="credit[]" step="0.01" class="w-full border rounded px-2 py-1" value="0"></td>
                        <td><input type="text" name="detail_description[]" class="w-full border rounded px-2 py-1" placeholder="Line description"></td>
                    </tr>
                </tbody>
            </table>
            <button type="button" onclick="addLine()" class="mt-2 bg-gray-500 text-white px-4 py-1 rounded hover:bg-gray-600">+ Add Line</button>
        </div>

        <div class="mt-4 flex gap-2">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Save Journal Entry</button>
            <a href="{{ route('accounting.journal-entries') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</a>
        </div>
    </form>
</div>

<script>
    function addLine() {
        const tbody = document.getElementById('lines-body');
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>
                <select name="account_id[]" class="w-full border rounded px-2 py-1">
                    <option value="">Select Account</option>
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}">{{ $account->account_code }} - {{ $account->account_name }}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="number" name="debit[]" step="0.01" class="w-full border rounded px-2 py-1" value="0"></td>
            <td><input type="number" name="credit[]" step="0.01" class="w-full border rounded px-2 py-1" value="0"></td>
            <td><input type="text" name="detail_description[]" class="w-full border rounded px-2 py-1" placeholder="Line description"></td>
        `;
        tbody.appendChild(tr);
    }
</script>
@endsection