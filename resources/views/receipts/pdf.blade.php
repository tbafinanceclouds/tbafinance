<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt - {{ $receipt->receipt_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            background: white;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 24px;
            margin: 0;
            color: #1a56db;
        }
        .header p {
            margin: 3px 0;
            color: #666;
        }
        .receipt-title {
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }
        .details {
            margin-bottom: 30px;
        }
        .details table {
            width: 100%;
        }
        .details td {
            padding: 5px 0;
        }
        .details .label {
            color: #666;
            width: 150px;
        }
        .transaction-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .transaction-table th {
            background: #f2f2f2;
            padding: 10px;
            text-align: left;
        }
        .transaction-table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .total {
            text-align: right;
            border-top: 2px solid #333;
            padding-top: 15px;
            margin-top: 15px;
            font-size: 18px;
        }
        .footer {
            text-align: center;
            border-top: 1px solid #ddd;
            padding-top: 20px;
            margin-top: 40px;
            color: #999;
            font-size: 12px;
        }
        .logo {
            max-height: 60px;
            margin-bottom: 10px;
        }
        .company-name {
            font-size: 20px;
            font-weight: bold;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="header">
        @if($receipt->company->logo)
            <img src="{{ public_path('storage/' . $receipt->company->logo) }}" class="logo" alt="{{ $receipt->company->name }}">
        @endif
        <p class="company-name">{{ $receipt->company->name }}</p>
        <p>{{ $receipt->company->address }}</p>
        <p>Phone: {{ $receipt->company->phone }} | Email: {{ $receipt->company->email }}</p>
        <p class="receipt-title">RECEIPT</p>
    </div>

    <div class="details">
        <table>
            <tr>
                <td class="label">Receipt Number:</td>
                <td><strong>{{ $receipt->receipt_number }}</strong></td>
            </tr>
            <tr>
                <td class="label">Date:</td>
                <td>{{ $receipt->receipt_date->format('d M Y') }}</td>
            </tr>
            <tr>
                <td class="label">Member:</td>
                <td><strong>{{ $receipt->member->first_name }} {{ $receipt->member->last_name }}</strong></td>
            </tr>
            <tr>
                <td class="label">Member ID:</td>
                <td>#{{ $receipt->member->id }}</td>
            </tr>
        </table>
    </div>

    <table class="transaction-table">
        <thead>
            <tr>
                <th>Description</th>
                <th>Type</th>
                <th style="text-align: right;">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $receipt->description }}</td>
                <td>{{ $receipt->type_label }}</td>
                <td style="text-align: right; font-weight: bold;">{{ number_format($receipt->amount, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="total">
        <p>Total Amount: <strong>{{ number_format($receipt->amount, 2) }}</strong></p>
        <p style="font-size: 14px; color: #666; margin: 0;">Payment Method: {{ ucfirst($receipt->payment_method) }}</p>
        @if($receipt->reference)
            <p style="font-size: 14px; color: #666; margin: 0;">Reference: {{ $receipt->reference }}</p>
        @endif
    </div>

    <div class="footer">
        <p>Thank you for your business!</p>
        <p>This is a computer-generated receipt. No signature required.</p>
        <p>Generated on: {{ now()->format('d M Y H:i') }}</p>
    </div>
</body>
</html>