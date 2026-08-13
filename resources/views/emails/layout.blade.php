<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'TBA Finance Cloud' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: #1a56db;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            color: #93b5e8;
            margin: 5px 0 0;
        }
        .content {
            padding: 30px 20px;
            color: #333333;
            line-height: 1.6;
        }
        .content h2 {
            color: #1a56db;
            margin-top: 0;
        }
        .button {
            display: inline-block;
            background: #1a56db;
            color: #ffffff !important;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .button:hover {
            background: #1e40af;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #6b7280;
            font-size: 12px;
            border-top: 1px solid #e5e7eb;
        }
        .footer a {
            color: #1a56db;
            text-decoration: none;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        ul li {
            padding: 5px 0;
        }
        .highlight {
            background: #f0f7ff;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #1a56db;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>🏦 TBA Finance Cloud</h1>
            <p>Your Trusted Financial Partner</p>
        </div>

        <!-- Content -->
        <div class="content">
            @yield('content')
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>
                <a href="{{ url('/') }}">TBA Finance Cloud</a> &bull;
                <a href="{{ url('/settings') }}">Settings</a> &bull;
                <a href="mailto:support@tbafinance.com">Support</a>
            </p>
            <p>&copy; {{ date('Y') }} TBA Finance Cloud. All rights reserved.</p>
            <p>This email was sent to you because you are registered with TBA Finance Cloud.</p>
        </div>
    </div>
</body>
</html>