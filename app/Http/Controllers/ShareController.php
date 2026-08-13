<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\ShareHolding;
use App\Models\ShareProduct;
use App\Models\ShareTransaction;
use Illuminate\Http\Request;

class ShareController extends Controller
{
    public function products()
    {
        $products = ShareProduct::where('company_id', auth()->user()->company_id)->get();
        return view('shares.products', compact('products'));
    }

    public function createProduct()
    {
        return view('shares.create-product');
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:share_products',
            'type' => 'required|string',
            'price_per_share' => 'required|numeric|min:0',
            'min_shares' => 'required|integer|min:1',
            'max_shares' => 'nullable|integer|min:1',
            'dividend_rate' => 'nullable|numeric|min:0',
        ]);

        ShareProduct::create([
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
    }

    public function buy()
    {
        $members = Member::where('company_id', auth()->user()->company_id)->get();
        $products = ShareProduct::where('company_id', auth()->user()->company_id)
            ->where('is_active', true)
            ->get();
        return view('shares.buy', compact('members', 'products'));
    }

    public function purchase(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'share_product_id' => 'required|exists:share_products,id',
            'shares' => 'required|integer|min:1',
        ]);

        $product = ShareProduct::find($request->share_product_id);
        $totalAmount = $product->price_per_share * $request->shares;

        ShareHolding::create([
            'member_id' => $request->member_id,
            'share_product_id' => $request->share_product_id,
            'shares' => $request->shares,
            'total_value' => $totalAmount,
            'purchase_date' => now(),
            'status' => 'active',
        ]);

        ShareTransaction::create([
            'member_id' => $request->member_id,
            'share_product_id' => $request->share_product_id,
            'type' => 'buy',
            'shares' => $request->shares,
            'price_per_share' => $product->price_per_share,
            'total_amount' => $totalAmount,
            'transaction_date' => now(),
            'notes' => 'Share purchase',
        ]);

        return redirect()->route('shares.buy')->with('success', 'Shares purchased successfully!');
    }

    public function holdings()
    {
        $holdings = ShareHolding::with(['member', 'shareProduct'])
            ->whereHas('member', function ($q) {
                $q->where('company_id', auth()->user()->company_id);
            })
            ->get();
        return view('shares.holdings', compact('holdings'));
    }
}