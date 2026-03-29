<?php

namespace App\Http\Controllers\Api\SleepWell;

use App\Http\Controllers\Controller;
use App\Models\AboutPageContent;
use App\Models\Faq;
use App\Models\PrivacyPolicyContent;
use App\Models\TermsOfServiceContent;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class LegalContentController extends Controller
{
    public function show(string $slug): JsonResponse
    {
        return match ($slug) {
            'about' => $this->contentFromModel(
                slug: 'about',
                fallbackTitle: 'About SleepWell',
                modelClass: AboutPageContent::class,
                seederClass: 'AboutPageContentSeeder',
            ),
            'privacy' => $this->contentFromModel(
                slug: 'privacy',
                fallbackTitle: 'Privacy Policy',
                modelClass: PrivacyPolicyContent::class,
                seederClass: 'PrivacyPolicyContentSeeder',
            ),
            'terms' => $this->contentFromModel(
                slug: 'terms',
                fallbackTitle: 'Terms of Service',
                modelClass: TermsOfServiceContent::class,
                seederClass: 'TermsOfServiceContentSeeder',
            ),
            'help' => $this->contentFromFaq(),
            default => response()->json(['message' => 'Not found.'], 404),
        };
    }

    private function contentFromModel(
        string $slug,
        string $fallbackTitle,
        string $modelClass,
        string $seederClass,
    ): JsonResponse {
        /** @var Collection<int, object> $rows */
        $rows = $modelClass::query()
            ->orderBy('section')
            ->orderBy('order')
            ->get(['key', 'value', 'label', 'section', 'updated_at']);

        if ($rows->isEmpty()) {
            Artisan::call('db:seed', ['--class' => $seederClass]);
            $rows = $modelClass::query()
                ->orderBy('section')
                ->orderBy('order')
                ->get(['key', 'value', 'label', 'section', 'updated_at']);
        }

        $title = $rows->firstWhere('key', 'hero_title')->value ?? $fallbackTitle;
        $lastUpdated = $rows->firstWhere('key', 'last_updated')->value ?? $rows->max('updated_at');

        $mappedByKey = $rows->keyBy('key');
        $blocks = $rows
            ->filter(function ($row) {
                $key = trim((string) $row->key);
                $value = trim((string) $row->value);
                return $value !== ''
                    && $key !== 'hero_title'
                    && $key !== 'last_updated'
                    && !Str::endsWith($key, '_title');
            })
            ->map(function ($row) use ($mappedByKey) {
                $key = trim((string) $row->key);
                $headingKey = Str::endsWith($key, '_content')
                    ? Str::replaceLast('_content', '_title', $key)
                    : $key . '_title';

                $heading = $mappedByKey[$headingKey]->value
                    ?? $row->label
                    ?? Str::headline($key);

                return [
                    'heading' => trim((string) $heading),
                    'body' => trim((string) $row->value),
                    'section' => trim((string) $row->section),
                ];
            })
            ->values()
            ->all();

        return response()->json([
            'slug' => $slug,
            'title' => $title,
            'updated_at' => is_string($lastUpdated) ? $lastUpdated : optional($lastUpdated)?->toIso8601String(),
            'blocks' => $blocks,
        ]);
    }

    private function contentFromFaq(): JsonResponse
    {
        $faqs = Faq::query()
            ->active()
            ->ordered()
            ->get(['question', 'answer', 'updated_at']);

        if ($faqs->isEmpty()) {
            $blocks = [[
                'heading' => 'Help & Support',
                'body' => 'Support content will be available soon.',
                'section' => 'help',
            ]];
        } else {
            $blocks = $faqs->map(fn ($faq) => [
                'heading' => trim((string) $faq->question),
                'body' => trim((string) $faq->answer),
                'section' => 'faq',
            ])->values()->all();
        }

        return response()->json([
            'slug' => 'help',
            'title' => 'Help & Support',
            'updated_at' => optional($faqs->max('updated_at'))?->toIso8601String(),
            'blocks' => $blocks,
        ]);
    }
}
