use AppHttpControllersDatasetController;
<?php
use App\Http\Controllers\CoachDashboardController;
use App\Http\Controllers\AdminDashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// ===== Controllers =====
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SocialController;

use App\Http\Controllers\StudentDashboardController;

use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\SystemLogsController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SpeechController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PracticeFeedbackController;

// ✅ import for CSRF exemption
use App\Http\Middleware\VerifyCsrfToken;

// ================= PUBLIC =================
Route::get('/', fn () => view('welcome'))->name('welcome');

// ================= GUEST AUTH =================
Route::middleware('guest')->group(function () {
    // Register
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);

    // Login
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);

    // Password reset
    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// ================= EMAIL VERIFICATION =================
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
    ->middleware(['auth', 'signed'])
    ->name('verification.verify');

Route::post('/email/verification-notification', [VerificationController::class, 'resend'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

// ================= SOCIAL LOGIN =================
// Explicit Google
Route::get('/auth/google/redirect', [SocialController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [SocialController::class, 'callback'])->name('google.callback');

// Explicit Facebook
Route::get('/auth/facebook/redirect', [SocialController::class, 'facebookRedirect'])->name('facebook.redirect');
Route::get('/auth/facebook/callback', [SocialController::class, 'facebookCallback'])->name('facebook.callback');

// Optional generic provider routes (only allow google|facebook)
Route::get('auth/redirect/{provider}', [SocialController::class, 'providerRedirect'])
    ->whereIn('provider', ['google', 'facebook'])
    ->name('social.redirect');

Route::get('auth/callback/{provider}', [SocialController::class, 'providerCallback'])
    ->whereIn('provider', ['google', 'facebook'])
    ->name('social.callback');

// ================= PROFILE & LOGOUT =================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Logout must be POST for CSRF protection
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});

// ================= APP (AUTH + VERIFIED) =================
Route::middleware(['auth'])->group(function () {
    // Speech APIs
    Route::post('/speech/recognize', [SpeechController::class, 'recognize'])->name('speech.recognize');

    // ✅ CSRF-exempt Whisper endpoint (only this route)
    Route::post('/stt/whisper', [SpeechController::class, 'whisper'])
        ->name('stt.whisper')
        ->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

    // ✅ New Fast Whisper local server route
    Route::post('/stt/whisper-fast', [SpeechController::class, 'whisperFast'])->name('stt.whisper.fast');

    // Exercises
    Route::get('/exercises', [ExerciseController::class, 'index'])->name('exercises.index');
    Route::get('/exercises/select', [ExerciseController::class, 'select'])->name('exercises.select');
    Route::get('/exercises/level/{level}', [ExerciseController::class, 'byLevel'])->name('exercises.byLevel');
    Route::get('/exercises/{exercise}', [ExerciseController::class, 'show'])->name('exercises.show');
    Route::get('/exercises/practice/{level}/{category}', [ExerciseController::class, 'practice'])->name('exercises.practice');

    // Practice examples for AI progression
    Route::get('/practice/example/{level}/{category}/{index}', [StudentDashboardController::class, 'getPracticeExample'])
        ->name('practice.example');

    // Pronunciation Feedback demo page
    Route::get('/practice', fn () => view('practice'))->name('practice.view');
    Route::post('/practice/score', [PracticeFeedbackController::class, 'score'])->name('practice.score');

    // Student dashboard (default after login)
    Route::get('/student/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');
    Route::post('/student/practice-select', [StudentDashboardController::class, 'practiceSelect'])->name('student.practice.select');

    // Coach area
    Route::middleware('role:coach')->group(function () {
    });

    // Admin area
    Route::middleware('role:admin')->prefix('admin')->as('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/users', [UserManagementController::class, 'index'])->name('users');
        Route::get('/logs', [SystemLogsController::class, 'index'])->name('system-logs');
        Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings');

        // User CRUD
        Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');

        // AJAX dashboard stats
        Route::get('/dashboard/stats', [AdminDashboardController::class, 'getStats'])->name('dashboard.stats');
    });
});

