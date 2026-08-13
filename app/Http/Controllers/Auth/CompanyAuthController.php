<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class CompanyAuthController extends Controller
{
    // Show signup form
    public function showRegisterForm()
    {
        return view('auth.company-register');
    }

    // Handle signup
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:companies',
            'email' => 'required|email|unique:companies',
            'phone' => 'nullable|string|max:20',
            'contact_person' => 'required|string|max:255',
            'business_type' => 'required|string|max:255',
            'registration_number' => 'nullable|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $company = Company::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'contact_person' => $request->contact_person,
                'business_type' => $request->business_type,
                'registration_number' => $request->registration_number,
                'password' => Hash::make($request->password),
                'is_active' => false,
                'is_approved' => false,
                'currency' => 'UGX',
            ]);

            Log::info('New company registered', ['id' => $company->id, 'name' => $company->name]);

            return redirect()->route('company.login')
                ->with('success', 'Registration successful! Your account is pending approval. You will be notified when approved.');

        } catch (\Exception $e) {
            Log::error('Company registration failed', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Registration failed. Please try again.')
                ->withInput();
        }
    }

    // Show login form
    public function showLoginForm()
    {
        return view('auth.company-login');
    }

    // Handle login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Find company by email
        $company = Company::where('email', $request->email)->first();

        if (!$company) {
            return back()->withErrors(['email' => 'Company not found.']);
        }

        // Check if approved
        if (!$company->is_approved) {
            return back()->withErrors(['email' => 'Your account is pending approval. Please wait for admin confirmation.']);
        }

        // Check if active
        if (!$company->is_active) {
            return back()->withErrors(['email' => 'Your account is suspended. Please contact support.']);
        }

        if (Auth::guard('company')->attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/company/dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::guard('company')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/company/login');
    }
}