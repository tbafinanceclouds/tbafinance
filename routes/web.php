<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\GuarantorController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentCategoryController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CollateralController;
use App\Http\Controllers\CollateralTypeController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\CompanyAuthController;
use App\Http\Controllers\CompanyDashboardController;

// ==========================================
// PUBLIC ROUTES
// ==========================================

Route::get('/', function () {
    return view('welcome');
});

// ==========================================
// COMPANY AUTH ROUTES (Business Clients)
// ==========================================

Route::get('/company/register', [CompanyAuthController::class, 'showRegisterForm'])->name('company.register');
Route::post('/company/register', [CompanyAuthController::class, 'register'])->name('company.register.submit');
Route::get('/company/login', [CompanyAuthController::class, 'showLoginForm'])->name('company.login');
Route::post('/company/login', [CompanyAuthController::class, 'login'])->name('company.login.submit');
Route::post('/company/logout', [CompanyAuthController::class, 'logout'])->name('company.logout');

Route::middleware(['auth:company'])->prefix('company')->name('company.')->group(function () {
    Route::get('/dashboard', [CompanyDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/settings', [CompanyDashboardController::class, 'settings'])->name('settings');
    Route::put('/settings', [CompanyDashboardController::class, 'updateSettings'])->name('settings.update');
});

// ==========================================
// ADMIN AUTHENTICATED ROUTES
// ==========================================

Route::get('/dashboard', function () {
    $companyId = auth()->user()->company_id;

    $totalMembers = \App\Models\Member::where('company_id', $companyId)->count();
    $totalCompanies = \App\Models\Company::count();
    $totalUsers = \App\Models\User::count();
    $recentMembers = \App\Models\Member::where('company_id', $companyId)
        ->latest()
        ->take(5)
        ->get();

    $savingsData = \App\Models\SavingsAccount::whereHas('member', function ($q) use ($companyId) {
        $q->where('company_id', $companyId);
    })->sum('balance');

    $loansData = \App\Models\Loan::whereHas('member', function ($q) use ($companyId) {
        $q->where('company_id', $companyId);
    })->sum('amount');

    $income = \App\Models\LoanRepayment::whereHas('loan', function ($q) use ($companyId) {
        $q->whereHas('member', function ($q2) use ($companyId) {
            $q2->where('company_id', $companyId);
        });
    })->sum('amount_paid');

    $expenses = \App\Models\SavingsTransaction::whereHas('savingsAccount', function ($q) use ($companyId) {
        $q->whereHas('member', function ($q2) use ($companyId) {
            $q2->where('company_id', $companyId);
        });
    })->where('type', 'withdrawal')->sum('amount');

    $activeLoans = \App\Models\Loan::whereHas('member', function ($q) use ($companyId) {
        $q->where('company_id', $companyId);
    })->where('status', 'active')->count();

    $paidLoans = \App\Models\Loan::whereHas('member', function ($q) use ($companyId) {
        $q->where('company_id', $companyId);
    })->where('status', 'completed')->count();

    $overdueLoans = \App\Models\Loan::whereHas('member', function ($q) use ($companyId) {
        $q->where('company_id', $companyId);
    })->whereHas('repayments', function ($q) {
        $q->where('status', 'pending')
          ->where('due_date', '<', now());
    })->count();

    return view('dashboard', compact(
        'totalMembers',
        'totalCompanies',
        'totalUsers',
        'recentMembers',
        'savingsData',
        'loansData',
        'income',
        'expenses',
        'activeLoans',
        'paidLoans',
        'overdueLoans'
    ));
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// ==========================================
// SUPER ADMIN ROUTES
// ==========================================

Route::middleware(['auth', 'super.admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/companies', [AdminController::class, 'companies'])->name('admin.companies');
    Route::get('/companies/create', [AdminController::class, 'create'])->name('admin.companies.create');
    Route::post('/companies', [AdminController::class, 'store'])->name('admin.companies.store');
    Route::get('/companies/{company}/edit', [AdminController::class, 'edit'])->name('admin.companies.edit');
    Route::put('/companies/{company}', [AdminController::class, 'update'])->name('admin.companies.update');
    Route::post('/companies/{company}/toggle', [AdminController::class, 'toggleStatus'])->name('admin.companies.toggle');
    Route::delete('/companies/{company}', [AdminController::class, 'destroy'])->name('admin.companies.destroy');
});

// ==========================================
// MEMBER ROUTES (CRUD)
// ==========================================

Route::middleware(['auth'])->group(function () {
    Route::resource('members', MemberController::class);
});

// ==========================================
// SAVINGS ROUTES
// ==========================================

Route::middleware(['auth'])->prefix('savings')->group(function () {
    Route::get('/products', function () {
        $products = \App\Models\SavingsProduct::where('company_id', auth()->user()->company_id)->get();
        return view('savings.products', compact('products'));
    })->name('savings.products');
    
    Route::get('/products/create', function () {
        return view('savings.create-product');
    })->name('savings.products.create');
    
    Route::post('/products', function (Request $request) {
        set_time_limit(300);
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'interest_rate' => 'nullable|numeric',
            'minimum_balance' => 'nullable|numeric',
        ]);

        \App\Models\SavingsProduct::create([
            'company_id' => auth()->user()->company_id,
            'name' => $request->name,
            'type' => $request->type,
            'interest_rate' => $request->interest_rate ?? 0,
            'minimum_balance' => $request->minimum_balance ?? 0,
            'is_active' => true,
        ]);

        return redirect()->route('savings.products')->with('success', 'Product created successfully!');
    })->name('savings.products.store');
    
    Route::get('/accounts', function () {
        $accounts = \App\Models\SavingsAccount::with(['member', 'savingsProduct'])
            ->whereHas('member', function ($query) {
                $query->where('company_id', auth()->user()->company_id);
            })
            ->get();
        return view('savings.accounts', compact('accounts'));
    })->name('savings.accounts');
    
    Route::get('/accounts/create', function () {
        $members = \App\Models\Member::where('company_id', auth()->user()->company_id)->get();
        $products = \App\Models\SavingsProduct::where('company_id', auth()->user()->company_id)
            ->where('is_active', true)
            ->get();
        return view('savings.create-account', compact('members', 'products'));
    })->name('savings.accounts.create');
    
    Route::post('/accounts', function (Request $request) {
        set_time_limit(300);
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'savings_product_id' => 'required|exists:savings_products,id',
        ]);

        $accountNumber = 'SAV-' . strtoupper(uniqid());

        \App\Models\SavingsAccount::create([
            'company_id' => auth()->user()->company_id,
            'member_id' => $request->member_id,
            'savings_product_id' => $request->savings_product_id,
            'account_number' => $accountNumber,
            'balance' => 0,
            'is_active' => true,
        ]);

        return redirect()->route('savings.accounts')->with('success', 'Account created successfully!');
    })->name('savings.accounts.store');
    
    Route::get('/accounts/{id}', function ($id) {
        $account = \App\Models\SavingsAccount::with(['member', 'savingsProduct', 'transactions'])
            ->findOrFail($id);
        return view('savings.show-account', compact('account'));
    })->name('savings.show');
    
    Route::post('/accounts/{id}/deposit', function (Request $request, $id) {
        set_time_limit(300);
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string',
        ]);

        $account = \App\Models\SavingsAccount::findOrFail($id);
        $account->deposit($request->amount, $request->description);

        return redirect()->route('savings.show', $account->id)
            ->with('success', 'Deposit successful!');
    })->name('savings.deposit');
    
    Route::post('/accounts/{id}/withdraw', function (Request $request, $id) {
        set_time_limit(300);
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string',
        ]);

        $account = \App\Models\SavingsAccount::findOrFail($id);

        try {
            $account->withdraw($request->amount, $request->description);
            return redirect()->route('savings.show', $account->id)
                ->with('success', 'Withdrawal successful!');
        } catch (\Exception $e) {
            return redirect()->route('savings.show', $account->id)
                ->with('error', $e->getMessage());
        }
    })->name('savings.withdraw');
});

