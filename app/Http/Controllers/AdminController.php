<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Super Admin Dashboard
    public function dashboard()
    {
        $totalCompanies = Company::count();
        $totalUsers = User::count();
        $activeCompanies = Company::where('is_active', true)->count();
        $expiredCompanies = Company::where('subscription_expiry', '<', now())->count();

        // Recent companies (last 5)
        $recentCompanies = Company::latest()->take(5)->get();

        // Subscription plan counts
        $starterCount = Company::where('subscription_plan', 'starter')->count();
        $professionalCount = Company::where('subscription_plan', 'professional')->count();
        $enterpriseCount = Company::where('subscription_plan', 'enterprise')->count();

        return view('admin.dashboard', compact(
            'totalCompanies',
            'totalUsers',
            'activeCompanies',
            'expiredCompanies',
            'recentCompanies',
            'starterCount',
            'professionalCount',
            'enterpriseCount'
        ));
    }

    // Companies Management
    public function companies(Request $request)
    {
        $query = Company::withCount('members');

        // Search
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($request->status === 'expired') {
                $query->where('subscription_expiry', '<', now());
            }
        }

        $companies = $query->latest()->paginate(12);

        // Stats
        $stats = [
            'total' => Company::count(),
            'active' => Company::where('is_active', true)->count(),
            'inactive' => Company::where('is_active', false)->count(),
            'expired' => Company::where('subscription_expiry', '<', now())->count(),
        ];

        return view('admin.companies', compact('companies', 'stats'));
    }

    public function toggleStatus(Company $company)
    {
        $company->is_active = !$company->is_active;
        $company->save();

        $status = $company->is_active ? 'activated' : 'suspended';
        return redirect()->back()->with('success', "Company {$status} successfully!");
    }

    public function destroy(Company $company)
    {
        if ($company->members()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete company with members. Remove members first.');
        }

        $company->delete();
        return redirect()->route('admin.companies')->with('success', 'Company deleted successfully!');
    }

    public function create()
    {
        $plans = \App\Models\SubscriptionPlan::active()->get();
        return view('admin.companies-create', compact('plans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:companies,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'subscription_plan' => 'nullable|string',
        ]);

        $company = Company::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'subscription_plan' => $request->subscription_plan,
            'is_active' => $request->has('is_active'),
            'currency' => 'UGX',
        ]);

        return redirect()->route('admin.companies')->with('success', 'Company created successfully!');
    }

    public function edit(Company $company)
    {
        $plans = \App\Models\SubscriptionPlan::active()->get();
        return view('admin.companies-edit', compact('company', 'plans'));
    }

    public function update(Request $request, Company $company)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:companies,email,' . $company->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'subscription_plan' => 'nullable|string',
        ]);

        $company->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'subscription_plan' => $request->subscription_plan,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.companies')->with('success', 'Company updated successfully!');
    }
}