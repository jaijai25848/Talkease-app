<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AnalyticsController extends Controller
{
    public function index()
    {
        // --- Daily Active Users (sessions: user_id + last_activity[int]) ---
        $dailyActiveUsers = 0;
        if ($this->colExists('sessions','last_activity')) {
            $since = now()->subDay()->timestamp;
            if ($this->colExists('sessions','user_id')) {
                $dailyActiveUsers = DB::table('sessions')
                    ->where('last_activity','>=',$since)
                    ->whereNotNull('user_id')
                    ->distinct('user_id')->count('user_id');
            } else {
                $dailyActiveUsers = DB::table('sessions')
                    ->where('last_activity','>=',$since)
                    ->count();
            }
        }

        // --- Total sessions (if table exists) ---
        $totalSessions = $this->tableExists('sessions') ? DB::table('sessions')->count() : 0;

        // --- Avg session duration (only if you added a 'duration' column) ---
        $avgSessionDuration = 'n/a';
        if ($this->colExists('sessions','duration')) {
            $avg = (int) (DB::table('sessions')->avg('duration') ?? 0);
            $avgSessionDuration = $this->formatDuration($avg);
        }

        // --- Completion rate (only if you added a 'status' column) ---
        $completedSessions = $this->colExists('sessions','status')
            ? DB::table('sessions')->where('status','completed')->count()
            : 0;
        $completionRate = $totalSessions > 0 ? round(($completedSessions/$totalSessions)*100) : 0;

        // Backward-compat: some Blades used $avgProgress to mean completion rate
        $avgProgress = $completionRate;

        // --- AI accuracy (placeholder) ---
        $aiAccuracy = 94.2;

        // --- User satisfaction (reviews.rating if exists) ---
        $userSatisfaction = $this->colExists('reviews','rating')
            ? round((float) (DB::table('reviews')->avg('rating') ?? 0), 1)
            : 0.0;

        // --- Error rate (activity_logs.type if exists) ---
        $errorRate = 0.0;
        if ($this->tableExists('activity_logs') && $this->colExists('activity_logs','type')) {
            $totalErrors = DB::table('activity_logs')->where('type','error')->count();
            $den = max($totalSessions, 1);
            $errorRate = round(($totalErrors/$den)*100, 1);
        }

        return view('admin.partials.analytics', compact(
            'dailyActiveUsers',
            'avgSessionDuration',
            'completionRate',
            'avgProgress',        // for legacy Blade
            'aiAccuracy',
            'userSatisfaction',
            'errorRate',
            'totalSessions'       // Blade shows this too
        ));
    }

    private function tableExists(string $table): bool
    {
        return Schema::hasTable($table);
    }

    private function colExists(string $table, string $col): bool
    {
        return Schema::hasTable($table) && Schema::hasColumn($table, $col);
    }

    private function formatDuration(?int $seconds): string
    {
        if (!$seconds || $seconds < 1) return '0s';
        $m = intdiv($seconds, 60);
        $s = $seconds % 60;
        return sprintf('%dm %02ds', $m, $s);
    }
}