// ==========================================
// LOANS ROUTES
// ==========================================

Route::middleware(['auth'])->prefix('loans')->group(function () {
    Route::get('/products', function () {
        $products = \App\Models\LoanProduct::where('company_id', auth()->user()->company_id)->get();
        return view('loans.products', compact('products'));
    })->name('loans.products');
    
    Route::get('/products/create', function () {
        return view('loans.create-product');
    })->name('loans.products.create');
    
    Route::post('/products', function (Request $request) {
        set_time_limit(300);
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'interest_rate' => 'required|numeric|min:0',
            'max_term_months' => 'required|integer|min:1',
            'max_amount' => 'required|numeric|min:0',
            'processing_fee' => 'nullable|numeric|min:0',
        ]);

        \App\Models\LoanProduct::create([
            'company_id' => auth()->user()->company_id,
            'name' => $request->name,
            'type' => $request->type,
            'interest_rate' => $request->interest_rate,
            'max_term_months' => $request->max_term_months,
            'max_amount' => $request->max_amount,
            'processing_fee' => $request->processing_fee ?? 0,
            'requires_guarantor' => $request->has('requires_guarantor'),
            'is_active' => true,
        ]);

        return redirect()->route('loans.products')->with('success', 'Loan product created successfully!');
    })->name('loans.products.store');
    
    Route::get('/applications', function () {
        $loans = \App\Models\Loan::with(['member', 'loanProduct'])
            ->whereHas('member', function ($query) {
                $query->where('company_id', auth()->user()->company_id);
            })
            ->latest()
            ->get();
        return view('loans.applications', compact('loans'));
    })->name('loans.applications');
    
    Route::get('/applications/create', function () {
        $members = \App\Models\Member::where('company_id', auth()->user()->company_id)->get();
        $products = \App\Models\LoanProduct::where('company_id', auth()->user()->company_id)
            ->where('is_active', true)
            ->get();
        return view('loans.create-application', compact('members', 'products'));
    })->name('loans.applications.create');
    
    Route::post('/applications', function (Request $request) {
        set_time_limit(300);
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'loan_product_id' => 'required|exists:loan_products,id',
            'amount' => 'required|numeric|min:0',
            'term_months' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $product = \App\Models\LoanProduct::find($request->loan_product_id);
        $interest = $request->amount * ($product->interest_rate / 100);
        $totalRepayable = $request->amount + $interest + ($product->processing_fee ?? 0);

        \App\Models\Loan::create([
            'company_id' => auth()->user()->company_id,
            'member_id' => $request->member_id,
            'loan_product_id' => $request->loan_product_id,
            'amount' => $request->amount,
            'interest_rate' => $product->interest_rate,
            'total_repayable' => $totalRepayable,
            'term_months' => $request->term_months,
            'status' => 'pending',
            'balance' => $totalRepayable,
            'notes' => $request->notes,
        ]);

        return redirect()->route('loans.applications')->with('success', 'Loan application submitted successfully!');
    })->name('loans.applications.store');
    
    Route::post('/applications/{id}/approve', function ($id) {
        $loan = \App\Models\Loan::findOrFail($id);
        $loan->approve();
        return redirect()->route('loans.applications')->with('success', 'Loan approved!');
    })->name('loans.approve');
    
    Route::post('/applications/{id}/reject', function ($id) {
        $loan = \App\Models\Loan::findOrFail($id);
        $loan->status = 'rejected';
        $loan->save();
        return redirect()->route('loans.applications')->with('success', 'Loan rejected.');
    })->name('loans.reject');
    
    Route::post('/loans/{id}/disburse', function ($id) {
        $loan = \App\Models\Loan::findOrFail($id);
        $loan->disburse();
        return redirect()->route('loans.show', $loan->id)->with('success', 'Loan disbursed successfully!');
    })->name('loans.disburse');
    
    Route::get('/loans/{id}', function ($id) {
        $loan = \App\Models\Loan::with(['member', 'loanProduct', 'repayments'])->findOrFail($id);
        return view('loans.show', compact('loan'));
    })->name('loans.show');
    
    Route::post('/loans/{id}/repay', function (Request $request, $id) {
        set_time_limit(300);
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        $loan = \App\Models\Loan::findOrFail($id);
        $repayment = $loan->repayments()
            ->where('status', 'pending')
            ->orderBy('due_date')
            ->first();

        if (!$repayment) {
            return redirect()->route('loans.show', $loan->id)->with('error', 'No pending installments found.');
        }

        $repayment->pay($request->amount);
        return redirect()->route('loans.show', $loan->id)->with('success', 'Repayment recorded successfully!');
    })->name('loans.repay');
});

