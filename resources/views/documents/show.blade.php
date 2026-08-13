@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 max-w-2xl">
    <div class="flex items-center mb-6">
        <a href="{{ route('documents.index') }}" class="text-blue-500 mr-4">← Back</a>
        <h1 class="text-2xl font-bold">Document Details</h1>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded shadow p-6">
        <!-- Preview Section -->
        <div class="mb-6 border rounded p-4 text-center bg-gray-50">
            @if($document->is_image)
                <img src="{{ $document->file_url }}" alt="{{ $document->name }}" class="max-h-64 mx-auto">
            @elseif($document->is_pdf)
                <i class="fas fa-file-pdf text-6xl text-red-500"></i>
                <p class="mt-2 text-gray-600">PDF Document</p>
            @else
                <i class="fas {{ $document->icon }} text-6xl text-gray-500"></i>
                <p class="mt-2 text-gray-600">{{ $document->file_name }}</p>
            @endif
        </div>

        <!-- Details Grid -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-500">Name</p>
                <p class="font-medium">{{ $document->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Category</p>
                <p class="font-medium">
                    <span class="px-2 py-1 text-xs rounded" style="background: {{ $document->category->color }}20; color: {{ $document->category->color }}">
                        {{ $document->category->name }}
                    </span>
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Related To</p>
                <p class="font-medium">
                    {{ class_basename($document->related_type) }} #{{ $document->related_id }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Status</p>
                <p class="font-medium">
                    @if($document->is_verified)
                        <span class="text-green-600">✅ Verified</span>
                    @else
                        <span class="text-yellow-600">⏳ Pending Verification</span>
                    @endif
                    @if($document->is_expired)
                        <span class="text-red-600 ml-2">🔴 Expired</span>
                    @endif
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500">File Name</p>
                <p class="font-medium text-sm">{{ $document->file_name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">File Size</p>
                <p class="font-medium">{{ $document->file_size_formatted }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Uploaded By</p>
                <p class="font-medium">{{ $document->uploader->name ?? 'Unknown' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Upload Date</p>
                <p class="font-medium">{{ $document->upload_date->format('Y-m-d H:i') }}</p>
            </div>
            @if($document->expires_date)
                <div>
                    <p class="text-sm text-gray-500">Expiry Date</p>
                    <p class="font-medium">{{ $document->expires_date->format('Y-m-d') }}</p>
                </div>
            @endif
            @if($document->is_verified && $document->verified_by)
                <div>
                    <p class="text-sm text-gray-500">Verified By</p>
                    <p class="font-medium">{{ $document->verifier->name ?? 'Unknown' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Verified At</p>
                    <p class="font-medium">{{ $document->verified_at->format('Y-m-d H:i') }}</p>
                </div>
            @endif
            @if($document->description)
                <div class="col-span-2">
                    <p class="text-sm text-gray-500">Description</p>
                    <p class="font-medium">{{ $document->description }}</p>
                </div>
            @endif
        </div>

        <!-- Actions -->
        <div class="mt-6 flex flex-wrap gap-2 border-t pt-6">
            <a href="{{ route('documents.download', $document) }}" 
               class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                ⬇️ Download
            </a>
            
            @if(!$document->is_verified)
                <form action="{{ route('documents.verify', $document) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
                            onclick="return confirm('Verify this document?')">
                        ✅ Verify
                    </button>
                </form>
            @else
                <form action="{{ route('documents.unverify', $document) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600"
                            onclick="return confirm('Unverify this document?')">
                        🔄 Unverify
                    </button>
                </form>
            @endif

            <a href="{{ route('documents.edit', $document) }}" 
               class="bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600">
                ✏️ Edit
            </a>

            <form action="{{ route('documents.destroy', $document) }}" method="POST" class="inline">
                @csrf @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600"
                        onclick="return confirm('Delete this document?')">
                    🗑️ Delete
                </button>
            </form>
        </div>
    </div>
</div>
@endsection