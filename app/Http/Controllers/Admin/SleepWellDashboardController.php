<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SleepWell\Listener;
use App\Models\SleepWell\SleepSession;
use Illuminate\Contracts\View\View;

class SleepWellDashboardController extends Controller
{
    public function index(): View
    {
        $totalListeners = Listener::query()->count();
        $activeToday = SleepSession::query()
            ->whereDate('started_at', today())
            ->distinct('listener_id')
            ->count('listener_id');

        $completedSessions = SleepSession::query()
            ->where('status', 'completed')
            ->whereNotNull('ended_at')
            ->count();

        $avgDurationMinutes = (int) round(
            (SleepSession::query()->where('status', 'completed')->avg('duration_seconds') ?? 0) / 60
        );

        return view('admin.sleepwell.dashboard', [
            'totalListeners' => $totalListeners,
            'activeToday' => $activeToday,
            'completedSessions' => $completedSessions,
            'avgDurationMinutes' => $avgDurationMinutes,
        ]);
    }
}
