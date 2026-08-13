@extends('emails.layout')

@section('content')
<h2>✅ Congratulations, {{ $name }}!</h2>

<p>We are pleased to inform you that your loan application has been <strong>approved</strong>! 🎉</p>

<div class="highlight">
    <strong>📋 Loan Details:</strong>
    <ul>
        <li><strong>Amount:</strong> UGX {{ number_format($loanAmount, 2) }}</li>
        <li><strong>Status:</strong> <span style="color: #16a34a;">Approved ✅</span></li>
        <li><strong>Next Step:</strong> Funds will be disbursed to your account</li>
    </ul>
</div>

<p>The funds will be disbursed to your account shortly. You will receive a confirmation once the disbursement is complete.</p>

<p style="text-align: center;">
    <a href="{{ url('/dashboard') }}" class="button">📊 View Your Loans</a>
</p>

<p>Thank you for choosing TBA Finance Cloud!</p>

<p>Best regards,<br>
<strong>TBA Finance Cloud Team</strong></p>
@endsection