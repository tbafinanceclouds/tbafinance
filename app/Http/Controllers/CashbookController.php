<?php

namespace App\Http\Controllers;

use App\Models\Cashbook;
use Illuminate\Http\Request;

class CashbookController extends Controller
{
    public function index()
    {
        $transactions = Cashbook::where('company_id', auth()->user()->company_id)
            ->with('creator')
            ->latest()
            ->paginate(50);
        
        $balance = Cashbook::where('company_id', auth()->user()->company_id)
            ->latest()
            ->value('balance') ?? 0;
        
        $todayTransactions = Cashbook::where('company_id', auth()->user()->company_id)
            ->whereDate('transaction_date', now())
            ->get();
        
        $todayCashIn = $todayTransactions->where('type', 'cash_in')->sum('amount');
        $todayCashOut = $todayTransactions->where('type', 'cash_out')->sum('amount');

        return view('cashbook.index', compact('transactions', 'balance', 'todayCashIn', 'todayCashOut'));
    }

    public function create()
    {
        return view('cashbook.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'transaction_date' => 'required|date',
            'type' => 'required|in:cash_in,cash_out',
            'category' => 'required|string',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string',
        ]);

        $lastBalance = Cashbook::where('company_id', auth()->user()->company_id)
            ->latest()
            ->value('balance') ?? 0;

        if ($request->type === 'cash_in') {
            $newBalance = $lastBalance + $request->amount;
        } else {
            $newBalance = $lastBalance - $request->amount;
        }

        Cashbook::create([
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
    }

    public function show($id)
    {
        $transaction = Cashbook::where('company_id', auth()->user()->company_id)
            ->with('creator')
            ->findOrFail($id);
        return view('cashbook.show', compact('transaction'));
    }

    public function destroy($id)
    {
        $transaction = Cashbook::where('company_id', auth()->user()->company_id)->findOrFail($id);
        $transaction->delete();
        return redirect()->route('cashbook.index')->with('success', 'Transaction deleted!');
    }
}