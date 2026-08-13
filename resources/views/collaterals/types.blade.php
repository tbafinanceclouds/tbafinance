@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">🏷️ Collateral Types</h1>
        <a href="{{ route('collaterals.types.create') }}" 
           class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            + Add Type
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
        @forelse($types as $type)
            <div class="bg-white rounded shadow p-4 hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-white" 
                             style="background: {{ $type->color }}">
                            <i class="fas {{ $type->icon }}"></i>
                        </div>
                        <div class="ml-3">
                            <p class="font-bold">{{ $type->name }}</p>
                            <p class="text-sm text-gray-500">{{ $type->collaterals_count }} collateral items</p>
                        </div>
                    </div>
                    <div class="flex space-x-1">
                        <a href="{{ route('collaterals.types.edit', $type) }}" 
                           class="text-blue-500 hover:text-blue-700 p-1">✏️</a>
                        <form action="{{ route('collaterals.types.destroy', $type) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 p-1"
                                    onclick="return confirm('Delete this type?')">🗑️</button>
                        </form>
                    </div>
                </div>
                @if($type->description)
                    <p class="text-sm text-gray-600 mt-2">{{ $type->description }}</p>
                @endif
                <div class="mt-2">
                    @if($type->is_active)
                        <span class="text-xs text-green-600">✅ Active</span>
                    @else
                        <span class="text-xs text-red-600">❌ Inactive</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-8 text-gray-500">
                No collateral types found.
                <a href="{{ route('collaterals.types.create') }}" class="text-blue-500 hover:underline">
                    Create your first type
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection