<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Resume extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'title', 'file_path', 'is_default'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
