<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Loans Report</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 8px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f2f2f2; }
        .footer { text-align: center; font-size: 11px; color: #999; margin-top: 20px; border-top: 1px solid #ddd; padding-top: 10px; }
        .letterhead { text-align: center; border-bottom: 3px solid #333; padding-bottom: 15px; margin-bottom: 20px; }
        .letterhead h1 { font-size: 20px; margin: 0; color: #333; }
        .letterhead p { margin: 2px 0; font-size: 12px; color: #666; }
        .letterhead h2 { font-size: 16px; color: #555; margin: 5px 0; }
        .letterhead .date { font-size: 11px; color: #999; }
    </style>
</head>
<body>
    <!-- Letterhead -->
    <div class="letterhead">
        @if(auth()->user()->company->logo)
            <img src="{{ public_path('storage/' . auth()->user()->company->logo) }}" alt="{{ auth()->user()->company->name }}" style="max-height: 60px; margin-bottom: 5px;">
        @endif
        <h1>{{ auth()->user()->company->name }}</h1>
        <p>{{ auth()->user()->company->address ?? '' }}</p>
        <p>{{ auth()->user()->company->phone ?? '' }} | {{ auth()->user()->company->email ?? '' }}</p>
        <h2>Loans Report</h2>
        <p class="date">Generated: {{ now()->format('Y-m-d H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Member</th>
                <th>Product</th>
                <th>Amount</th>
                <th>Balance</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($loans as $loan)
            <tr>
                <td>{{ $loan->member->first_name }} {{ $loan->member->last_name }}</td>
                <td>{{ $loan->loanProduct->name }}</td>
                <td>{{ number_format($loan->amount, 2) }}</td>
                <td>{{ number_format($loan->balance, 2) }}</td>
                <td>{{ ucfirst($loan->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        {{ auth()->user()->company->name }} - Confidential Report
    </div>
</body>
</html>