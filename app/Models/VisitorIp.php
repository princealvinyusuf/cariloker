<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitorIp extends Model
{
    protected $fillable = [
        'ip_address',
        'first_visited_at',
        'last_visited_at',
        'visit_count',
    ];

    protected function casts(): array
    {
        return [
            'first_visited_at' => 'datetime',
            'last_visited_at' => 'datetime',
            'visit_count' => 'integer',
        ];
    }
}