// ==========================================
// REPORTS ROUTES
// ==========================================

Route::middleware(['auth'])->prefix('reports')->group(function () {
    Route::get('/', function () {
        $companyId = auth()->user()->company_id;

        $totalMembers = \App\Models\Member::where('company_id', $companyId)->count();
        $totalSavings = \App\Models\SavingsAccount::whereHas('member', function ($q) use ($companyId) {
            $q->where('company_id', $companyId);
        })->sum('balance');

        $totalLoans = \App\Models\Loan::whereHas('member', function ($q) use ($companyId) {
            $q->where('company_id', $companyId);
        })->sum('amount');

        $totalRepayments = \App\Models\LoanRepayment::whereHas('loan', function ($q) use ($companyId) {
            $q->whereHas('member', function ($q2) use ($companyId) {
                $q2->where('company_id', $companyId);
            });
        })->sum('amount_paid');

        $overdueLoans = \App\Models\Loan::whereHas('member', function ($q) use ($companyId) {
            $q->where('company_id', $companyId);
        })->whereHas('repayments', function ($q) {
            $q->where('status', 'pending')
              ->where('due_date', '<', now());
        })->count();

        return view('reports.index', compact(
            'totalMembers',
            'totalSavings',
            'totalLoans',
            'totalRepayments',
            'overdueLoans'
        ));
    })->name('reports.index');
    
    Route::get('/members', function () {
        $members = \App\Models\Member::where('company_id', auth()->user()->company_id)->get();
        return view('reports.members', compact('members'));
    })->name('reports.members');
    
    Route::get('/members/pdf', function () {
        $members = \App\Models\Member::where('company_id', auth()->user()->company_id)->get();
        $pdf = Barryvdh\DomPDF\Facade\Pdf::loadView('reports.members-pdf', compact('members'));
        return $pdf->download('members-report.pdf');
    })->name('reports.members.pdf');
    
    Route::get('/members/csv', function () {
        $members = \App\Models\Member::where('company_id', auth()->user()->company_id)->get();
        $filename = 'members-report.csv';
        
        return response()->stream(
            function () use ($members) {
                $handle = fopen('php://output', 'w');
                fputcsv($handle, ['ID', 'Name', 'Email', 'Phone', 'National ID', 'Date Registered']);
                foreach ($members as $member) {
                    fputcsv($handle, [
                        $member->id,
                        $member->first_name . ' ' . $member->last_name,
                        $member->email,
                        $member->phone,
                        $member->national_id,
                        $member->created_at->format('Y-m-d'),
                    ]);
                }
                fclose($handle);
            },
            200,
            [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]
        );
    })->name('reports.members.csv');
    
    Route::get('/savings', function () {
        $savings = \App\Models\SavingsAccount::with(['member', 'savingsProduct'])
            ->whereHas('member', function ($q) {
                $q->where('company_id', auth()->user()->company_id);
            })
            ->get();
        return view('reports.savings', compact('savings'));
    })->name('reports.savings');
    
    Route::get('/savings/pdf', function () {
        $savings = \App\Models\SavingsAccount::with(['member', 'savingsProduct'])
            ->whereHas('member', function ($q) {
                $q->where('company_id', auth()->user()->company_id);
            })
            ->get();
        $pdf = Barryvdh\DomPDF\Facade\Pdf::loadView('reports.savings-pdf', compact('savings'));
        return $pdf->download('savings-report.pdf');
    })->name('reports.savings.pdf');
    
    Route::get('/loans', function () {
        $loans = \App\Models\Loan::with(['member', 'loanProduct', 'repayments'])
            ->whereHas('member', function ($q) {
                $q->where('company_id', auth()->user()->company_id);
            })
            ->get();
        return view('reports.loans', compact('loans'));
    })->name('reports.loans');
    
    Route::get('/loans/pdf', function () {
        $loans = \App\Models\Loan::with(['member', 'loanProduct', 'repayments'])
            ->whereHas('member', function ($q) {
                $q->where('company_id', auth()->user()->company_id);
            })
            ->get();
        $pdf = Barryvdh\DomPDF\Facade\Pdf::loadView('reports.loans-pdf', compact('loans'));
        return $pdf->download('loans-report.pdf');
    })->name('reports.loans.pdf');

    Route::get('/profit-loss', function () {
        $companyId = auth()->user()->company_id;

        $loanInterest = \App\Models\LoanRepayment::whereHas('loan', function ($q) use ($companyId) {
            $q->whereHas('member', function ($q2) use ($companyId) {
                $q2->where('company_id', $companyId);
            });
        })->sum('amount_paid');

        $processingFees = \App\Models\Loan::whereHas('member', function ($q) use ($companyId) {
            $q->where('company_id', $companyId);
        })->sum('processing_fee');

        $penalties = \App\Models\LoanRepayment::whereHas('loan', function ($q) use ($companyId) {
            $q->whereHas('member', function ($q2) use ($companyId) {
                $q2->where('company_id', $companyId);
            });
        })->sum('penalty');

        $savingsInterest = \App\Models\SavingsTransaction::whereHas('savingsAccount', function ($q) use ($companyId) {
            $q->whereHas('member', function ($q2) use ($companyId) {
                $q2->where('company_id', $companyId);
            });
        })->where('type', 'interest')->sum('amount');

        $withdrawals = \App\Models\SavingsTransaction::whereHas('savingsAccount', function ($q) use ($companyId) {
            $q->whereHas('member', function ($q2) use ($companyId) {
                $q2->where('company_id', $companyId);
            });
        })->where('type', 'withdrawal')->sum('amount');

        $totalIncome = $loanInterest + $processingFees + $penalties;
        $totalExpenses = $savingsInterest + $withdrawals;
        $netProfit = $totalIncome - $totalExpenses;

        return view('reports.profit-loss', compact(
            'loanInterest',
            'processingFees',
            'penalties',
            'savingsInterest',
            'withdrawals',
            'totalIncome',
            'totalExpenses',
            'netProfit'
        ));
    })->name('reports.profit-loss');

    Route::get('/profit-loss/pdf', function () {
        $companyId = auth()->user()->company_id;

        $loanInterest = \App\Models\LoanRepayment::whereHas('loan', function ($q) use ($companyId) {
            $q->whereHas('member', function ($q2) use ($companyId) {
                $q2->where('company_id', $companyId);
            });
        })->sum('amount_paid');

        $processingFees = \App\Models\Loan::whereHas('member', function ($q) use ($companyId) {
            $q->where('company_id', $companyId);
        })->sum('processing_fee');

        $penalties = \App\Models\LoanRepayment::whereHas('loan', function ($q) use ($companyId) {
            $q->whereHas('member', function ($q2) use ($companyId) {
                $q2->where('company_id', $companyId);
            });
        })->sum('penalty');

        $savingsInterest = \App\Models\SavingsTransaction::whereHas('savingsAccount', function ($q) use ($companyId) {
            $q->whereHas('member', function ($q2) use ($companyId) {
                $q2->where('company_id', $companyId);
            });
        })->where('type', 'interest')->sum('amount');

        $withdrawals = \App\Models\SavingsTransaction::whereHas('savingsAccount', function ($q) use ($companyId) {
            $q->whereHas('member', function ($q2) use ($companyId) {
                $q2->where('company_id', $companyId);
            });
        })->where('type', 'withdrawal')->sum('amount');

        $totalIncome = $loanInterest + $processingFees + $penalties;
        $totalExpenses = $savingsInterest + $withdrawals;
        $netProfit = $totalIncome - $totalExpenses;

        $pdf = Barryvdh\DomPDF\Facade\Pdf::loadView('reports.profit-loss-pdf', compact(
            'loanInterest',
            'processingFees',
            'penalties',
            'savingsInterest',
            'withdrawals',
            'totalIncome',
            'totalExpenses',
            'netProfit'
        ));
        return $pdf->download('profit-loss-report.pdf');
    })->name('reports.profit-loss.pdf');
});

