<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Skill extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug'];

    public function jobs(): BelongsToMany
    {
        return $this->belongsToMany(Job::class, 'job_listing_skill', 'skill_id', 'job_listing_id')
            ->withTimestamps();
    }
}
