@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">🏢 Companies</h1>
            <p class="text-sm text-gray-500">Manage all SACCOs in the system</p>
        </div>
        <a href="{{ route('admin.companies.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Company
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded mb-4 flex items-center justify-between">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">✕</button>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded mb-4 flex items-center justify-between">
            <span>{{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" class="text-red-700 hover:text-red-900">✕</button>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
            <p class="text-sm text-gray-500">Total Companies</p>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
            <p class="text-sm text-gray-500">Active</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['active'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-yellow-500">
            <p class="text-sm text-gray-500">Inactive</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $stats['inactive'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-red-500">
            <p class="text-sm text-gray-500">Expired</p>
            <p class="text-2xl font-bold text-red-600">{{ $stats['expired'] ?? 0 }}</p>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Search by name or email..."
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                    <option value="">All</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
                    🔍 Filter
                </button>
                <a href="{{ route('admin.companies') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg transition">
                    🔄 Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Companies Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($companies as $company)
        <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden border border-gray-100">
            <!-- Company Header -->
            <div class="p-5 border-b border-gray-100">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold text-xl">
                            {{ substr($company->name, 0, 2) }}
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800 text-lg">{{ $company->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $company->email }}</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full
                        @if($company->is_active && $company->subscription_expiry > now())
                            bg-green-100 text-green-700
                        @elseif(!$company->is_active)
                            bg-red-100 text-red-700
                        @else
                            bg-yellow-100 text-yellow-700
                        @endif">
                        @if($company->is_active && $company->subscription_expiry > now())
                            ✅ Active
                        @elseif(!$company->is_active)
                            ⛔ Suspended
                        @else
                            ⏰ Expired
                        @endif
                    </span>
                </div>
            </div>

            <!-- Company Details -->
            <div class="p-5 space-y-2">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">📧 Email</span>
                    <span class="font-medium text-gray-700">{{ $company->email }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">📱 Phone</span>
                    <span class="font-medium text-gray-700">{{ $company->phone ?? 'N/A' }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">💳 Plan</span>
                    <span class="font-medium text-gray-700">{{ $company->subscription_plan ?? 'None' }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">📅 Expiry</span>
                    <span class="font-medium text-gray-700 {{ $company->subscription_expiry < now() ? 'text-red-600' : '' }}">
                        {{ $company->subscription_expiry ? \Carbon\Carbon::parse($company->subscription_expiry)->format('M d, Y') : 'N/A' }}
                    </span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">👥 Members</span>
                    <span class="font-medium text-gray-700">{{ $company->members_count ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">📅 Joined</span>
                    <span class="font-medium text-gray-700">{{ $company->created_at->format('M d, Y') }}</span>
                </div>
            </div>

            <!-- Actions -->
            <div class="p-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between gap-2">
                <div class="flex gap-2">
                    <a href="{{ route('admin.companies.edit', $company) }}" 
                       class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-lg text-sm transition flex items-center gap-1">
                        ✏️ Edit
                    </a>
                    <form action="{{ route('admin.companies.toggle', $company) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                class="px-3 py-1.5 rounded-lg text-sm transition flex items-center gap-1
                                {{ $company->is_active ? 'bg-yellow-500 hover:bg-yellow-600 text-white' : 'bg-green-500 hover:bg-green-600 text-white' }}">
                            {{ $company->is_active ? '⛔ Suspend' : '✅ Activate' }}
                        </button>
                    </form>
                </div>
                <form action="{{ route('admin.companies.destroy', $company) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-sm transition flex items-center gap-1"
                            onclick="return confirm('⚠️ Delete this company? All data will be permanently removed!')">
                        🗑️ Delete
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12">
            <div class="text-6xl mb-4">🏢</div>
            <h3 class="text-xl font-semibold text-gray-700">No Companies Found</h3>
            <p class="text-gray-500 mt-2">Get started by creating your first company.</p>
            <a href="{{ route('admin.companies.create') }}" class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg transition">
                + Add Company
            </a>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $companies->links() }}
    </div>
</div>
@endsection