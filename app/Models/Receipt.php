<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    protected $fillable = [
        'company_id',
        'member_id',
        'receipt_number',
        'type',
        'amount',
        'payment_method',
        'reference',
        'description',
        'created_by',
        'receipt_date'
    ];

    protected $casts = [
        'receipt_date' => 'date',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getTypeLabelAttribute()
    {
        $types = [
            'deposit' => 'Savings Deposit',
            'withdrawal' => 'Savings Withdrawal',
            'loan_repayment' => 'Loan Repayment',
            'share_purchase' => 'Share Purchase',
        ];
        return $types[$this->type] ?? $this->type;
    }

    public function getAmountInWordsAttribute()
    {
        return $this->numberToWords($this->amount);
    }

    private function numberToWords($number)
    {
        $number = (int)$number;
        
        $words = [
            0 => 'Zero', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four',
            5 => 'Five', 6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
            10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen',
            14 => 'Fourteen', 15 => 'Fifteen', 16 => 'Sixteen', 17 => 'Seventeen',
            18 => 'Eighteen', 19 => 'Nineteen', 20 => 'Twenty',
            30 => 'Thirty', 40 => 'Forty', 50 => 'Fifty',
            60 => 'Sixty', 70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety',
            100 => 'Hundred', 1000 => 'Thousand'
        ];

        if ($number <= 20) {
            return $words[$number];
        }

        if ($number < 100) {
            $tens = floor($number / 10) * 10;
            $ones = $number % 10;
            return $words[$tens] . ($ones ? ' ' . $words[$ones] : '');
        }

        if ($number < 1000) {
            $hundreds = floor($number / 100);
            $remainder = $number % 100;
            return $words[$hundreds] . ' Hundred' . ($remainder ? ' ' . $this->numberToWords($remainder) : '');
        }

        if ($number < 1000000) {
            $thousands = floor($number / 1000);
            $remainder = $number % 1000;
            return $this->numberToWords($thousands) . ' Thousand' . ($remainder ? ' ' . $this->numberToWords($remainder) : '');
        }

        return number_format($number, 2);
    }
}