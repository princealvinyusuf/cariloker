<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SleepWell\OnboardingScreen;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SleepWellOnboardingScreenController extends Controller
{
    public function index(): View
    {
        $screens = OnboardingScreen::query()->orderBy('sort_order')->paginate(20);

        return view('admin.sleepwell.onboarding.index', [
            'screens' => $screens,
        ]);
    }

    public function create(): View
    {
        return view('admin.sleepwell.onboarding.form', [
            'screen' => new OnboardingScreen(),
            'method' => 'POST',
            'action' => route('admin.sleepwell.onboarding.store'),
            'title' => 'Create Onboarding Screen',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $payload = $this->validatedPayload($request);
        $payload['options'] = $this->decodeOptions($payload['options_json'] ?? null);
        unset($payload['options_json']);

        OnboardingScreen::query()->create($payload);

        return redirect()
            ->route('admin.sleepwell.onboarding.index')
            ->with('status', 'Onboarding screen created.');
    }

    public function edit(OnboardingScreen $screen): View
    {
        return view('admin.sleepwell.onboarding.form', [
            'screen' => $screen,
            'method' => 'PUT',
            'action' => route('admin.sleepwell.onboarding.update', $screen),
            'title' => 'Edit Onboarding Screen',
        ]);
    }

    public function update(Request $request, OnboardingScreen $screen): RedirectResponse
    {
        $payload = $this->validatedPayload($request);
        $payload['options'] = $this->decodeOptions($payload['options_json'] ?? null);
        unset($payload['options_json']);

        $screen->update($payload);

        return redirect()
            ->route('admin.sleepwell.onboarding.index')
            ->with('status', 'Onboarding screen updated.');
    }

    public function destroy(OnboardingScreen $screen): RedirectResponse
    {
        $screen->delete();

        return redirect()
            ->route('admin.sleepwell.onboarding.index')
            ->with('status', 'Onboarding screen deleted.');
    }

    private function validatedPayload(Request $request): array
    {
        return $request->validate([
            'step_key' => ['required', 'string', 'max:80'],
            'screen_type' => ['required', 'in:welcome,single_choice,multi_choice,slider,info,email'],
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:500'],
            'image_url' => ['nullable', 'url', 'max:500'],
            'cta_label' => ['nullable', 'string', 'max:40'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:9999'],
            'skippable' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'options_json' => ['nullable', 'string'],
        ]);
    }

    private function decodeOptions(?string $optionsJson): array
    {
        if (!$optionsJson || trim($optionsJson) === '') {
            return [];
        }

        $decoded = json_decode($optionsJson, true);

        return is_array($decoded) ? $decoded : [];
    }
}
