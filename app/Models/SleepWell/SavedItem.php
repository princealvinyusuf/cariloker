<?php

namespace App\Models\SleepWell;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SavedItem extends Model
{
    use HasFactory;

    protected $table = 'sleepwell_saved_items';

    protected $fillable = [
        'user_id',
        'item_type',
        'item_ref',
        'title',
        'subtitle',
        'meta',
        'last_played_at',
    ];

    protected $casts = [
        'meta' => 'array',
        'last_played_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
