<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShareHolding extends Model
{
    protected $fillable = [
        'member_id',
        'share_product_id',
        'shares',
        'total_value',
        'purchase_date',
        'status'
    ];

    protected $casts = [
        'purchase_date' => 'date',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function shareProduct()
    {
        return $this->belongsTo(ShareProduct::class);
    }
}