// ==========================================
// SETTINGS ROUTES
// ==========================================

Route::middleware(['auth'])->prefix('settings')->group(function () {
    Route::get('/', function () {
        $company = auth()->user()->company;
        return view('settings.index', compact('company'));
    })->name('settings.index');
    
    Route::put('/', function (Request $request) {
        set_time_limit(300);
        $company = auth()->user()->company;
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'currency' => 'required|string|max:10',
        ]);
        
        $company->update($request->only(['name', 'email', 'phone', 'address', 'currency']));
        
        return redirect()->route('settings.index')->with('success', 'Settings updated successfully!');
    })->name('settings.update');
    
    Route::post('/logo', function (Request $request) {
        set_time_limit(300);
        $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $company = auth()->user()->company;
        
        if ($company->logo && Storage::disk('public')->exists($company->logo)) {
            Storage::disk('public')->delete($company->logo);
        }
        
        $path = $request->file('logo')->store('logos', 'public');
        $company->logo = $path;
        $company->save();
        
        return redirect()->route('settings.index')->with('success', 'Logo updated successfully!');
    })->name('settings.logo');
});

// ==========================================
// GUARANTOR ROUTES
// ==========================================

Route::middleware(['auth'])->prefix('guarantors')->group(function () {
    Route::get('/', [GuarantorController::class, 'index'])->name('guarantors.index');
    Route::get('/create', [GuarantorController::class, 'create'])->name('guarantors.create');
    Route::post('/', [GuarantorController::class, 'store'])->name('guarantors.store');
    Route::get('/{guarantor}', [GuarantorController::class, 'show'])->name('guarantors.show');
    Route::get('/{guarantor}/edit', [GuarantorController::class, 'edit'])->name('guarantors.edit');
    Route::put('/{guarantor}', [GuarantorController::class, 'update'])->name('guarantors.update');
    Route::get('/{guarantor}/approve', [GuarantorController::class, 'approve'])->name('guarantors.approve');
    Route::get('/{guarantor}/reject', [GuarantorController::class, 'reject'])->name('guarantors.reject');
    Route::delete('/{guarantor}', [GuarantorController::class, 'destroy'])->name('guarantors.destroy');
});

// ==========================================
// AUDIT LOG ROUTES
// ==========================================

Route::middleware(['auth', 'super.admin'])->prefix('audit')->group(function () {
    Route::get('/', [App\Http\Controllers\AuditLogController::class, 'index'])->name('audit.index');
    Route::get('/{id}', [App\Http\Controllers\AuditLogController::class, 'show'])->name('audit.show');
});

