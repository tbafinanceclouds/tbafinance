<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use App\Models\JournalEntry;
use App\Models\JournalEntryDetail;
use Illuminate\Http\Request;

class AccountingController extends Controller
{
    // ==========================================
    // CHART OF ACCOUNTS
    // ==========================================

    public function chartOfAccounts()
    {
        $accounts = ChartOfAccount::where('company_id', auth()->user()->company_id)->get();
        return view('accounting.chart-of-accounts', compact('accounts'));
    }

    public function createAccount()
    {
        return view('accounting.create-account');
    }

    public function storeAccount(Request $request)
    {
        $request->validate([
            'account_code' => 'required|string|max:20|unique:chart_of_accounts',
            'account_name' => 'required|string|max:255',
            'account_type' => 'required|string',
            'normal_balance' => 'required|string',
        ]);

        ChartOfAccount::create([
            'company_id' => auth()->user()->company_id,
            'account_code' => $request->account_code,
            'account_name' => $request->account_name,
            'account_type' => $request->account_type,
            'normal_balance' => $request->normal_balance,
            'is_active' => true,
        ]);

        return redirect()->route('accounting.chart')->with('success', 'Account created successfully!');
    }

    // ==========================================
    // JOURNAL ENTRIES
    // ==========================================

    public function journalEntries()
    {
        $entries = JournalEntry::where('company_id', auth()->user()->company_id)
            ->with('details.account')
            ->latest()
            ->get();
        return view('accounting.journal-entries', compact('entries'));
    }

    public function createJournalEntry()
    {
        $accounts = ChartOfAccount::where('company_id', auth()->user()->company_id)
            ->where('is_active', true)
            ->get();
        return view('accounting.create-journal', compact('accounts'));
    }

    public function storeJournalEntry(Request $request)
    {
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

        $entry = JournalEntry::create([
            'company_id' => auth()->user()->company_id,
            'entry_date' => $request->entry_date,
            'reference' => $request->reference,
            'description' => $request->description,
            'total_debit' => $totalDebit,
            'total_credit' => $totalCredit,
            'status' => 'draft',
        ]);

        foreach ($request->account_id as $key => $accountId) {
            JournalEntryDetail::create([
                'journal_entry_id' => $entry->id,
                'account_id' => $accountId,
                'debit' => $request->debit[$key] ?? 0,
                'credit' => $request->credit[$key] ?? 0,
                'description' => $request->detail_description[$key] ?? null,
            ]);
        }

        return redirect()->route('accounting.journal-entries')->with('success', 'Journal entry created successfully!');
    }

    public function destroyJournalEntry($id)
    {
        $entry = JournalEntry::where('company_id', auth()->user()->company_id)->findOrFail($id);
        $entry->details()->delete();
        $entry->delete();

        return redirect()->route('accounting.journal-entries')->with('success', 'Journal entry deleted!');
    }

    public function postJournalEntry($id)
    {
        $entry = JournalEntry::where('company_id', auth()->user()->company_id)->findOrFail($id);
        $entry->status = 'posted';
        $entry->save();

        return redirect()->route('accounting.journal-entries')->with('success', 'Journal entry posted!');
    }

    // ==========================================
    // REPORTS
    // ==========================================

    public function trialBalance()
    {
        $companyId = auth()->user()->company_id;
        $accounts = ChartOfAccount::where('company_id', $companyId)->where('is_active', true)->get();
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
    }

    public function balanceSheet()
    {
        $companyId = auth()->user()->company_id;
        $accounts = ChartOfAccount::where('company_id', $companyId)->where('is_active', true)->get();
        
        $assets = $accounts->where('account_type', 'asset');
        $liabilities = $accounts->where('account_type', 'liability');
        $equity = $accounts->where('account_type', 'equity');

        return view('accounting.balance-sheet', compact('assets', 'liabilities', 'equity'));
    }

    public function incomeStatement()
    {
        $companyId = auth()->user()->company_id;
        $accounts = ChartOfAccount::where('company_id', $companyId)->where('is_active', true)->get();
        
        $income = $accounts->where('account_type', 'income');
        $expenses = $accounts->where('account_type', 'expense');

        return view('accounting.income-statement', compact('income', 'expenses'));
    }
}