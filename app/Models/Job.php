<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Job extends Model
{
    use HasFactory;
    protected $table = 'job_listings';

    protected $fillable = [
        'company_id',
        'category_id',
        'location_id',
        'title',
        'slug',
        'description',
        'employment_type',
        'openings',
        'posted_at',
        'external_url',
        'gender',
        'work_arrangement',
        'seniority_level',
        'education_level',
        'experience_min',
        'experience_max',
        'salary_min',
        'salary_max',
        'salary_currency',
        'is_remote',
        'status',
        'valid_until',
    ];

    /**
     * Attribute casting rules
     */
    protected function casts(): array
    {
        return [
            'posted_at' => 'datetime',
            'valid_until' => 'datetime',
            'is_remote' => 'boolean',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(JobCategory::class, 'category_id');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'job_listing_skill', 'job_listing_id', 'skill_id')
            ->withTimestamps();
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'job_listing_id');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
