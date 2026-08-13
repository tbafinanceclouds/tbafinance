<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    protected $fillable = [
        'company_id',
        'entry_date',
        'reference',
        'description',
        'total_debit',
        'total_credit',
        'status'
    ];

    protected $casts = [
        'entry_date' => 'date',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function details()
    {
        return $this->hasMany(JournalEntryDetail::class);
    }

    public function isBalanced()
    {
        return $this->total_debit === $this->total_credit;
    }
}