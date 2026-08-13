<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'member_number',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'date_of_birth',
        'national_id',
        'occupation',
        'monthly_income',
        'join_date',
        'status'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'join_date' => 'date',
        'monthly_income' => 'decimal:2'
    ];

    // ==========================================
    // RELATIONSHIPS
    // ==========================================

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function savingsAccounts()
    {
        return $this->hasMany(SavingsAccount::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function guarantors()
    {
        return $this->hasMany(Guarantor::class, 'member_id');
    }

    public function guarantorLoans()
    {
        return $this->belongsToMany(Loan::class, 'guarantors', 'member_id', 'loan_id');
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'related');
    }

    // ✅ Collateral relationship (MOVED INSIDE CLASS)
    public function collaterals()
    {
        return $this->hasMany(Collateral::class);
    }

    // ==========================================
    // ATTRIBUTES
    // ==========================================

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getDocumentCountAttribute()
    {
        return $this->documents()->count();
    }

    // ✅ Collateral count attribute (MOVED INSIDE CLASS)
    public function getCollateralCountAttribute()
    {
        return $this->collaterals()->count();
    }

    // ==========================================
    // SCOPES
    // ==========================================

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeSuspended($query)
    {
        return $query->where('status', 'suspended');
    }

    // ==========================================
    // BOOT
    // ==========================================

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($member) {
            if (empty($member->member_number)) {
                $lastMember = static::where('company_id', $member->company_id)
                    ->orderBy('id', 'desc')
                    ->first();
                
                $nextNumber = $lastMember ? 
                    intval(substr($lastMember->member_number, -5)) + 1 : 
                    1;
                
                $member->member_number = 'MEM-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
            }
        });
    }
}