// ==========================================
// SHARES ROUTES
// ==========================================

Route::middleware(['auth'])->prefix('shares')->group(function () {
    Route::get('/products', function () {
        $products = \App\Models\ShareProduct::where('company_id', auth()->user()->company_id)->get();
        return view('shares.products', compact('products'));
    })->name('shares.products');
    
    Route::get('/products/create', function () {
        return view('shares.create-product');
    })->name('shares.products.create');
    
    Route::post('/products', function (Request $request) {
        set_time_limit(300);
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:share_products',
            'type' => 'required|string',
            'price_per_share' => 'required|numeric|min:0',
            'min_shares' => 'required|integer|min:1',
            'max_shares' => 'nullable|integer|min:1',
            'dividend_rate' => 'nullable|numeric|min:0',
        ]);

        \App\Models\ShareProduct::create([
            'company_id' => auth()->user()->company_id,
            'name' => $request->name,
            'code' => $request->code,
            'type' => $request->type,
            'price_per_share' => $request->price_per_share,
            'min_shares' => $request->min_shares,
            'max_shares' => $request->max_shares,
            'dividend_rate' => $request->dividend_rate ?? 0,
            'is_active' => true,
        ]);

        return redirect()->route('shares.products')->with('success', 'Share product created successfully!');
    })->name('shares.products.store');
    
    Route::get('/buy', function () {
        $members = \App\Models\Member::where('company_id', auth()->user()->company_id)->get();
        $products = \App\Models\ShareProduct::where('company_id', auth()->user()->company_id)
            ->where('is_active', true)
            ->get();
        return view('shares.buy', compact('members', 'products'));
    })->name('shares.buy');
    
    Route::post('/purchase', function (Request $request) {
        set_time_limit(300);
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'share_product_id' => 'required|exists:share_products,id',
            'shares' => 'required|integer|min:1',
        ]);

        $product = \App\Models\ShareProduct::find($request->share_product_id);
        $totalAmount = $product->price_per_share * $request->shares;

        \App\Models\ShareHolding::create([
            'company_id' => auth()->user()->company_id,
            'member_id' => $request->member_id,
            'share_product_id' => $request->share_product_id,
            'shares' => $request->shares,
            'total_value' => $totalAmount,
            'purchase_date' => now(),
            'status' => 'active',
        ]);

        \App\Models\ShareTransaction::create([
            'company_id' => auth()->user()->company_id,
            'member_id' => $request->member_id,
            'share_product_id' => $request->share_product_id,
            'type' => 'buy',
            'shares' => $request->shares,
            'price_per_share' => $product->price_per_share,
            'total_amount' => $totalAmount,
            'transaction_date' => now(),
            'notes' => 'Share purchase',
        ]);

        $member = \App\Models\Member::find($request->member_id);
        \App\Models\Receipt::create([
            'company_id' => $member->company_id,
            'member_id' => $request->member_id,
            'receipt_number' => 'RCP-' . date('Ymd') . '-' . strtoupper(uniqid()),
            'type' => 'share_purchase',
            'amount' => $totalAmount,
            'payment_method' => 'cash',
            'description' => 'Purchase of ' . $request->shares . ' shares from ' . $product->name,
            'created_by' => auth()->id(),
            'receipt_date' => now(),
        ]);

        return redirect()->route('shares.buy')->with('success', 'Shares purchased successfully!');
    })->name('shares.purchase');
    
    Route::get('/holdings', function () {
        $holdings = \App\Models\ShareHolding::with(['member', 'shareProduct'])
            ->whereHas('member', function ($q) {
                $q->where('company_id', auth()->user()->company_id);
            })
            ->get();
        return view('shares.holdings', compact('holdings'));
    })->name('shares.holdings');
});

// ==========================================
// ACCOUNTING ROUTES
// ==========================================

