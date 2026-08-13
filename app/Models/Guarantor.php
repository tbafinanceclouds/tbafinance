<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guarantor extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'loan_id',
        'member_id',
        'relationship',
        'amount_guaranteed',
        'status',
        'notes',
        'guarantee_date',
        'approval_date',
        'approved_by'
    ];

    protected $casts = [
        'guarantee_date' => 'date',
        'approval_date' => 'date',
        'amount_guaranteed' => 'decimal:2'
    ];

    // ==========================================
    // RELATIONSHIPS
    // ==========================================

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // ✅ DOCUMENT RELATIONSHIP (ADDED)
    public function documents()
    {
        return $this->morphMany(Document::class, 'related');
    }

    // ==========================================
    // ATTRIBUTES
    // ==========================================

    // ✅ DOCUMENT COUNT ATTRIBUTE (ADDED)
    public function getDocumentCountAttribute()
    {
        return $this->documents()->count();
    }

    // ==========================================
    // SCOPES
    // ==========================================

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // ==========================================
    // METHODS
    // ==========================================

    public function approve($userId)
    {
        $this->status = 'approved';
        $this->approval_date = now();
        $this->approved_by = $userId;
        $this->save();
    }

    public function reject()
    {
        $this->status = 'rejected';
        $this->save();
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }
}