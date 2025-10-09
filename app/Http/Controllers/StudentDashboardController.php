<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Session;
use App\Models\Review;
use App\Models\User;
use App\Models\Exercise;

class StudentDashboardController extends Controller
{
    // Static practice data for fallback/AI-based progression
    private $practiceData = [
        'easy' => [
            'word' => ['cat', 'dog', 'sun', 'book', 'car', 'tree', 'fish', 'ball', 'milk', 'shoe'],
            'sentence' => [
                'The cat sat on the mat.',
                'I like to read books.',
                'She has a red ball.',
                'It is a sunny day.',
                'The car is fast.',
                'I drink milk every day.',
                'The tree is tall.',
                'The fish swims fast.',
                'My shoe is new.',
                'He plays in the park.',
            ],
        ],
        'medium' => [
            'word' => ['pencil', 'mountain', 'window', 'bottle', 'flower', 'pocket', 'camera', 'jacket', 'pillow', 'rabbit'],
            'sentence' => [
                'He forgot his jacket at home.',
                'Can you open the window, please?',
                'The rabbit jumps quickly.',
                'She found a flower in the garden.',
                'Take a picture with your camera.',
                'I wrote my name with a pencil.',
                'The mountain is covered in snow.',
                'Put the book in your pocket.',
                'My pillow is soft and white.',
                'She drinks water from her bottle.',
            ],
        ],
        'hard' => [
            'word' => ['architecture', 'refrigerator', 'technology', 'university', 'complicated', 'pronunciation', 'chocolate', 'electricity', 'adventure', 'philosophy'],
            'sentence' => [
                'The architecture of this building is beautiful.',
                'She wants to study at the university.',
                'Chocolate melts in your mouth.',
                'Electricity powers our homes.',
                'Pronunciation is sometimes complicated.',
                'He is reading a book about philosophy.',
                'The adventure begins at dawn.',
                'Please put the milk in the refrigerator.',
                'New technology changes our lives.',
                'Learning English can be challenging.',
            ],
        ],
        'insane' => [
            'word' => [
                'otorhinolaryngologist', 'antidisestablishmentarianism', 'floccinaucinihilipilification',
                'pneumonoultramicroscopicsilicovolcanoconiosis', 'supercalifragilisticexpialidocious',
                'hippopotomonstrosesquipedaliophobia', 'sesquipedalian', 'circumlocution',
                'honorificabilitudinitatibus', 'incomprehensibilities'
            ],
            'sentence' => [
                'The otorhinolaryngologist performed a complex surgery.',
                'Antidisestablishmentarianism is hard to pronounce.',
                'She used floccinaucinihilipilification in her essay.',
                'Pneumonoultramicroscopicsilicovolcanoconiosis is a lung disease.',
                'He spelled supercalifragilisticexpialidocious correctly.',
                'Ironically, hippopotomonstrosesquipedaliophobia means fear of long words.',
                'The professor was fond of sesquipedalian speech.',
                'His answer was full of circumlocution.',
                'Honorificabilitudinitatibus appears in Shakespeare.',
                'Incomprehensibilities baffle many students.',
            ],
        ],
    ];

