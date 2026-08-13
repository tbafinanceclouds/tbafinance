@extends('emails.layout')

@section('content')
<h2>⏰ Loan Repayment Reminder</h2>

<p>Dear {{ $name }},</p>

<p>This is a friendly reminder that your next loan installment is due soon.</p>

<div class="highlight">
    <strong>📋 Payment Details:</strong>
    <ul>
        <li><strong>Amount Due:</strong> UGX {{ number_format($amount, 2) }}</li>
        <li><strong>Due Date:</strong> {{ $dueDate }}</li>
        <li><strong>Status:</strong> <span style="color: #eab308;">Pending ⏳</span></li>
    </ul>
</div>

<p>Please ensure you make the payment on time to avoid any penalties.</p>

<p style="text-align: center;">
    <a href="{{ url('/loans') }}" class="button">🏦 Make a Payment</a>
</p>

<p>Thank you for your cooperation!</p>

<p>Best regards,<br>
<strong>TBA Finance Cloud Team</strong></p>
@endsection