<?php

namespace App\Http\Controllers;

use App\Models\Guarantor;
use App\Models\Loan;
use App\Models\Member;
use Illuminate\Http\Request;

class GuarantorController extends Controller
{
    public function index(Request $request)
    {
        $companyId = auth()->user()->company_id;

        $query = Guarantor::with(['loan', 'member'])
            ->where('company_id', $companyId);

        // Filter by loan ID
        if ($request->filled('loan_id')) {
            $query->where('loan_id', $request->loan_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $guarantors = $query->latest()->paginate(20);

        return view('guarantors.index', compact('guarantors'));
    }

    public function create(Loan $loan = null)
    {
        $companyId = auth()->user()->company_id;
        
        // Get all members (except those already guarantors for this loan)
        $members = Member::where('company_id', $companyId)
            ->whereDoesntHave('guarantorLoans', function ($q) use ($loan) {
                if ($loan) {
                    $q->where('loan_id', $loan->id);
                }
            })
            ->get();

        // ✅ FIXED: Removed company_id from loans query
        $loans = Loan::whereIn('status', ['pending', 'approved'])->get();

        return view('guarantors.create', compact('members', 'loans', 'loan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'loan_id' => 'required|exists:loans,id',
            'member_id' => 'required|exists:members,id',
            'relationship' => 'required|string|max:255',
            'amount_guaranteed' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        // Check if member is already a guarantor for this loan
        $exists = Guarantor::where('loan_id', $request->loan_id)
            ->where('member_id', $request->member_id)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->with('error', 'This member is already a guarantor for this loan.')
                ->withInput();
        }

        $guarantor = Guarantor::create([
            'company_id' => auth()->user()->company_id,
            'loan_id' => $request->loan_id,
            'member_id' => $request->member_id,
            'relationship' => $request->relationship,
            'amount_guaranteed' => $request->amount_guaranteed,
            'status' => 'pending',
            'notes' => $request->notes,
            'guarantee_date' => now(),
        ]);

        return redirect()->route('guarantors.show', $guarantor)
            ->with('success', 'Guarantor added successfully!');
    }

    public function show(Guarantor $guarantor)
    {
        $guarantor->load(['loan', 'member', 'approver']);
        return view('guarantors.show', compact('guarantor'));
    }

    public function edit(Guarantor $guarantor)
    {
        return view('guarantors.edit', compact('guarantor'));
    }

    public function update(Request $request, Guarantor $guarantor)
    {
        $request->validate([
            'relationship' => 'required|string|max:255',
            'amount_guaranteed' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $guarantor->update($request->only(['relationship', 'amount_guaranteed', 'notes']));

        return redirect()->route('guarantors.show', $guarantor)
            ->with('success', 'Guarantor updated successfully!');
    }

    public function approve(Request $request, Guarantor $guarantor)
    {
        $guarantor->approve(auth()->id());

        return redirect()->route('guarantors.show', $guarantor)
            ->with('success', 'Guarantor approved successfully!');
    }

    public function reject(Request $request, Guarantor $guarantor)
    {
        $guarantor->reject();

        return redirect()->route('guarantors.show', $guarantor)
            ->with('success', 'Guarantor rejected.');
    }

    public function destroy(Guarantor $guarantor)
    {
        $guarantor->delete();
        return redirect()->route('guarantors.index')
            ->with('success', 'Guarantor removed successfully!');
    }

    // Check if loan has enough approved guarantors
    public function checkLoanGuarantors(Loan $loan)
    {
        $approvedCount = $loan->guarantors()->where('status', 'approved')->count();
        $requiredCount = $loan->loanProduct->min_guarantors ?? 0;

        return response()->json([
            'approved' => $approvedCount,
            'required' => $requiredCount,
            'has_enough' => $approvedCount >= $requiredCount
        ]);
    }
}