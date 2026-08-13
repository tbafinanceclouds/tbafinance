<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ auth()->guard('company')->user()->name ?? 'TBA Finance' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-800 text-white min-h-screen">
            <div class="p-4 border-b border-gray-700 text-center">
                <div class="text-xl font-bold">
                    {{ auth()->guard('company')->user()->name ?? 'TBA Finance' }}
                </div>
                <div class="text-xs text-gray-400">Company Dashboard</div>
            </div>

            <nav class="mt-4">
                <a href="{{ route('company.dashboard') }}" 
                   class="block px-4 py-3 hover:bg-gray-700 {{ request()->is('company/dashboard') ? 'bg-gray-700' : '' }}">
                    📊 Dashboard
                </a>
                <a href="{{ route('company.members') }}" 
                   class="block px-4 py-3 hover:bg-gray-700 {{ request()->is('company/members*') ? 'bg-gray-700' : '' }}">
                    👥 Members
                </a>
                <a href="{{ route('company.savings') }}" 
                   class="block px-4 py-3 hover:bg-gray-700 {{ request()->is('company/savings*') ? 'bg-gray-700' : '' }}">
                    💰 Savings
                </a>
                <a href="{{ route('company.loans') }}" 
                   class="block px-4 py-3 hover:bg-gray-700 {{ request()->is('company/loans*') ? 'bg-gray-700' : '' }}">
                    💳 Loans
                </a>
                <a href="{{ route('company.reports') }}" 
                   class="block px-4 py-3 hover:bg-gray-700 {{ request()->is('company/reports*') ? 'bg-gray-700' : '' }}">
                    📊 Reports
                </a>
                <a href="{{ route('company.settings') }}" 
                   class="block px-4 py-3 hover:bg-gray-700 {{ request()->is('company/settings*') ? 'bg-gray-700' : '' }}">
                    ⚙️ Settings
                </a>
            </nav>

            <div class="absolute bottom-0 w-64 p-4 border-t border-gray-700">
                <form action="{{ route('company.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-left text-gray-400 hover:text-white">
                        🚪 Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1">
            <!-- Top Bar -->
            <div class="bg-white shadow px-6 py-3 flex justify-between items-center">
                <h1 class="text-xl font-bold text-gray-800">@yield('title', 'Dashboard')</h1>
                <div class="text-sm text-gray-500">
                    {{ auth()->guard('company')->user()->name }} | 
                    Plan: {{ auth()->guard('company')->user()->plan_name }}
                </div>
            </div>

            <!-- Content -->
            <div class="p-6">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>