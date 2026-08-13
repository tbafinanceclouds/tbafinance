<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    //
}
<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\SavingsAccount;
use App\Models\SavingsTransaction;
use App\Models\Loan;
use App\Models\LoanRepayment;
use App\Models\LoanProduct;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // ==========================================
    // REPORTS DASHBOARD
    // ==========================================

    public function index()
    {
        $companyId = auth()->user()->company_id;

        $totalMembers = Member::where('company_id', $companyId)->count();
        $totalSavings = SavingsAccount::whereHas('member', function ($q) use ($companyId) {
            $q->where('company_id', $companyId);
        })->sum('balance');

        $totalLoans = Loan::whereHas('member', function ($q) use ($companyId) {
            $q->where('company_id', $companyId);
        })->sum('amount');

        $totalRepayments = LoanRepayment::whereHas('loan', function ($q) use ($companyId) {
            $q->whereHas('member', function ($q2) use ($companyId) {
                $q2->where('company_id', $companyId);
            });
        })->sum('amount_paid');

        $overdueLoans = Loan::whereHas('member', function ($q) use ($companyId) {
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
    }

    // ==========================================
    // MEMBERS REPORT
    // ==========================================

    public function members()
    {
        $members = Member::where('company_id', auth()->user()->company_id)->get();
        return view('reports.members', compact('members'));
    }

    public function membersPdf()
    {
        $members = Member::where('company_id', auth()->user()->company_id)->get();
        $pdf = Pdf::loadView('reports.members-pdf', compact('members'));
        return $pdf->download('members-report.pdf');
    }

    public function membersCsv()
    {
        $members = Member::where('company_id', auth()->user()->company_id)->get();
        
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
    }

    // ==========================================
    // SAVINGS REPORT
    // ==========================================

    public function savings()
    {
        $savings = SavingsAccount::with(['member', 'savingsProduct'])
            ->whereHas('member', function ($q) {
                $q->where('company_id', auth()->user()->company_id);
            })
            ->get();
        return view('reports.savings', compact('savings'));
    }

    public function savingsPdf()
    {
        $savings = SavingsAccount::with(['member', 'savingsProduct'])
            ->whereHas('member', function ($q) {
                $q->where('company_id', auth()->user()->company_id);
            })
            ->get();
        $pdf = Pdf::loadView('reports.savings-pdf', compact('savings'));
        return $pdf->download('savings-report.pdf');
    }

    // ==========================================
    // LOANS REPORT
    // ==========================================

    public function loans()
    {
        $loans = Loan::with(['member', 'loanProduct', 'repayments'])
            ->whereHas('member', function ($q) {
                $q->where('company_id', auth()->user()->company_id);
            })
            ->get();
        return view('reports.loans', compact('loans'));
    }

    public function loansPdf()
    {
        $loans = Loan::with(['member', 'loanProduct', 'repayments'])
            ->whereHas('member', function ($q) {
                $q->where('company_id', auth()->user()->company_id);
            })
            ->get();
        $pdf = Pdf::loadView('reports.loans-pdf', compact('loans'));
        return $pdf->download('loans-report.pdf');
    }

    // ==========================================
    // PROFIT & LOSS REPORT
    // ==========================================

    public function profitLoss()
    {
        $companyId = auth()->user()->company_id;

        // INCOME - Loan Interest
        $loanInterest = LoanRepayment::whereHas('loan', function ($q) use ($companyId) {
            $q->whereHas('member', function ($q2) use ($companyId) {
                $q2->where('company_id', $companyId);
            });
        })->sum('amount_paid');

        // INCOME - Processing Fees
        $processingFees = Loan::whereHas('member', function ($q) use ($companyId) {
            $q->where('company_id', $companyId);
        })->sum('processing_fee');

        // INCOME - Penalties
        $penalties = LoanRepayment::whereHas('loan', function ($q) use ($companyId) {
            $q->whereHas('member', function ($q2) use ($companyId) {
                $q2->where('company_id', $companyId);
            });
        })->sum('penalty');

        // EXPENSE - Savings Interest
        $savingsInterest = SavingsTransaction::whereHas('savingsAccount', function ($q) use ($companyId) {
            $q->whereHas('member', function ($q2) use ($companyId) {
                $q2->where('company_id', $companyId);
            });
        })->where('type', 'interest')->sum('amount');

        // EXPENSE - Withdrawals
        $withdrawals = SavingsTransaction::whereHas('savingsAccount', function ($q) use ($companyId) {
            $q->whereHas('member', function ($q2) use ($companyId) {
                $q2->where('company_id', $companyId);
            });
        })->where('type', 'withdrawal')->sum('amount');

        // Calculate Totals
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
    }

    // ==========================================
    // PROFIT & LOSS PDF
    // ==========================================

    public function profitLossPdf()
    {
        $companyId = auth()->user()->company_id;

        $loanInterest = LoanRepayment::whereHas('loan', function ($q) use ($companyId) {
            $q->whereHas('member', function ($q2) use ($companyId) {
                $q2->where('company_id', $companyId);
            });
        })->sum('amount_paid');

        $processingFees = Loan::whereHas('member', function ($q) use ($companyId) {
            $q->where('company_id', $companyId);
        })->sum('processing_fee');

        $penalties = LoanRepayment::whereHas('loan', function ($q) use ($companyId) {
            $q->whereHas('member', function ($q2) use ($companyId) {
                $q2->where('company_id', $companyId);
            });
        })->sum('penalty');

        $savingsInterest = SavingsTransaction::whereHas('savingsAccount', function ($q) use ($companyId) {
            $q->whereHas('member', function ($q2) use ($companyId) {
                $q2->where('company_id', $companyId);
            });
        })->where('type', 'interest')->sum('amount');

        $withdrawals = SavingsTransaction::whereHas('savingsAccount', function ($q) use ($companyId) {
            $q->whereHas('member', function ($q2) use ($companyId) {
                $q2->where('company_id', $companyId);
            });
        })->where('type', 'withdrawal')->sum('amount');

        $totalIncome = $loanInterest + $processingFees + $penalties;
        $totalExpenses = $savingsInterest + $withdrawals;
        $netProfit = $totalIncome - $totalExpenses;

        $pdf = Pdf::loadView('reports.profit-loss-pdf', compact(
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
    }
}