Route::middleware(['auth'])->prefix('accounting')->group(function () {
    Route::get('/chart', function () {
        $accounts = \App\Models\ChartOfAccount::where('company_id', auth()->user()->company_id)->get();
        return view('accounting.chart-of-accounts', compact('accounts'));
    })->name('accounting.chart');
    
    Route::get('/chart/create', function () {
        return view('accounting.create-account');
    })->name('accounting.create-account');
    
    Route::post('/chart', function (Request $request) {
        set_time_limit(300);
        $request->validate([
            'account_code' => 'required|string|max:20|unique:chart_of_accounts',
            'account_name' => 'required|string|max:255',
            'account_type' => 'required|string',
            'normal_balance' => 'required|string',
        ]);

        \App\Models\ChartOfAccount::create([
            'company_id' => auth()->user()->company_id,
            'account_code' => $request->account_code,
            'account_name' => $request->account_name,
            'account_type' => $request->account_type,
            'normal_balance' => $request->normal_balance,
            'is_active' => true,
        ]);

        return redirect()->route('accounting.chart')->with('success', 'Account created successfully!');
    })->name('accounting.store-account');
    
    Route::get('/journal', function () {
        $entries = \App\Models\JournalEntry::where('company_id', auth()->user()->company_id)
            ->with('details.account')
            ->latest()
            ->get();
        return view('accounting.journal-entries', compact('entries'));
    })->name('accounting.journal-entries');
    
    Route::get('/journal/create', function () {
        $accounts = \App\Models\ChartOfAccount::where('company_id', auth()->user()->company_id)
            ->where('is_active', true)
            ->get();
        return view('accounting.create-journal', compact('accounts'));
    })->name('accounting.create-journal');
    
    Route::post('/journal', function (Request $request) {
        set_time_limit(300);
        $request->validate([
            'entry_date' => 'required|date',
            'description' => 'required|string',
            'reference' => 'nullable|string',
            'account_id' => 'required|array|min:2',
            'account_id.*' => 'exists:chart_of_accounts,id',
            'debit' => 'required|array',
            'debit.*' => 'numeric|min:0',
            'credit' => 'required|array',
            'credit.*' => 'numeric|min:0',
        ]);

        $totalDebit = array_sum($request->debit);
        $totalCredit = array_sum($request->credit);

        if ($totalDebit !== $totalCredit) {
            return redirect()->back()->with('error', 'Total debits must equal total credits.');
        }

        $entry = \App\Models\JournalEntry::create([
            'company_id' => auth()->user()->company_id,
            'entry_date' => $request->entry_date,
            'reference' => $request->reference,
            'description' => $request->description,
            'total_debit' => $totalDebit,
            'total_credit' => $totalCredit,
            'status' => 'draft',
        ]);

        foreach ($request->account_id as $key => $accountId) {
            \App\Models\JournalEntryDetail::create([
                'journal_entry_id' => $entry->id,
                'account_id' => $accountId,
                'debit' => $request->debit[$key] ?? 0,
                'credit' => $request->credit[$key] ?? 0,
                'description' => $request->detail_description[$key] ?? null,
            ]);
        }

        return redirect()->route('accounting.journal-entries')->with('success', 'Journal entry created successfully!');
    })->name('accounting.store-journal');
    
    Route::delete('/journal/{id}', function ($id) {
        $entry = \App\Models\JournalEntry::where('company_id', auth()->user()->company_id)->findOrFail($id);
        $entry->details()->delete();
        $entry->delete();
        return redirect()->route('accounting.journal-entries')->with('success', 'Journal entry deleted!');
    })->name('accounting.journal-entries.destroy');
    
    Route::post('/journal/{id}/post', function ($id) {
        $entry = \App\Models\JournalEntry::where('company_id', auth()->user()->company_id)->findOrFail($id);
        $entry->status = 'posted';
        $entry->save();
        return redirect()->route('accounting.journal-entries')->with('success', 'Journal entry posted!');
    })->name('accounting.journal.post');
    
    Route::get('/account/{id}', function ($id) {
        $account = \App\Models\ChartOfAccount::where('company_id', auth()->user()->company_id)->findOrFail($id);
        $transactions = \App\Models\JournalEntryDetail::where('account_id', $id)
            ->with('journalEntry')
            ->get();
        return view('accounting.account-details', compact('account', 'transactions'));
    })->name('accounting.account-details');
    
    Route::get('/trial-balance', function () {
        $companyId = auth()->user()->company_id;
        $accounts = \App\Models\ChartOfAccount::where('company_id', $companyId)->where('is_active', true)->get();
        $totalDebits = 0;
        $totalCredits = 0;

        foreach ($accounts as $account) {
            $balance = $account->balance;
            if ($balance > 0) {
                if ($account->normal_balance === 'debit') {
                    $account->balance_amount = $balance;
                    $account->balance_type = 'debit';
                    $totalDebits += $balance;
                } else {
                    $account->balance_amount = $balance;
                    $account->balance_type = 'credit';
                    $totalCredits += $balance;
                }
            }
        }

        return view('accounting.trial-balance', compact('accounts', 'totalDebits', 'totalCredits'));
    })->name('accounting.trial-balance');
    
    Route::get('/balance-sheet', function () {
        $companyId = auth()->user()->company_id;
        $accounts = \App\Models\ChartOfAccount::where('company_id', $companyId)->where('is_active', true)->get();
        
        $assets = $accounts->where('account_type', 'asset');
        $liabilities = $accounts->where('account_type', 'liability');
        $equity = $accounts->where('account_type', 'equity');

        return view('accounting.balance-sheet', compact('assets', 'liabilities', 'equity'));
    })->name('accounting.balance-sheet');
    
    Route::get('/income-statement', function () {
        $companyId = auth()->user()->company_id;
        $accounts = \App\Models\ChartOfAccount::where('company_id', $companyId)->where('is_active', true)->get();
        
        $income = $accounts->where('account_type', 'income');
        $expenses = $accounts->where('account_type', 'expense');

        return view('accounting.income-statement', compact('income', 'expenses'));
    })->name('accounting.income-statement');
    
    Route::post('/closing-entry', function (Request $request) {
        set_time_limit(300);
        $companyId = auth()->user()->company_id;
        
        $incomeAccounts = \App\Models\ChartOfAccount::where('company_id', $companyId)
            ->where('account_type', 'income')
            ->get();
        
        $expenseAccounts = \App\Models\ChartOfAccount::where('company_id', $companyId)
            ->where('account_type', 'expense')
            ->get();
        
        $totalIncome = $incomeAccounts->sum('balance');
        $totalExpenses = $expenseAccounts->sum('balance');
        $netProfit = $totalIncome - $totalExpenses;
        
        $entry = \App\Models\JournalEntry::create([
            'company_id' => $companyId,
            'entry_date' => now(),
            'reference' => 'CLOSING-' . now()->format('Y-m-d'),
            'description' => 'Closing entry for period ending ' . now()->format('Y-m-d'),
            'total_debit' => $totalIncome,
            'total_credit' => $totalExpenses + abs($netProfit),
            'status' => 'draft',
        ]);
        
        foreach ($incomeAccounts as $account) {
            \App\Models\JournalEntryDetail::create([
                'journal_entry_id' => $entry->id,
                'account_id' => $account->id,
                'debit' => $account->balance,
                'credit' => 0,
                'description' => 'Closing income account',
            ]);
        }
        
        foreach ($expenseAccounts as $account) {
            \App\Models\JournalEntryDetail::create([
                'journal_entry_id' => $entry->id,
                'account_id' => $account->id,
                'debit' => 0,
                'credit' => $account->balance,
                'description' => 'Closing expense account',
            ]);
        }
        
        if ($netProfit > 0) {
            $equityAccount = \App\Models\ChartOfAccount::where('company_id', $companyId)
                ->where('account_type', 'equity')
                ->first();
            
            if ($equityAccount) {
                \App\Models\JournalEntryDetail::create([
                    'journal_entry_id' => $entry->id,
                    'account_id' => $equityAccount->id,
                    'debit' => 0,
                    'credit' => $netProfit,
                    'description' => 'Net profit transferred to equity',
                ]);
            }
        } else {
            $equityAccount = \App\Models\ChartOfAccount::where('company_id', $companyId)
                ->where('account_type', 'equity')
                ->first();
            
            if ($equityAccount) {
                \App\Models\JournalEntryDetail::create([
                    'journal_entry_id' => $entry->id,
                    'account_id' => $equityAccount->id,
                    'debit' => abs($netProfit),
                    'credit' => 0,
                    'description' => 'Net loss transferred to equity',
                ]);
            }
        }
        
        return redirect()->route('accounting.journal-entries')->with('success', 'Closing entry created!');
    })->name('accounting.closing-entry');
});

