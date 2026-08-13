<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanRepayment extends Model
{
    protected $fillable = [
        'loan_id',
        'installment_number',
        'amount_due',
        'amount_paid',
        'due_date',
        'paid_date',
        'status',
        'penalty'
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_date' => 'date',
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function getBalanceAttribute()
    {
        return $this->amount_due - $this->amount_paid + $this->penalty;
    }

    public function pay($amount)
    {
        $this->amount_paid += $amount;
        $this->paid_date = now();
        
        if ($this->amount_paid >= $this->amount_due + $this->penalty) {
            $this->status = 'paid';
        }
        
        $this->save();

        // Create receipt for loan repayment
        Receipt::create([
            'company_id' => $this->loan->member->company_id,
            'member_id' => $this->loan->member_id,
            'receipt_number' => 'RCP-' . date('Ymd') . '-' . strtoupper(uniqid()),
            'type' => 'loan_repayment',
            'amount' => $amount,
            'payment_method' => 'cash',
            'description' => 'Loan repayment - Installment #' . $this->installment_number . ' for loan #' . $this->loan_id,
            'created_by' => auth()->id(),
            'receipt_date' => now(),
        ]);

        $loan = $this->loan;
        $loan->balance -= $amount;
        
        if ($loan->balance <= 0) {
            $loan->status = 'completed';
            $loan->completed_at = now();
        }
        
        $loan->save();
    }
}