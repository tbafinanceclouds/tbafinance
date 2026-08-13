<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavingsTransaction extends Model
{
    protected $fillable = [
        'savings_account_id',
        'type',
        'amount',
        'balance_after',
        'description',
        'reference'
    ];

    public function savingsAccount()
    {
        return $this->belongsTo(SavingsAccount::class);
    }
}