    public function index(Request $request)
    {
        $user = Auth::user();

        // ====== Top summary cards ======
        $totalSessions       = Session::where('user_id', $user->id)->count();
        $avgScore            = round(Review::where('student_id', $user->id)->avg('score'), 2);
        $exercisesCompleted  = Session::where('user_id', $user->id)->where('status', 'completed')->count();
        $exercisesToNextLevel= max(0, 10 - $exercisesCompleted);
        $streak              = $this->calculateStreak($user->id);
        $recentSessions      = Session::where('user_id', $user->id)->latest()->take(3)->get();
        $lastWeekSessions    = Session::where('user_id', $user->id)
                                    ->whereBetween('created_at', [now()->subWeek(), now()])
                                    ->count();
        $scoreImprovement    = 0;

        $learningPath = [
            'progress' => 40,
            'modules' => [
                ['name' => 'Vowel Sounds',        'level_class' => 'primary', 'level' => 'Beginner'],
                ['name' => 'Consonant Clusters',  'level_class' => 'warning', 'level' => 'Intermediate'],
                ['name' => 'Word Stress',         'level_class' => 'success', 'level' => 'Advanced'],
            ],
        ];

        $achievements = [
            ['icon' => 'fa-trophy', 'icon_color' => 'text-warning', 'text' => 'Completed your first session!'],
            ['icon' => 'fa-star',   'icon_color' => 'text-primary', 'text' => 'Scored above 90% in a review!'],
            ['icon' => 'fa-medal',  'icon_color' => 'text-success', 'text' => 'Achieved 5-day streak!'],
        ];

        // ====== Coach feedback (from human Review only) ======
        $reviewFeedbacks = Review::where('student_id', $user->id)
            ->whereNotNull('feedback')
            ->orderByDesc('created_at')
            ->take(20)
            ->get()
            ->map(function ($review) {
                $coach = User::find($review->coach_id);
                $color = 'primary';
                if ($coach && $coach->first_name === 'Kent') $color = 'success';
                if ($coach && $coach->first_name === 'Fred') $color = 'warning';

                return [
                    'color'   => $color,
                    'coach'   => $coach ? "Coach {$coach->first_name}" : 'Coach',
                    'message' => $review->feedback ?? $review->message ?? $review->category,
                    'date'    => $review->created_at ? $review->created_at->format('Y-m-d H:i') : '',
                ];
            });

        $coachFeedbacks = $reviewFeedbacks;

        // ====== Level/category selection ======
        $selectedLevel    = $request->query('level')    ?: session('selected_exercise_level');
        $selectedCategory = $request->query('category') ?: session('selected_exercise_category');

        if ($request->has(['level', 'category'])) {
            session([
                'selected_exercise_level'    => $selectedLevel,
                'selected_exercise_category' => $selectedCategory,
            ]);
        }

        // ====== Practice exercise selection ======
        $practiceExercise = null;

        if ($selectedLevel && $selectedCategory) {
            $dbExercise = Exercise::where('level', $selectedLevel)
                ->where('category', $selectedCategory)
                ->inRandomOrder()
                ->first();

            if ($dbExercise) {
                $practiceExercise = [
                    'id'    => $dbExercise->id,
                    'title' => ucfirst($selectedLevel) . ' ' . ucfirst($selectedCategory),
                    'word'  => $dbExercise->word
                        ?? $dbExercise->text
                        ?? $dbExercise->content
                        ?? $dbExercise->title
                        ?? '',
                ];
            } elseif (isset($this->practiceData[$selectedLevel][$selectedCategory])) {
                $practiceExercise = [
                    'id'    => 1,
                    'title' => ucfirst($selectedLevel) . ' ' . ucfirst($selectedCategory),
                    'word'  => $this->practiceData[$selectedLevel][$selectedCategory][0],
                ];
            }
        }

        if (!$practiceExercise) {
            $any = Exercise::inRandomOrder()->first();
            if ($any) {
                $practiceExercise = [
                    'id'    => $any->id,
                    'title' => 'Practice',
                    'word'  => $any->word
                        ?? $any->text
                        ?? $any->content
                        ?? $any->title
                        ?? '',
                ];
            } else {
                $practiceExercise = [
                    'id'    => 1,
                    'title' => 'Easy Word',
                    'word'  => $this->practiceData['easy']['word'][0] ?? 'cat',
                ];
            }
        }

        $practiceFeedback = null; // optional

        return view('student.dashboard', compact(
            'user',
            'totalSessions',
            'avgScore',
            'exercisesCompleted',
            'exercisesToNextLevel',
            'streak',
            'recentSessions',
            'lastWeekSessions',
            'scoreImprovement',
            'learningPath',
            'achievements',
            'coachFeedbacks',
            'practiceExercise',
            'practiceFeedback'
        ));
    }

    // For AJAX: returns JSON example from static data for progression
    public function getPracticeExample($level = 'easy', $category = 'word', $index = 0)
    {
        $data = $this->practiceData[$level][$category] ?? [];
        $item = $data[$index] ?? '';
        return response()->json([
            'example' => $item,
            'index'   => $index + 1,
            'total'   => count($data),
        ]);
    }

    // Helper for streak logic
    private function calculateStreak($userId)
    {
        $streak = 0;
        $date = now()->startOfDay();

        while (Session::where('user_id', $userId)->whereDate('created_at', $date)->exists()) {
            $streak++;
            $date->subDay();
        }
        return $streak;
    }

    // Handles POST from the select page to set session/query & redirect
    public function practiceSelect(Request $request)
    {
        $level = $request->input('level');
        $category = $request->input('category');

        session([
            'selected_exercise_level'    => $level,
            'selected_exercise_category' => $category,
        ]);

        return redirect()->route('student.dashboard', [
            'level'    => $level,
            'category' => $category,
        ]);
    }
}
