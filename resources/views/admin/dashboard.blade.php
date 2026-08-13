@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">⚙️ Super Admin Dashboard</h1>
            <p class="text-sm text-gray-500">Manage all SACCOs and system settings</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.companies') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition flex items-center gap-2">
                📋 All Companies
            </a>
            <a href="{{ route('admin.companies.create') }}" 
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition flex items-center gap-2">
                ➕ Add Company
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded mb-4 flex items-center justify-between">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">✕</button>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Companies</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalCompanies ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-xl">
                    🏢
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Active Companies</p>
                    <p class="text-3xl font-bold text-green-600">{{ $activeCompanies ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center text-green-600 text-xl">
                    ✅
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-500 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Users</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalUsers ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-600 text-xl">
                    👤
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-red-500 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Expired Companies</p>
                    <p class="text-3xl font-bold text-red-600">{{ $expiredCompanies ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center text-red-600 text-xl">
                    ⏰
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">🚀 Quick Actions</h3>
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('admin.companies') }}" 
                   class="bg-blue-50 hover:bg-blue-100 text-blue-700 p-3 rounded-lg text-center transition border border-blue-200">
                    📋 All Companies
                </a>
                <a href="{{ route('admin.companies.create') }}" 
                   class="bg-green-50 hover:bg-green-100 text-green-700 p-3 rounded-lg text-center transition border border-green-200">
                    ➕ Add Company
                </a>
                <a href="{{ route('audit.index') }}" 
                   class="bg-purple-50 hover:bg-purple-100 text-purple-700 p-3 rounded-lg text-center transition border border-purple-200">
                    📝 Audit Logs
                </a>
                <a href="{{ route('subscription.admin') }}" 
                   class="bg-yellow-50 hover:bg-yellow-100 text-yellow-700 p-3 rounded-lg text-center transition border border-yellow-200">
                    💳 Subscription Plans
                </a>
            </div>
        </div>

        <!-- System Info -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">⚙️ System Information</h3>
            <div class="space-y-2">
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-500">Laravel Version</span>
                    <span class="font-medium text-gray-800">{{ app()->version() }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-500">PHP Version</span>
                    <span class="font-medium text-gray-800">{{ phpversion() }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-500">Environment</span>
                    <span class="font-medium text-gray-800">{{ app()->environment() }}</span>
                </div>
                <div class="flex justify-between items-center py-2">
                    <span class="text-gray-500">Database</span>
                    <span class="font-medium text-gray-800">MySQL</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Companies -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-800">📋 Recent Companies</h3>
            <a href="{{ route('admin.companies') }}" class="text-blue-600 hover:text-blue-800 text-sm">View All →</a>
        </div>
        @if(isset($recentCompanies) && $recentCompanies->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-3 px-4 text-sm font-medium text-gray-500">Company</th>
                            <th class="text-left py-3 px-4 text-sm font-medium text-gray-500">Email</th>
                            <th class="text-left py-3 px-4 text-sm font-medium text-gray-500">Plan</th>
                            <th class="text-left py-3 px-4 text-sm font-medium text-gray-500">Status</th>
                            <th class="text-left py-3 px-4 text-sm font-medium text-gray-500">Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentCompanies as $company)
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                            <td class="py-3 px-4">
                                <span class="font-medium text-gray-800">{{ $company->name }}</span>
                            </td>
                            <td class="py-3 px-4 text-gray-600">{{ $company->email }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">
                                    {{ $company->plan_name }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    @if($company->is_active && $company->isSubscriptionActive())
                                        bg-green-100 text-green-700
                                    @elseif(!$company->is_active)
                                        bg-red-100 text-red-700
                                    @else
                                        bg-yellow-100 text-yellow-700
                                    @endif">
                                    @if($company->is_active && $company->isSubscriptionActive())
                                        ✅ Active
                                    @elseif(!$company->is_active)
                                        ⛔ Suspended
                                    @else
                                        ⏰ Expired
                                    @endif
                                </span>
                            </td>
                            <td class="py-3 px-4 text-gray-500">{{ $company->created_at->format('M d, Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                <p>No companies found.</p>
                <a href="{{ route('admin.companies.create') }}" class="text-blue-600 hover:text-blue-800">Create your first company →</a>
            </div>
        @endif
    </div>

    <!-- Subscription Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
        <div class="bg-white rounded-xl shadow-md p-6">
            <h4 class="font-bold text-gray-800">📊 Subscription Overview</h4>
            <div class="mt-4 space-y-2">
                <div class="flex justify-between">
                    <span class="text-gray-500">Starter</span>
                    <span class="font-medium">{{ $starterCount ?? 0 }} companies</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Professional</span>
                    <span class="font-medium">{{ $professionalCount ?? 0 }} companies</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Enterprise</span>
                    <span class="font-medium">{{ $enterpriseCount ?? 0 }} companies</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 col-span-2">
            <h4 class="font-bold text-gray-800">💡 Quick Tips</h4>
            <ul class="mt-4 space-y-2 text-sm text-gray-600">
                <li class="flex items-start gap-2">
                    <span class="text-blue-500">•</span>
                    <span>Use <strong>Suspend</strong> instead of Delete to keep company data safe</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-blue-500">•</span>
                    <span>Check <strong>Audit Logs</strong> to track all system activities</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-blue-500">•</span>
                    <span>Manage subscription plans at <strong>Subscription → Admin</strong></span>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection