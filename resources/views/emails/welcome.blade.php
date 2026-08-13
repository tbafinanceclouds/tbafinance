@extends('emails.layout')

@section('content')
<h2>🎉 Welcome to TBA Finance Cloud, {{ $name }}!</h2>

<p>We're excited to have you on board! Your account has been successfully created and you're now ready to take control of your financial management.</p>

<div class="highlight">
    <strong>🔑 Your Account Details:</strong>
    <ul>
        <li>✅ Account created successfully</li>
        <li>✅ You can now log in to your dashboard</li>
        <li>✅ Start managing members, savings, and loans</li>
    </ul>
</div>

<h3>What you can do with TBA Finance Cloud:</h3>
<ul>
    <li>📊 <strong>Dashboard</strong> - Get real-time financial insights</li>
    <li>👥 <strong>Members</strong> - Add and track all your members</li>
    <li>💰 <strong>Savings</strong> - Manage member savings accounts with ease</li>
    <li>🏦 <strong>Loans</strong> - Process loan applications and repayments</li>
    <li>📈 <strong>Reports</strong> - Generate professional financial reports</li>
</ul>

<p style="text-align: center;">
    <a href="{{ url('/login') }}" class="button">🔐 Login to Your Account</a>
</p>

<p>If you have any questions, feel free to reach out to our support team.</p>

<p>Best regards,<br>
<strong>TBA Finance Cloud Team</strong></p>
@endsection