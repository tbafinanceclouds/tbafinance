@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">📂 Document Categories</h1>
        <a href="{{ route('documents.categories.create') }}" 
           class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            + Add Category
        </a>
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

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @forelse($categories as $category)
            <div class="bg-white rounded shadow p-4 hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-white" 
                             style="background: {{ $category->color }}">
                            <i class="fas {{ $category->icon }}"></i>
                        </div>
                        <div class="ml-3">
                            <p class="font-bold">{{ $category->name }}</p>
                            <p class="text-sm text-gray-500">{{ $category->documents_count }} documents</p>
                        </div>
                    </div>
                    <div class="flex space-x-1">
                        <a href="{{ route('documents.categories.edit', $category) }}" 
                           class="text-blue-500 hover:text-blue-700 p-1">
                            ✏️
                        </a>
                        <form action="{{ route('documents.categories.destroy', $category) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 p-1"
                                    onclick="return confirm('Delete this category?')">
                                🗑️
                            </button>
                        </form>
                    </div>
                </div>
                <div class="mt-2">
                    @if($category->is_active)
                        <span class="text-xs text-green-600">✅ Active</span>
                    @else
                        <span class="text-xs text-red-600">❌ Inactive</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-8 text-gray-500">
                No categories found. 
                <a href="{{ route('documents.categories.create') }}" class="text-blue-500 hover:underline">
                    Create your first category
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection