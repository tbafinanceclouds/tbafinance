<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanProduct extends Model
{
    protected $fillable = [
        'company_id',
        'name',
        'type',
        'interest_rate',
        'max_term_months',
        'max_amount',
        'processing_fee',
        'requires_guarantor',
        'min_guarantors',
        'max_guarantors',
        'is_active',
        'description'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'requires_guarantor' => 'boolean',
        'interest_rate' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'processing_fee' => 'decimal:2'
    ];

    // Relationships
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function getFormattedInterestRateAttribute()
    {
        return $this->interest_rate . '%';
    }

    public function getFormattedMaxAmountAttribute()
    {
        return 'UGX ' . number_format($this->max_amount, 2);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRequiresGuarantor($query)
    {
        return $query->where('requires_guarantor', true);
    }
}