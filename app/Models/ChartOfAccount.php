<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChartOfAccount extends Model
{
    protected $table = 'chart_of_accounts';

    protected $fillable = [
        'company_id',
        'account_code',
        'account_name',
        'account_type',
        'normal_balance',
        'is_active'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function journalDetails()
    {
        return $this->hasMany(JournalEntryDetail::class, 'account_id');
    }

    public function getBalanceAttribute()
    {
        $totalDebit = $this->journalDetails()->sum('debit');
        $totalCredit = $this->journalDetails()->sum('credit');

        if ($this->normal_balance === 'debit') {
            return $totalDebit - $totalCredit;
        } else {
            return $totalCredit - $totalDebit;
        }
    }
}