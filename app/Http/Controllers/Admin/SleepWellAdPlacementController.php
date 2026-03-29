<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SleepWell\AdPlacement;
use App\Support\SleepWellAuditLogger;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SleepWellAdPlacementController extends Controller
{
    public function index(): View
    {
        return view('admin.sleepwell.ad-placements.index', [
            'placements' => AdPlacement::query()->orderByDesc('priority')->paginate(30),
        ]);
    }

    public function create(): View
    {
        return view('admin.sleepwell.ad-placements.form', [
            'placement' => new AdPlacement(),
            'action' => route('admin.sleepwell.ad-placements.store'),
            'method' => 'POST',
            'title' => 'Create Ad Placement',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $payload = $this->validatedPayload($request);
        $placement = AdPlacement::query()->create($payload);
        SleepWellAuditLogger::log($request, 'create', $placement, $payload);

        return redirect()->route('admin.sleepwell.ad-placements.index')->with('status', 'Ad placement created.');
    }

    public function edit(AdPlacement $placement): View
    {
        return view('admin.sleepwell.ad-placements.form', [
            'placement' => $placement,
            'action' => route('admin.sleepwell.ad-placements.update', $placement),
            'method' => 'PUT',
            'title' => 'Edit Ad Placement',
        ]);
    }

    public function update(Request $request, AdPlacement $placement): RedirectResponse
    {
        $payload = $this->validatedPayload($request);
        $before = $placement->toArray();
        $placement->update($payload);
        SleepWellAuditLogger::log($request, 'update', $placement, [
            'before' => $before,
            'after' => $placement->fresh()?->toArray(),
        ]);

        return redirect()->route('admin.sleepwell.ad-placements.index')->with('status', 'Ad placement updated.');
    }

    public function destroy(AdPlacement $placement): RedirectResponse
    {
        $snapshot = $placement->toArray();
        $placement->delete();
        SleepWellAuditLogger::log(request(), 'delete', $placement, ['before' => $snapshot]);

        return redirect()->route('admin.sleepwell.ad-placements.index')->with('status', 'Ad placement deleted.');
    }

    private function validatedPayload(Request $request): array
    {
        return $request->validate([
            'screen' => ['required', 'string', 'max:40'],
            'slot_key' => ['required', 'string', 'max:60'],
            'format' => ['required', 'string', 'max:30'],
            'enabled' => ['nullable', 'boolean'],
            'frequency_cap' => ['required', 'integer', 'min:0', 'max:999'],
            'countries' => ['nullable', 'string', 'max:200'],
            'priority' => ['required', 'integer', 'min:0', 'max:9999'],
            'ad_unit_id_android' => ['nullable', 'string', 'max:255'],
            'ad_unit_id_ios' => ['nullable', 'string', 'max:255'],
        ]);
    }
}
