<?php

namespace App\Support;

use App\Models\SleepWell\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class SleepWellAuditLogger
{
    public static function log(Request $request, string $action, Model $entity, array $changes = []): void
    {
        AuditLog::query()->create([
            'actor_user_id' => $request->user()?->id,
            'entity_type' => $entity::class,
            'entity_id' => $entity->getKey(),
            'action' => $action,
            'changes' => $changes,
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 300),
        ]);
    }
}
