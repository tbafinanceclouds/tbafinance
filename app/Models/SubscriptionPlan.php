<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SubscriptionPlan extends Model
{
    protected $table = 'pricing_plans';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'yearly_price',
        'billing_period',
        'features',
        'max_members',
        'max_users',
        'max_features',
        'is_active',
        'is_popular',
        'sort_order',
        'trial_days',
        'promo_code',
        'promo_discount',
        'promo_expires_at'
    ];

    protected $casts = [
        'features' => 'array',
        'price' => 'decimal:2',
        'yearly_price' => 'decimal:2',
        'promo_discount' => 'decimal:2',
        'is_active' => 'boolean',
        'is_popular' => 'boolean',
        'trial_days' => 'integer',
        'promo_expires_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($plan) {
            $plan->slug = Str::slug($plan->name);
        });
    }

    public function getFormattedPriceAttribute()
    {
        return 'UGX ' . number_format($this->price, 0);
    }

    public function getFormattedYearlyPriceAttribute()
    {
        if ($this->yearly_price) {
            return 'UGX ' . number_format($this->yearly_price, 0);
        }
        return null;
    }

    public function getFeaturesListAttribute()
    {
        return is_array($this->features) ? $this->features : [];
    }

    public function getHasTrialAttribute()
    {
        return $this->trial_days > 0;
    }

    public function getHasPromoAttribute()
    {
        return $this->promo_code && $this->promo_discount > 0 && 
               (!$this->promo_expires_at || $this->promo_expires_at > now());
    }

    public function getSavingsAttribute()
    {
        if ($this->yearly_price) {
            $yearlyCost = $this->price * 12;
            $savings = $yearlyCost - $this->yearly_price;
            return $savings > 0 ? round(($savings / $yearlyCost) * 100) : 0;
        }
        return 0;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePopular($query)
    {
        return $query->where('is_popular', true);
    }
}