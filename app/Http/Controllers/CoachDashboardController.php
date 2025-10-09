<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

class CoachDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ===== Students base list =====
        $studentsQ = User::query()
            ->when(Schema::hasColumn('users', 'role'), fn ($q) => $q->where('role', 'student'));

        $students   = $studentsQ->latest()->limit(12)->get();
        $studentIds = $students->pluck('id');

        // ===== Main stats =====
        $totalStudents       = $studentsQ->count();
        $newStudentsThisWeek = $studentsQ->where('created_at', '>=', now()->startOfWeek())->count();

        // ===== Average progress across all students =====
        $avgProgress  = 0.0;
        $sessionsBase = null;

        if (Schema::hasTable('sessions')) {
            $sessionsQ = DB::table('sessions');

            if (Schema::hasColumn('sessions', 'coach_id')) {
                $sessionsQ->where('coach_id', $user->id);
            }
            if (Schema::hasColumn('sessions', 'student_id')) {
                $sessionsQ->whereIn('student_id', $studentIds);
            } elseif (Schema::hasColumn('sessions', 'user_id')) {
                $sessionsQ->whereIn('user_id', $studentIds);
            }

            $sessionsBase = clone $sessionsQ;

            if (Schema::hasColumn('sessions', 'progress')) {
                $avgProgress = (float) (clone $sessionsQ)->avg('progress');
            } elseif (Schema::hasColumn('sessions', 'score')) {
                $avgProgress = (float) (clone $sessionsQ)->avg('score');
            }
            $avgProgress = round($avgProgress, 1);
        }

        // ===== Sessions summary =====
        $sessionsCount     = 0;
        $completedSessions = 0;

        if ($sessionsBase) {
            $sessionsCount = (clone $sessionsBase)->count();

            if (Schema::hasColumn('sessions', 'status')) {
                $completedSessions = (clone $sessionsBase)->where('status', 'completed')->count();
            }
        }

        $completionRate = $sessionsCount > 0 ? round(($completedSessions / $sessionsCount) * 100) : 0;

        // ===== Recent activities =====
        $recentActivities = [];
        if ($sessionsBase) {
            $recent = (clone $sessionsBase)->orderByDesc('created_at')->limit(5)->get();

            $recentActivities = $recent->map(fn ($s) => [
                'student'      => $s->student_name ?? $s->student_id ?? $s->user_id ?? 'Student',
                'last_session' => $s->created_at ? date('Y-m-d H:i', strtotime($s->created_at)) : '',
                'progress'     => $s->progress ?? $s->score ?? null,
            ])->all();
        }

        // ===== Reviews (counts + lists; defensive) =====
        $pendingReviews      = 0;
        $urgentReviews       = 0;
        $pendingReviewsList  = [];
        $urgentReviewsList   = [];

        if (Schema::hasTable('reviews')) {
            $reviewsBase = DB::table('reviews');

            if (Schema::hasColumn('reviews', 'coach_id')) {
                $reviewsBase->where('coach_id', $user->id);
            }
            if (Schema::hasColumn('reviews', 'student_id')) {
                $reviewsBase->whereIn('student_id', $studentIds);
            } elseif (Schema::hasColumn('reviews', 'user_id')) {
                $reviewsBase->whereIn('user_id', $studentIds);
            }

            // Pending
            if (Schema::hasColumn('reviews', 'status')) {
                $pendingReviews = (clone $reviewsBase)->where('status', 'pending')->count();

                $pendingQ = (clone $reviewsBase)->where('status', 'pending')
                    ->orderByDesc('created_at')->limit(5);

                $pendingReviewsList = $pendingQ->get()->map(fn ($r) => [
                    'name'      => $r->student_name ?? $r->student_id ?? $r->user_id ?? 'Student',
                    'category'  => $r->category ?? 'General',
                    'score'     => $r->score ?? null,
                    'duration'  => $r->duration ?? null,
                    'submitted' => $r->created_at ? date('Y-m-d H:i', strtotime($r->created_at)) : '',
                ])->all();
            }

            // Urgent (prefer explicit 'urgent'; fallback to low score)
            if (Schema::hasColumn('reviews', 'urgent')) {
                $urgentReviews = (clone $reviewsBase)->where('urgent', 1)->count();
                $urgentListQ   = (clone $reviewsBase)->where('urgent', 1);
            } elseif (Schema::hasColumn('reviews', 'score')) {
                $urgentReviews = (clone $reviewsBase)->where('score', '<', 70)->count();
                $urgentListQ   = (clone $reviewsBase)->where('score', '<', 70);
            } else {
                $urgentListQ   = (clone $reviewsBase);
            }

            $urgentReviewsList = $urgentListQ->orderByDesc('created_at')->limit(5)->get()
                ->map(fn ($r) => [
                    'name'      => $r->student_name ?? $r->student_id ?? $r->user_id ?? 'Student',
                    'category'  => $r->category ?? 'General',
                    'score'     => $r->score ?? null,
                    'duration'  => $r->duration ?? null,
                    'submitted' => $r->created_at ? date('Y-m-d H:i', strtotime($r->created_at)) : '',
                ])->all();
        }

        // ===== Analytics for "Performance Trends" cards =====
        // Avg improvement = avg progress (last 30d) - avg progress (prev 30d)
        $avgImprovement = 0.0;
        if ($sessionsBase && Schema::hasColumn('sessions', 'created_at')) {
            $now    = now();
            $start1 = $now->copy()->subDays(30);
            $start2 = $now->copy()->subDays(60);

            $last30 = (clone $sessionsBase)->whereBetween('created_at', [$start1, $now]);
            $prev30 = (clone $sessionsBase)->whereBetween('created_at', [$start2, $start1]);

            $m1 = 0.0;
            $m2 = 0.0;

            if (Schema::hasColumn('sessions', 'progress')) {
                $m1 = (float) $last30->avg('progress');
                $m2 = (float) $prev30->avg('progress');
            } elseif (Schema::hasColumn('sessions', 'score')) {
                $m1 = (float) $last30->avg('score');
                $m2 = (float) $prev30->avg('score');
            }

            $avgImprovement = round($m1 - $m2, 1);
        }

        // Student satisfaction = avg review score (0..100) if present
        $studentSatisfaction = 0;
        if (Schema::hasTable('reviews') && Schema::hasColumn('reviews', 'score')) {
            $reviewsQ = DB::table('reviews');
            if (Schema::hasColumn('reviews', 'coach_id')) {
                $reviewsQ->where('coach_id', $user->id);
            }
            if (Schema::hasColumn('reviews', 'student_id')) {
                $reviewsQ->whereIn('student_id', $studentIds);
            } elseif (Schema::hasColumn('reviews', 'user_id')) {
                $reviewsQ->whereIn('user_id', $studentIds);
            }
            $studentSatisfaction = (int) round((float) $reviewsQ->avg('score'));
        }

        // ===== "Common Challenges" list for the Analytics tab =====
        // Derive from reviews.category + low scores; otherwise provide a small static set.
        $challenges = [];
        if (Schema::hasTable('reviews') && Schema::hasColumn('reviews', 'category')) {
            $catQuery = DB::table('reviews');

            if (Schema::hasColumn('reviews', 'coach_id')) {
                $catQuery->where('coach_id', $user->id);
            }
            if (Schema::hasColumn('reviews', 'student_id')) {
                $catQuery->whereIn('student_id', $studentIds);
            } elseif (Schema::hasColumn('reviews', 'user_id')) {
                $catQuery->whereIn('user_id', $studentIds);
            }

            $cats = $catQuery
                ->select('category',
                    DB::raw('COUNT(*) as total'),
                    // treat <70 as a "challenge" if score exists; otherwise mark none
                    Schema::hasColumn('reviews', 'score')
                        ? DB::raw('SUM(CASE WHEN score < 70 THEN 1 ELSE 0 END) as low_count')
                        : DB::raw('0 as low_count')
                )
                ->groupBy('category')
                ->orderByDesc('total')
                ->limit(6)
                ->get();

            foreach ($cats as $row) {
                $ratio = $row->total > 0 ? ($row->low_count / $row->total) : 0;
                $severity = $ratio >= 0.5 ? 'High' : ($ratio >= 0.2 ? 'Medium' : 'Low');

                $challenges[] = [
                    'name'  => $row->category ?? 'General',
                    'level' => $severity,
                ];
            }
        }

        if (empty($challenges)) {
            // Static fallback so the Blade never breaks
            $challenges = [
                ['name' => 'Vowel Sounds',       'level' => 'Medium'],
                ['name' => 'Consonant Clusters', 'level' => 'High'],
                ['name' => 'Word Stress',        'level' => 'Low'],
            ];
        }

        // ===== Students tab data (includes email) =====
        $sessionsBaseLocal = $sessionsBase;

        $studentsForView = $students->map(function ($u) use ($sessionsBaseLocal) {
            $name = trim(($u->first_name ?? '') . ' ' . ($u->last_name ?? ''));
            if ($name === '') {
                $name = $u->name ?? $u->email ?? 'Student';
            }
            $level = $u->level ?? 'Beginner';
            $email = $u->email ?? '';

            $progress = 0.0;
            $last     = '';

            if ($sessionsBaseLocal) {
                $byStudent = (clone $sessionsBaseLocal);

                if (Schema::hasColumn('sessions', 'student_id')) {
                    $byStudent->where('student_id', $u->id);
                } elseif (Schema::hasColumn('sessions', 'user_id')) {
                    $byStudent->where('user_id', $u->id);
                }

                if (Schema::hasColumn('sessions', 'progress')) {
                    $progress = round((float) $byStudent->avg('progress'), 1);
                } elseif (Schema::hasColumn('sessions', 'score')) {
                    $progress = round((float) $byStudent->avg('score'), 1);
                }

                $lastAt = (clone $byStudent)->max('created_at');
                $last   = $lastAt ? date('Y-m-d H:i', strtotime($lastAt)) : '';
            }

            return [
                'name'         => $name,
                'email'        => $email,
                'level'        => $level,
                'progress'     => $progress ?: 0,
                'last_session' => $last,
            ];
        })->all();

        // Simple goals
        $weeklyGoal     = 5;
        $hoursThisWeek  = $sessionsCount; // treat 1 session = 1 hour
        $hoursRemaining = max(0, $weeklyGoal - (int) $hoursThisWeek);

        return view('coach.dashboard', [
            'user'                 => $user,
            'totalStudents'        => $totalStudents,
            'newStudentsThisWeek'  => $newStudentsThisWeek,
            'avgProgress'          => $avgProgress,
            'completionRate'       => $completionRate,
            'hoursThisWeek'        => $hoursThisWeek,
            'hoursRemaining'       => $hoursRemaining,
            'recentActivities'     => $recentActivities,
            'pendingReviews'       => $pendingReviews,
            'pendingReviewsList'   => $pendingReviewsList,
            'urgentReviews'        => $urgentReviews,
            'urgentReviewsList'    => $urgentReviewsList,
            // Analytics tab extras so Blade never errors
            'avgImprovement'       => $avgImprovement,
            'studentSatisfaction'  => $studentSatisfaction,
            'challenges'           => $challenges,
            // Students tab
            'students'             => $studentsForView,
        ]);
    }
}
