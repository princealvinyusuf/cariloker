<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
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
        'source_hash',
        'description',
        'requirements',
        'employment_type',
        'openings',
        'posted_at',
        'external_url',
        'gender',
        'physical_condition',
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
        'views',
        'apply_clicks',
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
            'views' => 'integer',
            'apply_clicks' => 'integer',
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

    protected static function booted(): void
    {
        // Exclude expired jobs by default: allow null (no expiry) or valid_until today or later
        static::addGlobalScope('notExpired', function (Builder $query) {
            $query->where(function ($q) {
                $q->whereNull('valid_until')
                  ->orWhereDate('valid_until', '>=', now()->toDateString());
            });
        });
    }

    public function getSanitizedDescriptionHtmlAttribute(): string
    {
        $decodedDescription = html_entity_decode((string) $this->description, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $descriptionWithoutStyles = preg_replace('/\sstyle=(["\']).*?\1/i', '', $decodedDescription);
        $descriptionWithoutAttrs = preg_replace('/\s(?:class|id|dir|lang)=(["\']).*?\1/i', '', (string) $descriptionWithoutStyles);
        $descriptionWithAllowedTags = strip_tags(
            (string) $descriptionWithoutAttrs,
            '<p><br><ul><ol><li><strong><b><em><i><u><h2><h3><h4><blockquote>'
        );
        $sanitizedHtmlDescription = preg_replace('/<([a-z0-9]+)\b[^>]*>/i', '<$1>', (string) $descriptionWithAllowedTags);
        $sanitizedHtmlDescription = preg_replace('/(<br\s*\/?>\s*){3,}/i', '<br><br>', (string) $sanitizedHtmlDescription);

        return trim((string) $sanitizedHtmlDescription);
    }

    public function getPlainDescriptionTextAttribute(): string
    {
        $decodedDescription = html_entity_decode((string) $this->description, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return trim(strip_tags($decodedDescription));
    }
}
