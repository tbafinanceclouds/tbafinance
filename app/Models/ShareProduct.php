<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShareProduct extends Model
{
    protected $fillable = [
        'company_id',
        'name',
        'code',
        'type',
        'price_per_share',
        'min_shares',
        'max_shares',
        'dividend_rate',
        'is_active'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function holdings()
    {
        return $this->hasMany(ShareHolding::class);
    }

    public function transactions()
    {
        return $this->hasMany(ShareTransaction::class);
    }
}