// ==========================================
// CASHBOOK ROUTES
// ==========================================

Route::middleware(['auth'])->prefix('cashbook')->group(function () {
    Route::get('/', function () {
        $transactions = \App\Models\Cashbook::where('company_id', auth()->user()->company_id)
            ->with('creator')
            ->latest()
            ->paginate(50);
        
        $balance = \App\Models\Cashbook::where('company_id', auth()->user()->company_id)
            ->latest()
            ->value('balance') ?? 0;
        
        $todayTransactions = \App\Models\Cashbook::where('company_id', auth()->user()->company_id)
            ->whereDate('transaction_date', now())
            ->get();
        
        $todayCashIn = $todayTransactions->where('type', 'cash_in')->sum('amount');
        $todayCashOut = $todayTransactions->where('type', 'cash_out')->sum('amount');

        return view('cashbook.index', compact('transactions', 'balance', 'todayCashIn', 'todayCashOut'));
    })->name('cashbook.index');
    
    Route::get('/create', function () {
        return view('cashbook.create');
    })->name('cashbook.create');
    
    Route::post('/', function (Request $request) {
        set_time_limit(300);
        $request->validate([
            'transaction_date' => 'required|date',
            'type' => 'required|in:cash_in,cash_out',
            'category' => 'required|string',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string',
        ]);

        $lastBalance = \App\Models\Cashbook::where('company_id', auth()->user()->company_id)
            ->latest()
            ->value('balance') ?? 0;

        if ($request->type === 'cash_in') {
            $newBalance = $lastBalance + $request->amount;
        } else {
            $newBalance = $lastBalance - $request->amount;
        }

        \App\Models\Cashbook::create([
            'company_id' => auth()->user()->company_id,
            'transaction_date' => $request->transaction_date,
            'type' => $request->type,
            'category' => $request->category,
            'reference' => $request->reference,
            'description' => $request->description,
            'amount' => $request->amount,
            'balance' => $newBalance,
            'payment_method' => $request->payment_method,
            'status' => 'completed',
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('cashbook.index')->with('success', 'Cashbook entry created successfully!');
    })->name('cashbook.store');
    
    Route::get('/{id}', function ($id) {
        $transaction = \App\Models\Cashbook::where('company_id', auth()->user()->company_id)
            ->with('creator')
            ->findOrFail($id);
        return view('cashbook.show', compact('transaction'));
    })->name('cashbook.show');
    
    Route::delete('/{id}', function ($id) {
        $transaction = \App\Models\Cashbook::where('company_id', auth()->user()->company_id)->findOrFail($id);
        $transaction->delete();
        return redirect()->route('cashbook.index')->with('success', 'Transaction deleted!');
    })->name('cashbook.destroy');
});

// ==========================================
// RECEIPT ROUTES
// ==========================================

Route::middleware(['auth'])->prefix('receipts')->group(function () {
    Route::get('/', function (Request $request) {
        $query = \App\Models\Receipt::where('company_id', auth()->user()->company_id)
            ->with(['member', 'creator']);
        
        if ($request->filled('date_from')) {
            $query->whereDate('receipt_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('receipt_date', '<=', $request->date_to);
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        $receipts = $query->latest()->paginate(20);
        return view('receipts.index', compact('receipts'));
    })->name('receipts.index');
    
    Route::get('/{id}', function ($id) {
        $receipt = \App\Models\Receipt::with(['company', 'member', 'creator'])
            ->where('company_id', auth()->user()->company_id)
            ->findOrFail($id);
        return view('receipts.show', compact('receipt'));
    })->name('receipts.show');
    
    Route::get('/{id}/pdf', function ($id) {
        $receipt = \App\Models\Receipt::with(['company', 'member', 'creator'])
            ->where('company_id', auth()->user()->company_id)
            ->findOrFail($id);
        
        $pdf = Barryvdh\DomPDF\Facade\Pdf::loadView('receipts.pdf', compact('receipt'));
        return $pdf->download('receipt-' . $receipt->receipt_number . '.pdf');
    })->name('receipts.pdf');
});

// ==========================================
// TEST EMAIL ROUTE
// ==========================================

Route::get('/test-email', function () {
    $email = new \App\Http\Controllers\EmailController();
    $email->sendWelcomeEmail('escapeobey@gmail.com', 'Test User');
    return 'Test email sent! Check your inbox.';
});

// ==========================================
// DOCUMENT MANAGEMENT ROUTES
// ==========================================

Route::middleware(['auth'])->prefix('documents')->group(function () {
    // Categories
    Route::get('/categories', [DocumentCategoryController::class, 'index'])->name('documents.categories');
    Route::get('/categories/create', [DocumentCategoryController::class, 'create'])->name('documents.categories.create');
    Route::post('/categories', [DocumentCategoryController::class, 'store'])->name('documents.categories.store');
    Route::get('/categories/{category}/edit', [DocumentCategoryController::class, 'edit'])->name('documents.categories.edit');
    Route::put('/categories/{category}', [DocumentCategoryController::class, 'update'])->name('documents.categories.update');
    Route::delete('/categories/{category}', [DocumentCategoryController::class, 'destroy'])->name('documents.categories.destroy');

    // Documents
    Route::get('/', [DocumentController::class, 'index'])->name('documents.index');
    Route::get('/create', [DocumentController::class, 'create'])->name('documents.create');
    Route::post('/', [DocumentController::class, 'store'])->name('documents.store');
    Route::get('/{document}', [DocumentController::class, 'show'])->name('documents.show');
    Route::get('/{document}/edit', [DocumentController::class, 'edit'])->name('documents.edit');
    Route::put('/{document}', [DocumentController::class, 'update'])->name('documents.update');
    Route::delete('/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    
    // Actions
    Route::get('/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
    Route::get('/{document}/preview', [DocumentController::class, 'preview'])->name('documents.preview');
    Route::post('/{document}/verify', [DocumentController::class, 'verify'])->name('documents.verify');
    Route::post('/{document}/unverify', [DocumentController::class, 'unverify'])->name('documents.unverify');
    
    // Attachments
    Route::get('/attachments/{type}/{id}', [DocumentController::class, 'attachments'])->name('documents.attachments');
});

// ==========================================
// PAYMENT ROUTES (Flutterwave)
// ==========================================

Route::middleware(['auth'])->prefix('payments')->group(function () {
    Route::get('/', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/initiate', [PaymentController::class, 'initiate'])->name('payments.initiate');
    Route::get('/callback', [PaymentController::class, 'callback'])->name('payments.callback');
});

// ==========================================
// COLLATERAL MANAGEMENT ROUTES
// ==========================================

// Collateral Types
Route::middleware(['auth'])->prefix('collaterals')->group(function () {
    Route::get('/types', [CollateralTypeController::class, 'index'])->name('collaterals.types');
    Route::get('/types/create', [CollateralTypeController::class, 'create'])->name('collaterals.types.create');
    Route::post('/types', [CollateralTypeController::class, 'store'])->name('collaterals.types.store');
    Route::get('/types/{type}/edit', [CollateralTypeController::class, 'edit'])->name('collaterals.types.edit');
    Route::put('/types/{type}', [CollateralTypeController::class, 'update'])->name('collaterals.types.update');
    Route::delete('/types/{type}', [CollateralTypeController::class, 'destroy'])->name('collaterals.types.destroy');
});

// Collaterals
Route::middleware(['auth'])->prefix('collaterals')->group(function () {
    Route::get('/', [CollateralController::class, 'index'])->name('collaterals.index');
    Route::get('/create', [CollateralController::class, 'create'])->name('collaterals.create');
    Route::post('/', [CollateralController::class, 'store'])->name('collaterals.store');
    Route::get('/{collateral}', [CollateralController::class, 'show'])->name('collaterals.show');
    Route::get('/{collateral}/edit', [CollateralController::class, 'edit'])->name('collaterals.edit');
    Route::put('/{collateral}', [CollateralController::class, 'update'])->name('collaterals.update');
    Route::delete('/{collateral}', [CollateralController::class, 'destroy'])->name('collaterals.destroy');
    Route::post('/{collateral}/verify', [CollateralController::class, 'verify'])->name('collaterals.verify');
    Route::post('/{collateral}/reject', [CollateralController::class, 'reject'])->name('collaterals.reject');
    Route::post('/{collateral}/release', [CollateralController::class, 'release'])->name('collaterals.release');
});

// ==========================================
// SUBSCRIPTION ROUTES
// ==========================================

Route::middleware(['auth'])->group(function () {
    Route::get('/subscription', [SubscriptionController::class, 'index'])->name('subscription.index');
    Route::post('/subscription/select', [SubscriptionController::class, 'selectPlan'])->name('subscription.select');
});

Route::middleware(['auth', 'super.admin'])->prefix('subscription')->group(function () {
    Route::get('/admin', [SubscriptionController::class, 'admin'])->name('subscription.admin');
    Route::get('/admin/{plan}/edit', [SubscriptionController::class, 'edit'])->name('subscription.edit');
    Route::post('/admin', [SubscriptionController::class, 'store'])->name('subscription.store');
    Route::put('/admin/{plan}', [SubscriptionController::class, 'update'])->name('subscription.update');
    Route::delete('/admin/{plan}', [SubscriptionController::class, 'destroy'])->name('subscription.delete');
    Route::post('/validate-promo', [SubscriptionController::class, 'validatePromo'])->name('subscription.validate-promo');
});

// ==========================================
// REDIRECTS
// ==========================================

Route::get('/pricing', function () {
    return redirect()->route('subscription.index');
});

Route::get('/pricing/admin', function () {
    return redirect()->route('subscription.admin');
});

Route::get('/pricing/admin/{id}/edit', function ($id) {
    return redirect()->route('subscription.edit', $id);
});

Route::get('/register', function () {
    return redirect()->route('company.register');
})->name('register');


// ==========================================
// COMPANY DASHBOARD ROUTES (Business Clients)
// ==========================================

Route::middleware(['auth:company'])->prefix('company')->name('company.')->group(function () {
    Route::get('/dashboard', [CompanyDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/members', [CompanyDashboardController::class, 'members'])->name('members');
    Route::get('/savings', [CompanyDashboardController::class, 'savings'])->name('savings');
    Route::get('/loans', [CompanyDashboardController::class, 'loans'])->name('loans');
    Route::get('/reports', [CompanyDashboardController::class, 'reports'])->name('reports');
    Route::get('/settings', [CompanyDashboardController::class, 'settings'])->name('settings');
    Route::put('/settings', [CompanyDashboardController::class, 'updateSettings'])->name('settings.update');
});