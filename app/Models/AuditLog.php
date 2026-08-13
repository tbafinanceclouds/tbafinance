<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'company_id',
        'action',
        'module',
        'description',
        'old_data',
        'new_data',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function getActionColorAttribute()
    {
        $colors = [
            'login' => 'green',
            'logout' => 'gray',
            'create' => 'blue',
            'update' => 'yellow',
            'delete' => 'red',
            'approve' => 'green',
            'reject' => 'red',
            'disburse' => 'blue',
            'deposit' => 'green',
            'withdraw' => 'orange',
            'repay' => 'blue',
        ];
        return $colors[$this->action] ?? 'gray';
    }
}