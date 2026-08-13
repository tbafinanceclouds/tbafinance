@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 max-w-2xl">
    <div class="flex items-center mb-6">
        <a href="{{ route('documents.categories') }}" class="text-blue-500 mr-4">← Back</a>
        <h1 class="text-2xl font-bold">Edit Category</h1>
    </div>

    <div class="bg-white rounded shadow p-6">
        <form action="{{ route('documents.categories.update', $category) }}" method="POST">
            @csrf @method('PUT')

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Category Name *</label>
                    <input type="text" name="name" value="{{ old('name', $category->name) }}" 
                           class="w-full border rounded px-3 py-2 @error('name') border-red-500 @enderror" required>
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Icon (Font Awesome)</label>
                    <input type="text" name="icon" value="{{ old('icon', $category->icon) }}" 
                           placeholder="fa-file"
                           class="w-full border rounded px-3 py-2 @error('icon') border-red-500 @enderror">
                    <p class="text-xs text-gray-500 mt-1">Enter Font Awesome icon class (e.g., fa-file, fa-id-card)</p>
                    @error('icon') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Color</label>
                    <input type="color" name="color" value="{{ old('color', $category->color) }}" 
                           class="w-full h-12 border rounded px-3 py-2 @error('color') border-red-500 @enderror">
                    @error('color') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }} 
                               class="mr-2">
                        <span class="text-sm font-medium">Active</span>
                    </label>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                    Update Category
                </button>
            </div>
        </form>
    </div>
</div>
@endsection