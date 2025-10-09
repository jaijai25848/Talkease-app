<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

class SystemLogsController extends Controller
{
    public function index(Request $request)
    {
        // ---------- Users table for "User Management" tab ----------
        $q      = trim((string) $request->query('q', ''));
        $role   = trim((string) $request->query('role', ''));   // optional filter
        $status = trim((string) $request->query('status', '')); // optional filter: verified/unverified

        $usersQuery = User::query();

        if ($q !== '') {
            $usersQuery->where(function ($w) use ($q) {
                $w->where('first_name', 'like', "%$q%")
                  ->orWhere('last_name',  'like', "%$q%")
                  ->orWhere('email',      'like', "%$q%");
            });
        }
        if ($role !== '') {
            $usersQuery->where('role', $role);
        }
        if ($status === 'verified') {
            $usersQuery->whereNotNull('email_verified_at');
        } elseif ($status === 'unverified') {
            $usersQuery->whereNull('email_verified_at');
        }

        $users = $usersQuery->orderByDesc('created_at')->paginate(10)->withQueryString();

        // ---------- Dashboard/analytics (guard tables/columns) ----------
        $totalUsers     = User::count();
        $activeStudents = User::where('role','student')->whereNotNull('email_verified_at')->count();
        $coaches        = User::where('role','coach')->count();
        $recentUsers    = User::orderByDesc('created_at')->take(3)->get();

        $totalSessions = Schema::hasTable('sessions') ? DB::table('sessions')->count() : 0;
        $reviewsCount  = Schema::hasTable('reviews')  ? DB::table('reviews')->count()  : 0;

        $dailyActiveUsers = 0;
        if (Schema::hasTable('sessions')) {
            $dailyActiveUsers = DB::table('sessions')
                ->where('created_at', '>=', now()->subDay())
                ->distinct('user_id')
                ->count('user_id');
        }

        $avgProgress = 0.0;
        if (Schema::hasTable('sessions')) {
            if (Schema::hasColumn('sessions', 'progress')) {
                $avgProgress = (float) DB::table('sessions')->avg('progress');
            } elseif (Schema::hasColumn('sessions', 'score')) {
                $avgProgress = (float) DB::table('sessions')->avg('score');
            }
        }
        $avgProgress = round($avgProgress, 2);

        $aiAccuracy       = 97;
        $userSatisfaction = 89;
        $avgScore         = null;
        $uptime           = '99.8%';

        // Logs (optional)
        if (Schema::hasTable('system_logs')) {
            $logs = DB::table('system_logs as l')
                ->leftJoin('users as u', 'u.id', '=', 'l.user_id')
                ->select(
                    'l.*',
                    DB::raw('u.first_name as `user.first_name`'),
                    DB::raw('u.last_name  as `user.last_name`'),
                    DB::raw('u.email      as `user.email`')
                )
                ->orderByDesc('l.created_at')
                ->limit(50)
                ->get();
        } else {
            $logs = collect();
        }

        $systemHealth = [
            'server'   => 'Online',
            'database' => $this->checkDatabaseHealth(),
            'ai'       => 'Operational',
            'storage'  => $this->getDiskUsage(),
            'response' => rand(120, 180) . 'ms',
        ];

        return view('admin.dashboard', [
            'totalUsers'        => $totalUsers,
            'activeStudents'    => $activeStudents,
            'coaches'           => $coaches,
            'recentUsers'       => $recentUsers,
            'totalSessions'     => $totalSessions,
            'reviewsCount'      => $reviewsCount,
            'avgScore'          => $avgScore,
            'uptime'            => $uptime,
            'systemHealth'      => $systemHealth,
            'logs'              => $logs,
            'users'             => $users, // <— now populated
            'activeTab'         => $request->query('tab', 'user-management'),
            'dailyActiveUsers'  => $dailyActiveUsers,
            'avgProgress'       => $avgProgress,
            'aiAccuracy'        => $aiAccuracy,
            'userSatisfaction'  => $userSatisfaction,
        ]);
    }

    private function checkDatabaseHealth()
    {
        try { DB::connection()->getPdo(); return 'Healthy'; }
        catch (\Throwable $e) { return 'Unhealthy'; }
    }

    private function getDiskUsage()
    {
        $total = @disk_total_space("/");
        $free  = @disk_free_space("/");
        if ($total === false || $free === false || $total == 0) return 'N/A';
        $used = $total - $free;
        $percentUsed = round($used / $total * 100, 1);
        return "{$percentUsed}% Used";
    }
}
