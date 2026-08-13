<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    // Public pricing page
    public function index()
    {
        $plans = SubscriptionPlan::active()->orderBy('sort_order')->get();
        $company = auth()->user()->company;
        
        return view('subscription.index', compact('plans', 'company'));
    }

    // Admin management page
    public function admin()
    {
        $plans = SubscriptionPlan::orderBy('sort_order')->get();
        return view('subscription.admin', compact('plans'));
    }

    // Edit plan form
    public function edit(SubscriptionPlan $plan)
    {
        return view('subscription.edit', compact('plan'));
    }

    // Create new plan
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:pricing_plans',
            'price' => 'required|numeric|min:0',
            'yearly_price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'trial_days' => 'nullable|integer|min:0',
            'promo_code' => 'nullable|string|max:50|unique:pricing_plans,promo_code',
            'promo_discount' => 'nullable|numeric|min:0|max:100',
            'max_members' => 'nullable|integer|min:0',
            'max_users' => 'nullable|integer|min:0',
            'is_popular' => 'boolean',
        ]);

        $features = [];
        if ($request->has('features')) {
            $features = array_filter(array_map('trim', explode("\n", $request->features)));
        }

        SubscriptionPlan::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'yearly_price' => $request->yearly_price,
            'billing_period' => $request->billing_period ?? 'monthly',
            'features' => $features,
            'max_members' => $request->max_members ?? 0,
            'max_users' => $request->max_users ?? 0,
            'max_features' => $request->max_features ?? 0,
            'trial_days' => $request->trial_days ?? 0,
            'promo_code' => $request->promo_code,
            'promo_discount' => $request->promo_discount,
            'promo_expires_at' => $request->promo_expires_at,
            'sort_order' => SubscriptionPlan::count() + 1,
            'is_active' => $request->has('is_active'),
            'is_popular' => $request->has('is_popular'),
        ]);

        return redirect()->route('subscription.admin')->with('success', 'Plan created successfully!');
    }

    // Update plan
    public function update(Request $request, SubscriptionPlan $plan)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:pricing_plans,name,' . $plan->id,
            'price' => 'required|numeric|min:0',
            'yearly_price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'trial_days' => 'nullable|integer|min:0',
            'promo_code' => 'nullable|string|max:50|unique:pricing_plans,promo_code,' . $plan->id,
            'promo_discount' => 'nullable|numeric|min:0|max:100',
            'max_members' => 'nullable|integer|min:0',
            'max_users' => 'nullable|integer|min:0',
            'is_popular' => 'boolean',
        ]);

        $features = [];
        if ($request->has('features')) {
            $features = array_filter(array_map('trim', explode("\n", $request->features)));
        }

        $plan->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'yearly_price' => $request->yearly_price,
            'billing_period' => $request->billing_period ?? 'monthly',
            'features' => $features,
            'max_members' => $request->max_members ?? 0,
            'max_users' => $request->max_users ?? 0,
            'max_features' => $request->max_features ?? 0,
            'trial_days' => $request->trial_days ?? 0,
            'promo_code' => $request->promo_code,
            'promo_discount' => $request->promo_discount,
            'promo_expires_at' => $request->promo_expires_at,
            'is_active' => $request->has('is_active'),
            'is_popular' => $request->has('is_popular'),
        ]);

        return redirect()->route('subscription.admin')->with('success', 'Plan updated successfully!');
    }

    // Delete plan
    public function destroy(SubscriptionPlan $plan)
    {
        $plan->delete();
        return redirect()->route('subscription.admin')->with('success', 'Plan deleted successfully!');
    }

    // User selects a plan
    public function selectPlan(Request $request)
    {
        $company = auth()->user()->company;
        $plan = SubscriptionPlan::where('slug', $request->plan)->first();
        
        if (!$plan) {
            return redirect()->back()->with('error', 'Plan not found.');
        }

        $company->subscription_plan = $plan->slug;
        $company->subscription_expiry = now()->addMonths(12);
        $company->save();

        return redirect()->route('subscription.index')->with('success', 'Plan updated to ' . $plan->name . '!');
    }

    // Validate promo code (AJAX)
    public function validatePromo(Request $request)
    {
        $plan = SubscriptionPlan::where('promo_code', $request->promo_code)
            ->where('is_active', true)
            ->first();

        if (!$plan) {
            return response()->json(['valid' => false, 'message' => 'Invalid promo code']);
        }

        if ($plan->promo_expires_at && $plan->promo_expires_at < now()) {
            return response()->json(['valid' => false, 'message' => 'Promo code expired']);
        }

        return response()->json([
            'valid' => true,
            'discount' => $plan->promo_discount,
            'message' => $plan->promo_discount . '% discount applied!'
        ]);
    }
}