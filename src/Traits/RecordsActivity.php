<?php

namespace Moe\Settings\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

/**
 * Helper reusable untuk mencatat aktivitas ke tabel audit/activity log.
 *
 * Cara pakai di app:
 *   \Moe\Settings\Traits\RecordsActivity::record('update', 'setting', null, $old, $new);
 * atau dari dalam Model via import trait lalu:
 *   $this->recordActivity('login');
 *
 * Nama tabel diambil dari config('moe-settings.audit_log_table', 'audit_logs').
 * Jika tabel tidak ada, pencatatan di-skip graceful (tidak mematikan app).
 */
trait RecordsActivity
{
    /**
     * Catat aktivitas.
     *
     * @param string $action     contoh: 'update', 'login', 'create', 'delete'.
     * @param string $model       nama entity, contoh: 'setting', 'order', 'user'.
     * @param int|string|null $modelId
     * @param mixed $oldValues
     * @param mixed $newValues
     */
    public static function record(
        string $action,
        string $model = 'system',
        $modelId = null,
        $oldValues = null,
        $newValues = null
    ): void {
        $table = config('moe-settings.audit_log_table', 'audit_logs');

        if (! $table || ! \Illuminate\Support\Facades\Schema::hasTable($table)) {
            return;
        }

        $userId = Auth::id();
        $ip = Request::ip();
        $ua = Request::header('User-Agent');

        DB::table($table)->insert([
            'user_id'     => $userId,
            'action'      => $action,
            'model'       => $model,
            'model_id'    => $modelId,
            'old_values'  => $oldValues === null ? null : json_encode($oldValues),
            'new_values'  => $newValues === null ? null : json_encode($newValues),
            'ip_address'  => $ip,
            'user_agent'  => $ua,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    }

    /**
     * Convenience dari dalam model Eloquent.
     */
    public function recordActivity(string $action, $oldValues = null, $newValues = null): void
    {
        static::record($action, class_basename($this), $this->getKey(), $oldValues, $newValues);
    }
}
