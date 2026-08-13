<?php

namespace App\Http\Controllers;

use App\Models\Collateral;
use App\Models\CollateralType;
use App\Models\Loan;
use App\Models\Member;
use Illuminate\Http\Request;

class CollateralController extends Controller
{
    public function index(Request $request)
    {
        $companyId = auth()->user()->company_id;

        $query = Collateral::with(['loan', 'collateralType', 'member', 'verifier'])
            ->where('company_id', $companyId);

        // Filter by loan
        if ($request->filled('loan_id')) {
            $query->where('loan_id', $request->loan_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->filled('type_id')) {
            $query->where('collateral_type_id', $request->type_id);
        }

        $collaterals = $query->latest()->paginate(20);
        
        // Stats
        $stats = [
            'total' => Collateral::where('company_id', $companyId)->count(),
            'pending' => Collateral::where('company_id', $companyId)->where('status', 'pending')->count(),
            'verified' => Collateral::where('company_id', $companyId)->where('status', 'verified')->count(),
            'released' => Collateral::where('company_id', $companyId)->where('status', 'released')->count(),
        ];

        $types = CollateralType::where('company_id', $companyId)->active()->get();

        return view('collaterals.index', compact('collaterals', 'stats', 'types'));
    }

    public function create(Request $request)
    {
        $companyId = auth()->user()->company_id;
        $types = CollateralType::where('company_id', $companyId)->active()->get();
        $members = Member::where('company_id', $companyId)->get();
        $loans = Loan::where('company_id', $companyId)
            ->whereIn('status', ['pending', 'approved', 'active'])
            ->get();

        // Pre-select loan if provided
        $selectedLoan = null;
        if ($request->filled('loan_id')) {
            $selectedLoan = Loan::find($request->loan_id);
        }

        return view('collaterals.create', compact('types', 'members', 'loans', 'selectedLoan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'loan_id' => 'required|exists:loans,id',
            'collateral_type_id' => 'required|exists:collateral_types,id',
            'member_id' => 'required|exists:members,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'estimated_value' => 'required|numeric|min:0',
            'serial_number' => 'nullable|string|max:255',
            'location' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $collateral = Collateral::create([
            'company_id' => auth()->user()->company_id,
            'loan_id' => $request->loan_id,
            'collateral_type_id' => $request->collateral_type_id,
            'member_id' => $request->member_id,
            'name' => $request->name,
            'description' => $request->description,
            'estimated_value' => $request->estimated_value,
            'serial_number' => $request->serial_number,
            'location' => $request->location,
            'status' => 'pending',
            'notes' => $request->notes,
        ]);

        return redirect()->route('collaterals.show', $collateral)
            ->with('success', 'Collateral added successfully!');
    }

    public function show(Collateral $collateral)
    {
        $collateral->load(['loan', 'collateralType', 'member', 'verifier']);
        return view('collaterals.show', compact('collateral'));
    }

    public function edit(Collateral $collateral)
    {
        $companyId = auth()->user()->company_id;
        $types = CollateralType::where('company_id', $companyId)->active()->get();
        $members = Member::where('company_id', $companyId)->get();
        $loans = Loan::where('company_id', $companyId)
            ->whereIn('status', ['pending', 'approved', 'active'])
            ->get();

        return view('collaterals.edit', compact('collateral', 'types', 'members', 'loans'));
    }

    public function update(Request $request, Collateral $collateral)
    {
        $request->validate([
            'loan_id' => 'required|exists:loans,id',
            'collateral_type_id' => 'required|exists:collateral_types,id',
            'member_id' => 'required|exists:members,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'estimated_value' => 'required|numeric|min:0',
            'serial_number' => 'nullable|string|max:255',
            'location' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $collateral->update([
            'loan_id' => $request->loan_id,
            'collateral_type_id' => $request->collateral_type_id,
            'member_id' => $request->member_id,
            'name' => $request->name,
            'description' => $request->description,
            'estimated_value' => $request->estimated_value,
            'serial_number' => $request->serial_number,
            'location' => $request->location,
            'notes' => $request->notes,
        ]);

        return redirect()->route('collaterals.show', $collateral)
            ->with('success', 'Collateral updated successfully!');
    }

    public function destroy(Collateral $collateral)
    {
        $collateral->delete();
        return redirect()->route('collaterals.index')
            ->with('success', 'Collateral deleted successfully!');
    }

    public function verify(Request $request, Collateral $collateral)
    {
        $request->validate([
            'verified_value' => 'nullable|numeric|min:0',
        ]);

        $collateral->verify(auth()->id(), $request->verified_value);

        return redirect()->route('collaterals.show', $collateral)
            ->with('success', 'Collateral verified successfully!');
    }

    public function reject(Collateral $collateral)
    {
        $collateral->reject();
        return redirect()->route('collaterals.show', $collateral)
            ->with('success', 'Collateral rejected.');
    }

    public function release(Collateral $collateral)
    {
        $collateral->release();
        return redirect()->route('collaterals.show', $collateral)
            ->with('success', 'Collateral released successfully!');
    }
}