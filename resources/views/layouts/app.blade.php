<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ auth()->user()->company->name ?? 'TBA Finance Cloud' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-800 text-white min-h-screen">
            <div class="p-4 border-b border-gray-700 text-center">
                @if(auth()->user()->company->logo)
                    <img src="{{ asset('storage/' . auth()->user()->company->logo) }}" 
                         alt="{{ auth()->user()->company->name }}" 
                         class="h-10 w-10 object-cover rounded-full mx-auto mb-1">
                @endif
                <div class="text-xl font-bold">
                    {{ auth()->user()->company->name ?? 'TBA Finance' }}
                </div>
            </div>
            
            <nav class="mt-4">
                <!-- Dashboard -->
                <a href="/dashboard" class="block px-4 py-3 hover:bg-gray-700 {{ request()->is('dashboard') ? 'bg-gray-700' : '' }}">
                    📊 Dashboard
                </a>
                
                <!-- Members -->
                <a href="/members" class="block px-4 py-3 hover:bg-gray-700 {{ request()->is('members*') ? 'bg-gray-700' : '' }}">
                    👥 Members
                </a>

                <!-- Savings -->
                <a href="{{ route('savings.accounts') }}" class="block px-4 py-3 hover:bg-gray-700 {{ request()->is('savings*') ? 'bg-gray-700' : '' }}">
                    💰 Savings
                </a>

                <!-- Savings Products -->
                <a href="{{ route('savings.products') }}" class="block px-4 py-3 hover:bg-gray-700 {{ request()->is('savings/products*') ? 'bg-gray-700' : '' }}">
                    📦 Products
                </a>

                <!-- Loans -->
                <a href="{{ route('loans.products') }}" class="block px-4 py-3 hover:bg-gray-700 {{ request()->is('loans/products*') ? 'bg-gray-700' : '' }}">
                    📋 Loan Products
                </a>
                <a href="{{ route('loans.applications') }}" class="block px-4 py-3 hover:bg-gray-700 {{ request()->is('loans*') ? 'bg-gray-700' : '' }}">
                    💳 Loans
                </a>
                <!-- Guarantors -->
<a href="{{ route('guarantors.index') }}" class="block px-4 py-3 hover:bg-gray-700 {{ request()->is('guarantors*') ? 'bg-gray-700' : '' }}">
    👥 Guarantors
</a>
                <!-- Shares -->
<a href="{{ route('shares.products') }}" class="block px-4 py-3 hover:bg-gray-700 {{ request()->is('shares*') ? 'bg-gray-700' : '' }}">
    📈 Shares
</a>

<!-- Accounting -->
<a href="{{ route('accounting.chart') }}" class="block px-4 py-3 hover:bg-gray-700 {{ request()->is('accounting*') ? 'bg-gray-700' : '' }}">
    📊 Accounting
</a>

<!-- Cashbook -->
<a href="{{ route('cashbook.index') }}" class="block px-4 py-3 hover:bg-gray-700 {{ request()->is('cashbook*') ? 'bg-gray-700' : '' }}">
    💰 Cashbook
</a>

                <!-- Reports -->
                <a href="{{ route('reports.index') }}" class="block px-4 py-3 hover:bg-gray-700 {{ request()->is('reports*') ? 'bg-gray-700' : '' }}">
                    📊 Reports
                </a>
                
                <!-- Documents -->
<a href="{{ route('documents.index') }}" class="block px-4 py-3 hover:bg-gray-700 {{ request()->is('documents*') && !request()->is('documents/categories*') ? 'bg-gray-700' : '' }}">
    📄 Documents
</a>
<a href="{{ route('documents.categories') }}" class="block px-4 py-3 hover:bg-gray-700 {{ request()->is('documents/categories*') ? 'bg-gray-700' : '' }}">
    📂 Categories
</a>

<!-- Super Admin Section -->
@if(auth()->user()->is_super_admin)       
                
                <div class="mt-4 border-t border-gray-700 pt-4">
                    <p class="px-4 text-xs text-gray-400 uppercase">Admin</p>
                    <a href="/admin/dashboard" class="block px-4 py-3 hover:bg-gray-700 {{ request()->is('admin*') ? 'bg-gray-700' : '' }}">
                        ⚙️ Admin Dashboard
                    </a>
                    <a href="/admin/companies" class="block px-4 py-3 hover:bg-gray-700 {{ request()->is('admin/companies') ? 'bg-gray-700' : '' }}">
                        🏢 Companies
                    </a>
                    <a href="{{ route('audit.index') }}" class="block px-4 py-3 hover:bg-gray-700 {{ request()->is('audit*') ? 'bg-gray-700' : '' }}">
                        📝 Audit Logs
                    </a>
                    
                @endif
                
                <!-- Settings - MOVED TO BOTTOM -->
                <div class="mt-4 border-t border-gray-700 pt-4">
                    <a href="{{ route('settings.index') }}" class="block px-4 py-3 hover:bg-gray-700 {{ request()->is('settings*') ? 'bg-gray-700' : '' }}">
                        ⚙️ Settings
                    </a>
                </div>

<!-- Subscription -->
<a href="{{ route('subscription.index') }}" class="block px-4 py-3 hover:bg-gray-700 {{ request()->is('subscription*') ? 'bg-gray-700' : '' }}">
    💳 Subscription
</a>
                
                <!-- Logout -->
                <div class="mt-4 border-t border-gray-700 pt-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-3 hover:bg-gray-700">
                            🚪 Logout
                        </button>
                    </form>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 bg-gray-100">
            <div class="p-6">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>