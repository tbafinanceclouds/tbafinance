<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;

class Company extends Model implements Authenticatable
{
    use AuthenticatableTrait;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'logo',
        'currency',
        'subscription_expiry',
        'is_active',
        'primary_color',
        'secondary_color',
        'subscription_plan',
        'trial_started_at',
        // ✅ NEW FIELDS FOR BUSINESS CLIENT SIGNUP
        'password',
        'contact_person',
        'business_type',
        'registration_number',
        'email_verified_at',
        'is_approved',
        'approved_at',
        'remember_token',
    ];

    protected $casts = [
        'subscription_expiry' => 'datetime',
        'trial_started_at' => 'datetime',
        'email_verified_at' => 'datetime',
        'approved_at' => 'datetime',
        'is_active' => 'boolean',
        'is_approved' => 'boolean',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // ==========================================
    // RELATIONSHIPS
    // ==========================================

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function members()
    {
        return $this->hasMany(Member::class);
    }

    public function subscriptionPlan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan', 'slug');
    }

    // ==========================================
    // AUTHENTICATION METHODS
    // ==========================================

    public function isApproved()
    {
        return $this->is_approved && $this->approved_at;
    }

    public function approve()
    {
        $this->is_approved = true;
        $this->approved_at = now();
        $this->is_active = true;
        $this->save();
        return $this;
    }

    public function reject()
    {
        $this->is_approved = false;
        $this->approved_at = null;
        $this->is_active = false;
        $this->save();
        return $this;
    }

    public function isActive()
    {
        return $this->is_active && $this->isApproved();
    }

    public function hasCompletedRegistration()
    {
        return !empty($this->password) && $this->isApproved();
    }

    // ==========================================
    // ATTRIBUTES
    // ==========================================

    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            return asset('storage/' . $this->logo);
        }
        return null;
    }

    public function isTrialActive()
    {
        if (!$this->trial_started_at) {
            return false;
        }
        return $this->trial_started_at->addDays(14) > now();
    }

    public function isSubscriptionActive()
    {
        if (!$this->subscription_expiry) {
            return false;
        }
        return $this->subscription_expiry > now();
    }

    public function getPlanNameAttribute()
    {
        $plans = [
            'starter' => 'Starter',
            'professional' => 'Professional',
            'enterprise' => 'Enterprise',
        ];
        return $plans[$this->subscription_plan] ?? 'Starter';
    }

    public function getPlanPriceAttribute()
    {
        $prices = [
            'starter' => 80000,
            'professional' => 180000,
            'enterprise' => 350000,
        ];
        return $prices[$this->subscription_plan] ?? 80000;
    }

    public function getMemberCountAttribute()
    {
        return $this->members()->count();
    }

    public function getStatusBadgeAttribute()
    {
        if (!$this->is_approved) {
            return ['text' => 'Pending Approval', 'color' => 'yellow'];
        }
        if (!$this->is_active) {
            return ['text' => 'Suspended', 'color' => 'red'];
        }
        if ($this->subscription_expiry && $this->subscription_expiry < now()) {
            return ['text' => 'Expired', 'color' => 'red'];
        }
        return ['text' => 'Active', 'color' => 'green'];
    }

    public function getFormattedRegistrationNumberAttribute()
    {
        return $this->registration_number ?? 'N/A';
    }

    public function getBusinessTypeLabelAttribute()
    {
        $types = [
            'SACCO' => 'SACCO',
            'Microfinance' => 'Microfinance',
            'Cooperative' => 'Cooperative',
            'Other' => 'Other',
        ];
        return $types[$this->business_type] ?? 'Not Set';
    }
}