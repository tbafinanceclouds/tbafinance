@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900">Choose Your Plan</h1>
        <p class="text-gray-600 mt-2">Select the perfect plan for your SACCO</p>
    </div>

    <!-- ✅ ADMIN BUTTON -->
    @if(auth()->user()->is_super_admin)
        <div class="text-right mb-4 max-w-6xl mx-auto">
            <a href="{{ route('subscription.admin') }}" 
               class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition inline-flex items-center">
                ⚙️ Manage Plans
            </a>
        </div>
    @endif

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 max-w-3xl mx-auto">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 max-w-3xl mx-auto">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
        @foreach($plans as $plan)
        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition border-2 
             {{ $company->subscription_plan == $plan->slug ? 'border-blue-500' : 'border-transparent' }}
             {{ $plan->is_popular ? 'ring-2 ring-yellow-400 relative' : '' }}">
            
            <!-- Popular Badge -->
            @if($plan->is_popular)
                <div class="bg-yellow-400 text-center py-1 text-sm font-bold text-black">
                    ⭐ MOST POPULAR
                </div>
            @endif
            
            <div class="p-6">
                <h2 class="text-2xl font-bold">{{ $plan->name }}</h2>
                <p class="text-gray-500 text-sm mt-1">{{ $plan->description }}</p>
                
                <!-- Price Display -->
                <div class="mt-4">
                    <span class="text-4xl font-bold">{{ $plan->formatted_price }}</span>
                    <span class="text-gray-500">/{{ $plan->billing_period }}</span>
                    
                    @if($plan->yearly_price)
                        <p class="text-sm text-green-600 mt-1">
                            Save {{ $plan->savings }}% with yearly billing
                            <span class="block text-gray-500 text-xs">UGX {{ number_format($plan->yearly_price, 0) }}/year</span>
                        </p>
                    @endif
                    
                    @if($plan->has_promo)
                        <p class="text-sm text-blue-600 mt-1">
                            🔥 Promo: {{ $plan->promo_discount }}% off!
                            @if($plan->promo_code)
                                <span class="block text-xs">Use code: <strong>{{ $plan->promo_code }}</strong></span>
                            @endif
                        </p>
                    @endif
                    
                    @if($plan->has_trial)
                        <p class="text-sm text-green-600 mt-1">
                            🎉 {{ $plan->trial_days }}-day free trial
                        </p>
                    @endif
                </div>

                <!-- Features -->
                <ul class="mt-6 space-y-3">
                    @foreach($plan->features_list as $feature)
                        <li class="flex items-center text-gray-700">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            {{ $feature }}
                        </li>
                    @endforeach
                </ul>

                <!-- Limits -->
                <div class="mt-4 pt-4 border-t">
                    @if($plan->max_members > 0)
                        <p class="text-sm text-gray-500">👥 Up to {{ number_format($plan->max_members) }} members</p>
                    @else
                        <p class="text-sm text-gray-500">👥 Unlimited members</p>
                    @endif
                    
                    @if($plan->max_users > 0)
                        <p class="text-sm text-gray-500">👤 {{ number_format($plan->max_users) }} user account(s)</p>
                    @else
                        <p class="text-sm text-gray-500">👤 Unlimited users</p>
                    @endif
                    
                    @if($plan->max_features > 0)
                        <p class="text-sm text-gray-500">📋 {{ number_format($plan->max_features) }} features included</p>
                    @else
                        <p class="text-sm text-gray-500">📋 Unlimited features</p>
                    @endif
                </div>

                <!-- Select Button -->
                <form action="{{ route('subscription.select') }}" method="POST" class="mt-6">
                    @csrf
                    <input type="hidden" name="plan" value="{{ $plan->slug }}">
                    <button type="submit" 
                            class="w-full py-3 px-4 rounded-lg font-semibold transition
                            {{ $company->subscription_plan == $plan->slug 
                                ? 'bg-green-500 text-white hover:bg-green-600' 
                                : 'bg-blue-600 text-white hover:bg-blue-700' }}">
                        {{ $company->subscription_plan == $plan->slug ? '✅ Current Plan' : 'Select Plan' }}
                    </button>
                </form>

                <!-- Promo Code Display -->
                @if($plan->has_promo && $plan->promo_code)
                    <div class="mt-3 text-center">
                        <span class="text-xs text-gray-500">Promo code: </span>
                        <span class="text-xs font-mono bg-gray-100 px-2 py-1 rounded">{{ $plan->promo_code }}</span>
                    </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <!-- Compare Plans Section -->
    <div class="max-w-6xl mx-auto mt-16">
        <h2 class="text-2xl font-bold text-center mb-6">Compare All Plans</h2>
        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Feature</th>
                        @foreach($plans as $plan)
                            <th class="px-4 py-3 text-center text-sm font-medium text-gray-500">
                                {{ $plan->name }}
                                @if($plan->is_popular)
                                    <span class="block text-xs text-yellow-500">⭐ Popular</span>
                                @endif
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr>
                        <td class="px-4 py-2 text-sm text-gray-600">Price (Monthly)</td>
                        @foreach($plans as $plan)
                            <td class="px-4 py-2 text-center text-sm font-bold">{{ $plan->formatted_price }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="px-4 py-2 text-sm text-gray-600">Price (Yearly)</td>
                        @foreach($plans as $plan)
                            <td class="px-4 py-2 text-center text-sm">
                                @if($plan->yearly_price)
                                    UGX {{ number_format($plan->yearly_price, 0) }}
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="px-4 py-2 text-sm text-gray-600">Max Members</td>
                        @foreach($plans as $plan)
                            <td class="px-4 py-2 text-center text-sm">
                                {{ $plan->max_members > 0 ? number_format($plan->max_members) : '♾️ Unlimited' }}
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="px-4 py-2 text-sm text-gray-600">Max Users</td>
                        @foreach($plans as $plan)
                            <td class="px-4 py-2 text-center text-sm">
                                {{ $plan->max_users > 0 ? number_format($plan->max_users) : '♾️ Unlimited' }}
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="px-4 py-2 text-sm text-gray-600">Free Trial</td>
                        @foreach($plans as $plan)
                            <td class="px-4 py-2 text-center text-sm">
                                @if($plan->trial_days > 0)
                                    {{ $plan->trial_days }} days
                                @else
                                    <span class="text-gray-400">None</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="px-4 py-2 text-sm text-gray-600">Features Count</td>
                        @foreach($plans as $plan)
                            <td class="px-4 py-2 text-center text-sm">
                                {{ count($plan->features_list) }}
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection