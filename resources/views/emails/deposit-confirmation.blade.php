@extends('emails.layout')

@section('content')
<h2>💰 Deposit Confirmed!</h2>

<p>Dear {{ $name }},</p>

<p>We are pleased to confirm that a deposit has been made to your savings account.</p>

<div class="highlight">
    <strong>📋 Transaction Details:</strong>
    <ul>
        <li><strong>Amount:</strong> UGX {{ number_format($amount, 2) }}</li>
        <li><strong>Status:</strong> <span style="color: #16a34a;">Completed ✅</span></li>
        <li><strong>Account:</strong> Your savings account has been updated</li>
    </ul>
</div>

<p>Your current balance has been updated accordingly.</p>

<p style="text-align: center;">
    <a href="{{ url('/savings/accounts') }}" class="button">💰 View Your Savings</a>
</p>

<p>Thank you for banking with TBA Finance Cloud!</p>

<p>Best regards,<br>
<strong>TBA Finance Cloud Team</strong></p>
@endsection