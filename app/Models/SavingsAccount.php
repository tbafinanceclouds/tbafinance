<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavingsAccount extends Model
{
    protected $fillable = [
        'member_id',
        'savings_product_id',
        'account_number',
        'balance',
        'is_active'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function savingsProduct()
    {
        return $this->belongsTo(SavingsProduct::class);
    }

    public function transactions()
    {
        return $this->hasMany(SavingsTransaction::class);
    }

    public function deposit($amount, $description = null)
    {
        $this->balance += $amount;
        $this->save();

        // Create transaction
        $transaction = SavingsTransaction::create([
            'savings_account_id' => $this->id,
            'type' => 'deposit',
            'amount' => $amount,
            'balance_after' => $this->balance,
            'description' => $description,
        ]);

        // Create receipt
        Receipt::create([
            'company_id' => $this->member->company_id,
            'member_id' => $this->member_id,
            'receipt_number' => 'RCP-' . date('Ymd') . '-' . strtoupper(uniqid()),
            'type' => 'deposit',
            'amount' => $amount,
            'payment_method' => 'cash',
            'description' => $description ?? 'Savings deposit',
            'created_by' => auth()->id(),
            'receipt_date' => now(),
        ]);

        return $transaction;
    }

    public function withdraw($amount, $description = null)
    {
        if ($amount > $this->balance) {
            throw new \Exception('Insufficient balance');
        }

        $this->balance -= $amount;
        $this->save();

        // Create transaction
        $transaction = SavingsTransaction::create([
            'savings_account_id' => $this->id,
            'type' => 'withdrawal',
            'amount' => $amount,
            'balance_after' => $this->balance,
            'description' => $description,
        ]);

        // Create receipt
        Receipt::create([
            'company_id' => $this->member->company_id,
            'member_id' => $this->member_id,
            'receipt_number' => 'RCP-' . date('Ymd') . '-' . strtoupper(uniqid()),
            'type' => 'withdrawal',
            'amount' => $amount,
            'payment_method' => 'cash',
            'description' => $description ?? 'Savings withdrawal',
            'created_by' => auth()->id(),
            'receipt_date' => now(),
        ]);

        return $transaction;
    }
}