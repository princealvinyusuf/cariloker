<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ErrorLog extends Model
{
    protected $fillable = [
        'status_code',
        'method',
        'url',
        'route',
        'message',
        'ip_address',
        'user_agent',
        'count',
        'first_occurred_at',
        'last_occurred_at',
    ];

    protected function casts(): array
    {
        return [
            'status_code' => 'integer',
            'count' => 'integer',
            'first_occurred_at' => 'datetime',
            'last_occurred_at' => 'datetime',
        ];
    }
}
