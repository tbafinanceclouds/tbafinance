@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 max-w-2xl">
    <div class="flex items-center mb-6">
        <a href="{{ route('documents.show', $document) }}" class="text-blue-500 mr-4">← Back</a>
        <h1 class="text-2xl font-bold">Edit Document</h1>
    </div>

    <div class="bg-white rounded shadow p-6">
        <form action="{{ route('documents.update', $document) }}" method="POST">
            @csrf @method('PUT')

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Category *</label>
                    <select name="category_id" class="w-full border rounded px-3 py-2 @error('category_id') border-red-500 @enderror" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $document->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Document Name *</label>
                    <input type="text" name="name" value="{{ old('name', $document->name) }}" 
                           class="w-full border rounded px-3 py-2 @error('name') border-red-500 @enderror" required>
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Description</label>
                    <textarea name="description" rows="3" 
                              class="w-full border rounded px-3 py-2 @error('description') border-red-500 @enderror">{{ old('description', $document->description) }}</textarea>
                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Expiry Date</label>
                    <input type="date" name="expires_date" value="{{ old('expires_date', optional($document->expires_date)->format('Y-m-d')) }}" 
                           class="w-full border rounded px-3 py-2 @error('expires_date') border-red-500 @enderror">
                    @error('expires_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="bg-gray-50 p-4 rounded">
                    <p class="text-sm text-gray-500">File: <span class="font-medium">{{ $document->file_name }}</span></p>
                    <p class="text-sm text-gray-500">Size: <span class="font-medium">{{ $document->file_size_formatted }}</span></p>
                    <p class="text-sm text-gray-500">Uploaded: <span class="font-medium">{{ $document->upload_date->format('Y-m-d') }}</span></p>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                    Update Document
                </button>
            </div>
        </form>
    </div>
</div>
@endsection