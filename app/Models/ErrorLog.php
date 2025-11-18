<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErrorLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'status_code',
        'method',
        'path',
        'route_name',
        'ip_address',
        'user_id',
        'user_agent',
        'message',
        'context',
    ];

    protected function casts(): array
    {
        return [
            'status_code' => 'integer',
            'user_id' => 'integer',
            'context' => 'array',
        ];
    }
}


