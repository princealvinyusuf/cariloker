<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrivacyPolicyContent extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'label',
        'section',
        'order',
    ];

    /**
     * Get content by key
     */
    public static function getContent($key, $default = '')
    {
        $content = self::where('key', $key)->first();
        return $content ? $content->value : $default;
    }

    /**
     * Set or update content
     */
    public static function setContent($key, $value, $label = null, $type = 'text', $section = 'general')
    {
        return self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'label' => $label ?? ucfirst(str_replace('_', ' ', $key)),
                'type' => $type,
                'section' => $section,
            ]
        );
    }

    /**
     * Get content by section
     */
    public static function getBySection($section)
    {
        return self::where('section', $section)->orderBy('order')->get();
    }
}
