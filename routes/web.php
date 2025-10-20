<?php
use Illuminate\Support\Facades\Route;

/*
 | This block disables ALL Practice pages by returning 404 for:
 | /exercises/practice/*
 | Works regardless of existing routes defined later.
*/
Route::prefix('exercises/practice')->group(function () {
    Route::any('/{any?}', function () {
        abort(404);
    })->where('any', '.*');
});
<?php

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
use App\Http\Controllers\CoachDashboardController;
use App\Http\Controllers\AdminDashboardController;

use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\SystemLogsController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\SettingsController;

use App\Http\Controllers\SpeechController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\PracticeFeedbackController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DatasetController;

// ================= PUBLIC =================
Route::get('/', fn () => view('welcome'))->name('welcome');

// ================= GUEST AUTH =================
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);

    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);

    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// ================= EMAIL VERIFICATION =================
Route::get('/email/verify', fn () => view('auth.verify-email'))
    ->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
    ->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', [VerificationController::class, 'resend'])
    ->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// ================= SOCIAL LOGIN =================
Route::get('/auth/google/redirect',  [SocialController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback',  [SocialController::class, 'callback'])->name('google.callback');

Route::get('/auth/facebook/redirect', [SocialController::class, 'facebookRedirect'])->name('facebook.redirect');
Route::get('/auth/facebook/callback', [SocialController::class, 'facebookCallback'])->name('facebook.callback');

Route::get('auth/redirect/{provider}', [SocialController::class, 'providerRedirect'])
    ->whereIn('provider', ['google', 'facebook'])->name('social.redirect');
Route::get('auth/callback/{provider}', [SocialController::class, 'providerCallback'])
    ->whereIn('provider', ['google', 'facebook'])->name('social.callback');

// ================= PROFILE & LOGOUT =================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('logout', [LoginController::class, 'logout'])->name('logout'); // POST for CSRF
});

// ================= APP (AUTH) =================
Route::middleware(['auth'])->group(function () {

    // -------- Speech / STT --------
    Route::post('/speech/recognize', [SpeechController::class, 'recognize'])->name('speech.recognize');
    Route::post('/stt/whisper', [SpeechController::class, 'whisper'])
        ->name('stt.whisper')
        ->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
    Route::post('/stt/whisper-fast', [SpeechController::class, 'whisperFast'])->name('stt.whisper.fast');

    // -------- Exercises flow --------
    Route::prefix('exercises')->name('exercises.')->group(function () {
        Route::get('/',            [ExerciseController::class, 'index'])->name('index');
        Route::get('/select',      [ExerciseController::class, 'select'])->name('select');

        // target for the Select page (GET) — validates & redirects to practice
        Route::get('/start',       [ExerciseController::class, 'start'])->name('start');

        Route::get('/practice/{level}/{category}', [ExerciseController::class, 'practice'])
            ->whereIn('level', ['easy','medium','hard','insane'])
            ->whereIn('category', ['word','sentence','phrase'])
            ->name('practice');

        Route::get('/show/{id}',   [ExerciseController::class, 'show'])->name('show');

        Route::get('/example/{level}/{category}/{index?}', [ExerciseController::class, 'example'])
            ->whereIn('level', ['easy','medium','hard','insane'])
            ->whereIn('category', ['word','sentence','phrase'])
            ->name('example');

        Route::post('/submit',     [ExerciseController::class, 'submit'])->name('submit');
    });

    // Dashboard helper (optional)
    Route::get('/practice/example/{level}/{category}/{index}', [StudentDashboardController::class, 'getPracticeExample'])
        ->whereIn('level', ['easy','medium','hard','insane'])
        ->whereIn('category', ['word','sentence','phrase'])
        ->name('practice.example');

    // Demo page + scoring
    Route::get('/practice', fn () => view('practice'))->name('practice.view');
    Route::post('/practice/score', [PracticeFeedbackController::class, 'score'])->name('practice.score');

    // Dashboards
    Route::get('/student/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');
    Route::post('/student/practice-select', [StudentDashboardController::class, 'practiceSelect'])->name('student.practice.select');

    // Admin
    Route::middleware('role:admin')->prefix('admin')->as('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/stats', [AdminDashboardController::class, 'stats'])->name('dashboard.stats');
        Route::get('/users', [UserManagementController::class, 'index'])->name('users');
        Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
        Route::get('/system-logs', [SystemLogsController::class, 'index'])->name('system.logs');
        Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    });

    // Coach
    Route::middleware('role:coach')->group(function () {
        Route::get('/coach/dashboard', [CoachDashboardController::class, 'index'])->name('coach.dashboard');
    });

    // Datasets
    Route::get('/datasets', [DatasetController::class, 'index'])->name('datasets.index');
    Route::get('/datasets/{dataset:slug}/random', [DatasetController::class, 'random'])->name('datasets.random');
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
Route::fallback(fn () => redirect()->route('welcome'));
