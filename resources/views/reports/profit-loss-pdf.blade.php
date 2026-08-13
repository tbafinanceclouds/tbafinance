<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Profit & Loss Report</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 8px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f2f2f2; }
        .income { color: green; }
        .expense { color: red; }
        .profit { color: green; }
        .loss { color: red; }
        .total { font-weight: bold; }
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
        <h2>Profit & Loss Statement</h2>
        <p class="date">Generated: {{ now()->format('Y-m-d H:i') }}</p>
    </div>

    <!-- Report Content -->
    <table>
        <tr>
            <th colspan="2" style="background-color: #d4edda;">Income</th>
        </tr>
        <tr>
            <td>Loan Interest</td>
            <td>{{ number_format($loanInterest ?? 0, 2) }}</td>
        </tr>
        <tr>
            <td>Processing Fees</td>
            <td>{{ number_format($processingFees ?? 0, 2) }}</td>
        </tr>
        <tr>
            <td>Penalties</td>
            <td>{{ number_format($penalties ?? 0, 2) }}</td>
        </tr>
        <tr style="font-weight: bold;">
            <td>Total Income</td>
            <td style="color: green;">{{ number_format($totalIncome ?? 0, 2) }}</td>
        </tr>
        <tr>
            <th colspan="2" style="background-color: #f8d7da;">Expenses</th>
        </tr>
        <tr>
            <td>Savings Interest</td>
            <td>{{ number_format($savingsInterest ?? 0, 2) }}</td>
        </tr>
        <tr>
            <td>Member Withdrawals</td>
            <td>{{ number_format($withdrawals ?? 0, 2) }}</td>
        </tr>
        <tr style="font-weight: bold;">
            <td>Total Expenses</td>
            <td style="color: red;">{{ number_format($totalExpenses ?? 0, 2) }}</td>
        </tr>
        <tr>
            <th colspan="2" style="background-color: #cce5ff;">Net Profit / Loss</th>
        </tr>
        <tr>
            <td style="font-weight: bold;">Net Profit/Loss</td>
            <td style="font-weight: bold; color: {{ ($netProfit ?? 0) >= 0 ? 'green' : 'red' }};">
                {{ number_format($netProfit ?? 0, 2) }}
            </td>
        </tr>
    </table>

    <div class="footer">
        {{ auth()->user()->company->name }} - Confidential Report
    </div>
</body>
</html>