<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Collateral extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'loan_id',
        'collateral_type_id',
        'member_id',
        'name',
        'description',
        'estimated_value',
        'verified_value',
        'serial_number',
        'location',
        'status',
        'verification_date',
        'verified_by',
        'notes',
        'documents',
    ];

    protected $casts = [
        'estimated_value' => 'decimal:2',
        'verified_value' => 'decimal:2',
        'verification_date' => 'date',
        'documents' => 'array',
    ];

    // Relationships
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function collateralType()
    {
        return $this->belongsTo(CollateralType::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    public function scopeReleased($query)
    {
        return $query->where('status', 'released');
    }

    // Methods
    public function verify($userId, $verifiedValue = null)
    {
        $this->status = 'verified';
        $this->verification_date = now();
        $this->verified_by = $userId;
        if ($verifiedValue) {
            $this->verified_value = $verifiedValue;
        }
        $this->save();
    }

    public function reject()
    {
        $this->status = 'rejected';
        $this->save();
    }

    public function release()
    {
        $this->status = 'released';
        $this->save();
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isVerified()
    {
        return $this->status === 'verified';
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'verified' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            'released' => 'bg-blue-100 text-blue-800',
        ];
        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getStatusLabelAttribute()
    {
        return ucfirst($this->status);
    }

    public function getFormattedEstimatedValueAttribute()
    {
        return 'UGX ' . number_format($this->estimated_value, 2);
    }

    public function getFormattedVerifiedValueAttribute()
    {
        return $this->verified_value ? 'UGX ' . number_format($this->verified_value, 2) : 'N/A';
    }

    public function getDocumentsArrayAttribute()
    {
        return $this->documents ?? [];
    }
}