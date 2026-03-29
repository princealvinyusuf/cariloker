<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SleepWell\AuditLog;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class SleepWellAuditLogController extends Controller
{
    public function index(Request $request): View
    {
        $entityType = trim((string) $request->query('entity_type', ''));
        $action = trim((string) $request->query('action', ''));

        $logs = AuditLog::query()
            ->with('actor:id,name,email')
            ->when($entityType !== '', fn ($q) => $q->where('entity_type', $entityType))
            ->when($action !== '', fn ($q) => $q->where('action', $action))
            ->latest()
            ->paginate(50)
            ->withQueryString();

        return view('admin.sleepwell.audit-logs.index', [
            'logs' => $logs,
            'entityType' => $entityType,
            'action' => $action,
        ]);
    }
}
