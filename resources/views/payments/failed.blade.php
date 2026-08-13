@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 max-w-2xl">
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        ❌ Payment Failed!
    </div>

    <div class="bg-white rounded shadow p-6">
        <p class="text-gray-700">{{ $message ?? 'Payment could not be processed.' }}</p>
        <a href="{{ route('payments.create') }}" class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Try Again
        </a>
    </div>
</div>
@endsection