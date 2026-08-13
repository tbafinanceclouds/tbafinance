<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Loan;
use App\Models\SavingsAccount;
use App\Models\SavingsTransaction;
use Illuminate\Http\Request;

class CompanyDashboardController extends Controller
{
    public function dashboard()
    {
        $company = auth()->guard('company')->user();
        
        // Stats
        $stats = [
            'members' => Member::where('company_id', $company->id)->count(),
            'savings' => SavingsAccount::whereHas('member', function($q) use ($company) {
                $q->where('company_id', $company->id);
            })->sum('balance'),
            'loans' => Loan::whereHas('member', function($q) use ($company) {
                $q->where('company_id', $company->id);
            })->sum('amount'),
            'active_loans' => Loan::whereHas('member', function($q) use ($company) {
                $q->where('company_id', $company->id);
            })->where('status', 'active')->count(),
            'overdue_loans' => Loan::whereHas('member', function($q) use ($company) {
                $q->where('company_id', $company->id);
            })->where('status', 'overdue')->count(),
        ];

        // Recent members (last 5)
        $recentMembers = Member::where('company_id', $company->id)
            ->latest()
            ->take(5)
            ->get();

        // Recent loans (last 5)
        $recentLoans = Loan::whereHas('member', function($q) use ($company) {
                $q->where('company_id', $company->id);
            })
            ->latest()
            ->take(5)
            ->get();

        return view('company.dashboard', compact('company', 'stats', 'recentMembers', 'recentLoans'));
    }

    public function members()
    {
        $company = auth()->guard('company')->user();
        $members = Member::where('company_id', $company->id)->paginate(20);
        return view('company.members', compact('company', 'members'));
    }

    public function savings()
    {
        $company = auth()->guard('company')->user();
        $accounts = SavingsAccount::with(['member', 'savingsProduct'])
            ->whereHas('member', function($q) use ($company) {
                $q->where('company_id', $company->id);
            })
            ->get();
        return view('company.savings', compact('company', 'accounts'));
    }

    public function loans()
    {
        $company = auth()->guard('company')->user();
        $loans = Loan::with(['member', 'loanProduct', 'repayments'])
            ->whereHas('member', function($q) use ($company) {
                $q->where('company_id', $company->id);
            })
            ->get();
        return view('company.loans', compact('company', 'loans'));
    }

    public function reports()
    {
        $company = auth()->guard('company')->user();
        return view('company.reports', compact('company'));
    }

    public function settings()
    {
        $company = auth()->guard('company')->user();
        return view('company.settings', compact('company'));
    }

    public function updateSettings(Request $request)
    {
        $company = auth()->guard('company')->user();
        
        $request->validate([
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'contact_person' => 'nullable|string|max:255',
        ]);

        $company->update($request->only(['phone', 'address', 'contact_person']));

        return redirect()->route('company.settings')->with('success', 'Settings updated successfully!');
    }
}