<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShareTransaction extends Model
{
    protected $fillable = [
        'member_id',
        'share_product_id',
        'type',
        'shares',
        'price_per_share',
        'total_amount',
        'transaction_date',
        'notes'
    ];

    protected $casts = [
        'transaction_date' => 'date',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function shareProduct()
    {
        return $this->belongsTo(ShareProduct::class);
    }

    // Add this method to create receipt when shares are purchased
    public static function createReceipt($memberId, $shareProductId, $shares, $totalAmount)
    {
        $member = Member::find($memberId);
        
        Receipt::create([
            'company_id' => $member->company_id,
            'member_id' => $memberId,
            'receipt_number' => 'RCP-' . date('Ymd') . '-' . strtoupper(uniqid()),
            'type' => 'share_purchase',
            'amount' => $totalAmount,
            'payment_method' => 'cash',
            'description' => 'Purchase of ' . $shares . ' shares from ' . ShareProduct::find($shareProductId)->name,
            'created_by' => auth()->id(),
            'receipt_date' => now(),
        ]);
    }
}