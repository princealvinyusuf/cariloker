<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SleepWell\HomeSection;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SleepWellHomeSectionController extends Controller
{
    public function index(): View
    {
        $sections = HomeSection::query()->orderBy('sort_order')->paginate(20);

        return view('admin.sleepwell.home-sections.index', [
            'sections' => $sections,
        ]);
    }

    public function create(): View
    {
        return view('admin.sleepwell.home-sections.form', [
            'section' => new HomeSection(),
            'method' => 'POST',
            'action' => route('admin.sleepwell.home-sections.store'),
            'title' => 'Create Home Section',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $payload = $this->validatedPayload($request);
        HomeSection::query()->create($payload);

        return redirect()->route('admin.sleepwell.home-sections.index')
            ->with('status', 'Home section created.');
    }

    public function edit(HomeSection $section): View
    {
        return view('admin.sleepwell.home-sections.form', [
            'section' => $section,
            'method' => 'PUT',
            'action' => route('admin.sleepwell.home-sections.update', $section),
            'title' => 'Edit Home Section',
        ]);
    }

    public function update(Request $request, HomeSection $section): RedirectResponse
    {
        $payload = $this->validatedPayload($request);
        $section->update($payload);

        return redirect()->route('admin.sleepwell.home-sections.index')
            ->with('status', 'Home section updated.');
    }

    public function destroy(HomeSection $section): RedirectResponse
    {
        $section->delete();

        return redirect()->route('admin.sleepwell.home-sections.index')
            ->with('status', 'Home section deleted.');
    }

    private function validatedPayload(Request $request): array
    {
        return $request->validate([
            'section_key' => ['required', 'string', 'max:80'],
            'title' => ['nullable', 'string', 'max:160'],
            'subtitle' => ['nullable', 'string', 'max:300'],
            'section_type' => ['required', 'in:hero_carousel,grid,horizontal,top_ranked,chips,promo'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }
}
