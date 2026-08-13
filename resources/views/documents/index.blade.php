@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">📄 Documents</h1>
        <div class="flex space-x-2">
            <a href="{{ route('documents.categories') }}" 
               class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                📂 Categories
            </a>
            <a href="{{ route('documents.create') }}" 
               class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                + Upload Document
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded shadow p-4">
            <p class="text-sm text-gray-500">Total Documents</p>
            <p class="text-2xl font-bold">{{ $documents->total() }}</p>
        </div>
        <div class="bg-white rounded shadow p-4">
            <p class="text-sm text-gray-500">Verified</p>
            <p class="text-2xl font-bold text-green-600">
                {{ $documents->where('is_verified', true)->count() }}
            </p>
        </div>
        <div class="bg-white rounded shadow p-4">
            <p class="text-sm text-gray-500">Unverified</p>
            <p class="text-2xl font-bold text-yellow-600">
                {{ $documents->where('is_verified', false)->count() }}
            </p>
        </div>
        <div class="bg-white rounded shadow p-4">
            <p class="text-sm text-gray-500">Expired</p>
            <p class="text-2xl font-bold text-red-600">
                {{ $documents->filter(function($doc) { return $doc->is_expired; })->count() }}
            </p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded shadow p-4 mb-6">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-sm font-medium mb-1">Category</label>
                <select name="category_id" class="border rounded px-3 py-2">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Related To</label>
                <select name="related_type" class="border rounded px-3 py-2">
                    <option value="">All</option>
                    <option value="member" {{ request('related_type') == 'member' ? 'selected' : '' }}>Member</option>
                    <option value="loan" {{ request('related_type') == 'loan' ? 'selected' : '' }}>Loan</option>
                    <option value="guarantor" {{ request('related_type') == 'guarantor' ? 'selected' : '' }}>Guarantor</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Status</label>
                <select name="status" class="border rounded px-3 py-2">
                    <option value="">All</option>
                    <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verified</option>
                    <option value="unverified" {{ request('status') == 'unverified' ? 'selected' : '' }}>Unverified</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Search documents..."
                       class="border rounded px-3 py-2 w-48">
            </div>
            <div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Filter
                </button>
                <a href="{{ route('documents.index') }}" class="ml-2 bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Documents Table -->
    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Related To</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Size</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Uploaded</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($documents as $document)
                <tr>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <i class="fas {{ $document->icon }} text-gray-500 mr-2"></i>
                            <span>{{ $document->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded" style="background: {{ $document->category->color }}20; color: {{ $document->category->color }}">
                            {{ $document->category->name }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $type = class_basename($document->related_type);
                        @endphp
                        <span class="text-sm">
                            {{ $type }} #{{ $document->related_id }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm">{{ $document->file_size_formatted }}</td>
                    <td class="px-6 py-4">
                        @if($document->is_verified)
                            <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">✅ Verified</span>
                        @else
                            <span class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-800">⏳ Pending</span>
                        @endif
                        @if($document->is_expired)
                            <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-800 ml-1">Expired</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm">{{ $document->created_at->diffForHumans() }}</td>
                    <td class="px-6 py-4">
                        <a href="{{ route('documents.show', $document) }}" class="text-blue-500 hover:text-blue-700 mr-2">View</a>
                        <a href="{{ route('documents.download', $document) }}" class="text-green-500 hover:text-green-700 mr-2">Download</a>
                        <form action="{{ route('documents.destroy', $document) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700" 
                                    onclick="return confirm('Delete this document?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        No documents found. 
                        <a href="{{ route('documents.create') }}" class="text-blue-500 hover:underline">
                            Upload your first document
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $documents->links() }}
    </div>
</div>
@endsection