<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\SavingsAccount;
use App\Models\SavingsProduct;
use App\Models\SavingsTransaction;
use Illuminate\Http\Request;

class SavingsController extends Controller
{
    public function products()
    {
        $products = SavingsProduct::where('company_id', auth()->user()->company_id)->get();
        return view('savings.products', compact('products'));
    }

    public function createProduct()
    {
        return view('savings.create-product');
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'interest_rate' => 'nullable|numeric',
            'minimum_balance' => 'nullable|numeric',
        ]);

        SavingsProduct::create([
            'company_id' => auth()->user()->company_id,
            'name' => $request->name,
            'type' => $request->type,
            'interest_rate' => $request->interest_rate ?? 0,
            'minimum_balance' => $request->minimum_balance ?? 0,
            'is_active' => true,
        ]);

        return redirect()->route('savings.products')->with('success', 'Product created successfully!');
    }

    public function accounts()
    {
        $accounts = SavingsAccount::with(['member', 'savingsProduct'])
            ->whereHas('member', function ($query) {
                $query->where('company_id', auth()->user()->company_id);
            })
            ->get();
        return view('savings.accounts', compact('accounts'));
    }

    public function createAccount()
    {
        $members = Member::where('company_id', auth()->user()->company_id)->get();
        $products = SavingsProduct::where('company_id', auth()->user()->company_id)
            ->where('is_active', true)
            ->get();
        return view('savings.create-account', compact('members', 'products'));
    }

    public function storeAccount(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'savings_product_id' => 'required|exists:savings_products,id',
        ]);

        $accountNumber = 'SAV-' . strtoupper(uniqid());

        SavingsAccount::create([
            'member_id' => $request->member_id,
            'savings_product_id' => $request->savings_product_id,
            'account_number' => $accountNumber,
            'balance' => 0,
            'is_active' => true,
        ]);

        return redirect()->route('savings.accounts')->with('success', 'Account created successfully!');
    }

    public function showAccount($id)
    {
        $account = SavingsAccount::with(['member', 'savingsProduct', 'transactions'])
            ->findOrFail($id);
        return view('savings.show-account', compact('account'));
    }

    public function deposit(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string',
        ]);

        $account = SavingsAccount::findOrFail($id);
        $account->deposit($request->amount, $request->description);

        return redirect()->route('savings.show', $account->id)
            ->with('success', 'Deposit successful!');
    }

    public function withdraw(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string',
        ]);

        $account = SavingsAccount::findOrFail($id);

        try {
            $account->withdraw($request->amount, $request->description);
            return redirect()->route('savings.show', $account->id)
                ->with('success', 'Withdrawal successful!');
        } catch (\Exception $e) {
            return redirect()->route('savings.show', $account->id)
                ->with('error', $e->getMessage());
        }
    }
}