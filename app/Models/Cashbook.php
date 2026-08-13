<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cashbook extends Model
{
    protected $table = 'cashbook';

    protected $fillable = [
        'company_id',
        'transaction_date',
        'type',
        'category',
        'reference',
        'description',
        'amount',
        'balance',
        'payment_method',
        'status',
        'created_by'
    ];

    protected $casts = [
        'transaction_date' => 'date',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getTypeLabelAttribute()
    {
        return $this->type === 'cash_in' ? 'Cash In' : 'Cash Out';
    }

    public function getCategoryLabelAttribute()
    {
        $categories = [
            'deposit' => 'Member Deposit',
            'loan_repayment' => 'Loan Repayment',
            'income' => 'Income',
            'withdrawal' => 'Member Withdrawal',
            'expense' => 'Expense',
            'loan_disbursement' => 'Loan Disbursement',
        ];
        return $categories[$this->category] ?? $this->category;
    }

    public function getAmountWithSignAttribute()
    {
        if ($this->type === 'cash_in') {
            return '+' . number_format($this->amount, 2);
        }
        return '-' . number_format($this->amount, 2);
    }
}