// ================= DASHBOARD REDIRECTOR =================
Route::get('/dashboard', function () {
    $user = Auth::user();
    if (!$user) return redirect()->route('login');
    if ($user->role === 'coach') return redirect()->route('coach.dashboard');
    if ($user->role === 'admin') return redirect()->route('admin.dashboard');
    return redirect()->route('student.dashboard');
})->middleware('auth')->name('dashboard');

// ================= DIAGNOSTICS (DEV ONLY) =================
Route::get('/_diag/google', function () {
    $url = \Laravel\Socialite\Facades\Socialite::driver('google')->redirect()->getTargetUrl();
    return response()->json(['redirect_url' => $url]);
})->middleware('auth');

Route::get('/_diag/whisper', function () {
    $ping = \Illuminate\Support\Facades\Http::withToken(env('OPENAI_API_KEY'))
        ->timeout(10)->get('https://api.openai.com/v1/models');

    return response()->json([
        'key_present'         => !empty(env('OPENAI_API_KEY')),
        'models_status'       => $ping->status(),
        'models_error'        => $ping->ok() ? null : $ping->body(),
        'upload_max_filesize' => ini_get('upload_max_filesize'),
        'post_max_size'       => ini_get('post_max_size'),
    ]);
})->middleware('auth');

// ================= LEGACY / FALLBACK =================
Route::redirect('/home', '/student/dashboard', 301);

Route::middleware(['auth','role:admin'])->group(function () {
});

Route::middleware(['auth','role:admin'])->group(function () {
});


Route::middleware(['auth','role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/dashboard/stats', [AdminDashboardController::class, 'stats'])->name('admin.dashboard.stats');
});


Route::middleware(['auth','role:coach'])->group(function () {
    Route::get('/coach/dashboard', [CoachDashboardController::class, 'index'])->name('coach.dashboard');
});

// --- Admin: User management ---
Route::middleware(['auth','role:admin'])->group(function () {
    Route::get('/admin/users', [UserManagementController::class, 'index'])->name('admin.users');
    Route::get('/admin/users/{id}/edit', [UserManagementController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{id}', [UserManagementController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{id}', [UserManagementController::class, 'destroy'])->name('admin.users.destroy');
});

// --- Admin: System Logs ---
Route::middleware(['auth','role:admin'])->group(function () {
    Route::get('/admin/system-logs', [SystemLogsController::class, 'index'])->name('admin.system.logs');
});

/* ---------- Exercises / Practice ---------- */
Route::get('/exercises/practice/{level}/{category}', [ExerciseController::class, 'practice'])
    ->whereIn('level', ['easy','medium','hard'])
    ->whereIn('category', ['word','sentence'])
    ->name('exercises.practice');

Route::get('/exercises/example/{level}/{category}/{index?}', [ExerciseController::class, 'example'])
    ->name('exercises.example');

Route::post('/exercises/submit', [ExerciseController::class, 'submit'])
    ->name('exercises.submit');

Route::fallback(fn () => redirect()->route('welcome'));



Route::prefix('exercises')->name('exercises.')->group(function () {
    Route::get('/', [\App\Http\Controllers\ExerciseController::class, 'index'])->name('index');
    Route::get('/select', [\App\Http\Controllers\ExerciseController::class, 'select'])->name('select');
    Route::get('/show/{id}', [\App\Http\Controllers\ExerciseController::class, 'show'])->name('show');
    Route::get('/practice/{level}/{category}', [\App\Http\Controllers\ExerciseController::class, 'practice'])->name('practice');
});

Route::middleware(['auth','role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Controller returns the PARTIAL. If you prefer full page, change view in controller to 'admin.analytics'
    Route::get('/analytics', [\App\Http\Controllers\AnalyticsController::class, 'index'])->name('analytics');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/datasets', [DatasetController::class, 'index'])->name('datasets.index');
    Route::get('/datasets/{dataset:slug}/random', [DatasetController::class, 'random'])->name('datasets.random');
});
