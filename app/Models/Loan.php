<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
        'company_id',
        'member_id',
        'loan_product_id',
        'amount',
        'interest_rate',
        'total_repayable',
        'term_months',
        'status',
        'balance',
        'approved_at',
        'disbursed_at',
        'completed_at',
        'notes',
        'processing_fee'
    ];

    protected $casts = [
        'approved_at' => 'date',
        'disbursed_at' => 'date',
        'completed_at' => 'date',
        'amount' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'total_repayable' => 'decimal:2',
        'balance' => 'decimal:2',
        'processing_fee' => 'decimal:2'
    ];

    // ==========================================
    // RELATIONSHIPS
    // ==========================================

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function loanProduct()
    {
        return $this->belongsTo(LoanProduct::class);
    }

    public function repayments()
    {
        return $this->hasMany(LoanRepayment::class);
    }

    public function guarantors()
    {
        return $this->hasMany(Guarantor::class);
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'related');
    }

    // ✅ COLLATERAL RELATIONSHIP (MOVED INSIDE CLASS)
    public function collaterals()
    {
        return $this->hasMany(Collateral::class);
    }

    // ==========================================
    // ATTRIBUTES
    // ==========================================

    public function getTotalPaidAttribute()
    {
        return $this->repayments()->sum('amount_paid');
    }

    public function getIsOverdueAttribute()
    {
        return $this->repayments()
            ->where('status', 'pending')
            ->where('due_date', '<', now())
            ->exists();
    }

    public function hasApprovedGuarantors()
    {
        return $this->guarantors()->where('status', 'approved')->count() > 0;
    }

    public function getTotalGuaranteedAmountAttribute()
    {
        return $this->guarantors()->where('status', 'approved')->sum('amount_guaranteed');
    }

    public function getDocumentCountAttribute()
    {
        return $this->documents()->count();
    }

    // ✅ COLLATERAL ATTRIBUTES (MOVED INSIDE CLASS)
    public function hasVerifiedCollateral()
    {
        return $this->collaterals()->where('status', 'verified')->exists();
    }

    public function getTotalCollateralValueAttribute()
    {
        return $this->collaterals()->where('status', 'verified')->sum('verified_value');
    }

    public function getCollateralCountAttribute()
    {
        return $this->collaterals()->count();
    }

    // ==========================================
    // BUSINESS LOGIC
    // ==========================================

    public function approve()
    {
        $this->status = 'approved';
        $this->approved_at = now();
        $this->save();
        
        $this->generateRepaymentSchedule();
    }

    public function disburse()
    {
        $this->status = 'disbursed';
        $this->disbursed_at = now();
        $this->balance = $this->total_repayable;
        $this->save();
    }

    public function generateRepaymentSchedule()
    {
        $installmentAmount = $this->total_repayable / $this->term_months;
        $startDate = now()->addDays(30);

        for ($i = 1; $i <= $this->term_months; $i++) {
            LoanRepayment::create([
                'loan_id' => $this->id,
                'installment_number' => $i,
                'amount_due' => $installmentAmount,
                'amount_paid' => 0,
                'due_date' => $startDate->copy()->addMonths($i - 1),
                'status' => 'pending',
                'penalty' => 0,
            ]);
        }
    }

    // ==========================================
    // SCOPES
    // ==========================================

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeOverdue($query)
    {
        return $query->whereHas('repayments', function ($q) {
            $q->where('status', 'pending')
              ->where('due_date', '<', now());
        });
    }
}