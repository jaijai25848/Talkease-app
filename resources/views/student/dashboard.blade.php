@extends('layouts.student')

@section('content')
<div class="row">
    <div class="col-12 mb-3">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
            <div>
                <h4 class="mb-0">
                    Welcome back, 
                    {{ trim((auth()->user()->first_name ?? '') . ' ' . (auth()->user()->last_name ?? '')) ?: (auth()->user()->email ?? 'Student') }}
                </h4>
                <div class="text-muted small">Continue your pronunciation journey</div>
            </div>
        </div>
        <!-- Tabs -->
        <ul class="nav nav-tabs mb-3" id="dashboardTabs" role="tablist">
            <li class="nav-item"><a class="nav-link active" id="overview-tab" data-bs-toggle="tab" href="#overview" role="tab">Overview</a></li>
            <li class="nav-item"><a class="nav-link" id="practice-tab" data-bs-toggle="tab" href="#practice" role="tab">Practice</a></li>
            <li class="nav-item"><a class="nav-link" id="progress-tab" data-bs-toggle="tab" href="#progress" role="tab">Progress</a></li>
            <li class="nav-item"><a class="nav-link" id="feedback-tab" data-bs-toggle="tab" href="#feedback" role="tab">Feedback</a></li>
        </ul>
        <div class="tab-content" id="dashboardTabsContent">

            <!-- Overview Tab -->
            <div class="tab-pane fade show active" id="overview" role="tabpanel">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="card text-center shadow-sm">
                            <div class="card-body">
                                <div class="fs-4 fw-bold">{{ $totalSessions ?? 0 }}</div>
                                <div class="small text-muted">Total Sessions</div>
                                <div class="text-success small">+{{ $lastWeekSessions ?? 0 }} from last week</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center shadow-sm">
                            <div class="card-body">
                                <div class="fs-4 fw-bold">{{ $avgScore ?? 0 }}%</div>
                                <div class="small text-muted">Average Score</div>
                                <div class="text-success small">+{{ $scoreImprovement ?? 0 }}% improvement</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center shadow-sm">
                            <div class="card-body">
                                <div class="fs-4 fw-bold">{{ $exercisesCompleted ?? 0 }}</div>
                                <div class="small text-muted">Exercises Completed</div>
                                <div class="text-success small">{{ $exercisesToNextLevel ?? 0 }} more to unlock next level</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center shadow-sm">
                            <div class="card-body">
                                <div class="fs-4 fw-bold">{{ $streak ?? 0 }} days</div>
                                <div class="small text-muted">Streak</div>
                                <div class="text-primary small">Keep it up!</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Learning Path -->
                <div class="card mt-4 shadow-sm">
                    <div class="card-body">
                        <div class="fw-bold mb-2">Current Learning Path</div>
                        <div class="mb-3 small text-muted">Your personalized pronunciation improvement journey</div>
                        <div class="progress mb-3" style="height: 8px;">
                            <div class="progress-bar" style="width:{{ $learningPath['progress'] ?? 0 }}%;"></div>
                        </div>
                        <div class="row g-2 align-items-center">
                            @if(!empty($learningPath['modules']))
                                @foreach($learningPath['modules'] as $module)
                                    <div class="col-md-3">
                                        <div class="p-2 rounded bg-light border d-flex justify-content-between align-items-center">
                                            <span>{{ $module['name'] ?? 'Module' }}</span>
                                            <span class="badge bg-{{ $module['level_class'] ?? 'secondary' }}">{{ $module['level'] ?? '' }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="col">
                                    <div class="alert alert-secondary mb-0">No modules yet.</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- Recent Sessions -->
                <div class="card mt-4 shadow-sm">
                    <div class="card-body">
                        <div class="fw-bold mb-2">Recent Sessions</div>
                        <div class="row g-3">
                            @forelse($recentSessions ?? [] as $session)
                                <div class="col-md-4">
                                    <div class="p-3 bg-light rounded border">
                                        <div class="fw-semibold">{{ $session->module_name ?? 'N/A' }}</div>
                                        <div class="text-muted small">{{ $session->created_at ? $session->created_at->format('Y-m-d') : 'N/A' }}</div>
                                        <div class="fw-bold
                                            @if(($session->score ?? 0) >= 85) text-success
                                            @elseif(($session->score ?? 0) >= 75) text-info
                                            @else text-warning @endif
                                        ">
                                            {{ $session->score ?? 0 }}%
                                            <span class="text-muted small">
                                                {{ $session->feedback ?? '' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col"><div class="alert alert-secondary mb-0">No recent sessions.</div></div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Practice Tab -->
            <div class="tab-pane fade" id="practice" role="tabpanel" aria-labelledby="practice-tab">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <div class="fw-bold mb-2">Practice Session</div>
                        <div class="text-muted mb-4">Record your pronunciation and get instant AI feedback</div>

                        <!-- Hidden fields for exercise data -->
                        <input type="hidden" id="exercise-id" value="{{ $practiceExercise['id'] ?? '' }}">
                        <input type="hidden" id="exercise-target" value="{{ $practiceExercise['word'] ?? '' }}">

                        <div class="mb-3">
                            <span class="d-inline-block p-4 mb-2 rounded-circle" style="background:#f1f3f7;">
                                <i class="fa-solid fa-microphone" style="font-size:2.4rem; color:#4674fa;"></i>
                            </span>
                        </div>

                        <div class="fw-semibold mb-2">{{ $practiceExercise['title'] ?? 'No exercise' }}</div>
                        <div class="mb-4">
                            Practice pronouncing: <b>{{ $practiceExercise['word'] ?? '' }}</b>
                        </div>

                        <div class="d-flex justify-content-center gap-2 mb-4">
                            <button class="btn btn-dark px-4" id="btn-record">
                                <i class="fa-solid fa-circle-dot me-2"></i>Start Recording
                            </button>
                            <button class="btn btn-outline-secondary px-4" id="btn-play-example">
                                <i class="fa-solid fa-volume-up me-2"></i>Play Example
                            </button>
                        </div>

                        <audio id="practice-example-audio"
                            src="{{ asset('exercises/audio/example_' . ($practiceExercise['id'] ?? 1) . '.mp3') }}">
                        </audio>

                        <div id="ai-feedback-result"></div>
                    </div>
                </div>
            </div>

            <!-- Progress Tab -->
            <div class="tab-pane fade" id="progress" role="tabpanel">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card shadow-sm mb-3">
                            <div class="card-body">
                                <div class="fw-bold mb-3">Weekly Progress</div>
                                <div class="mb-2">Pronunciation Accuracy <span class="float-end fw-bold">{{ $weeklyProgress['accuracy'] ?? 0 }}%</span></div>
                                <div class="progress mb-3"><div class="progress-bar" style="width:{{ $weeklyProgress['accuracy'] ?? 0 }}%"></div></div>
                                <div class="mb-2">Speaking Fluency <span class="float-end fw-bold">{{ $weeklyProgress['fluency'] ?? 0 }}%</span></div>
                                <div class="progress mb-3"><div class="progress-bar bg-info" style="width:{{ $weeklyProgress['fluency'] ?? 0 }}%"></div></div>
                                <div class="mb-2">Confidence Level <span class="float-end fw-bold">{{ $weeklyProgress['confidence'] ?? 0 }}%</span></div>
                                <div class="progress mb-3"><div class="progress-bar bg-success" style="width:{{ $weeklyProgress['confidence'] ?? 0 }}%"></div></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-sm mb-3">
                            <div class="card-body">
                                <div class="fw-bold mb-3">Achievements</div>
                                <ul class="list-unstyled">
                                    @forelse($achievements ?? [] as $achievement)
                                    <li class="mb-2">
                                        <i class="fa-solid {{ $achievement['icon'] ?? 'fa-award' }} {{ $achievement['icon_color'] ?? '' }} me-2"></i>
                                        {{ $achievement['text'] ?? 'Achievement unlocked!' }}
                                    </li>
                                    @empty
                                    <li>No achievements yet.</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Feedback Tab -->
            <div class="tab-pane fade" id="feedback" role="tabpanel">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="fw-bold mb-3">Coach Feedback</div>
                        <div class="small text-muted mb-3">Personalized guidance from your pronunciation coach</div>
                        @forelse($coachFeedbacks ?? [] as $feedback)
                        <div class="border-start border-4 border-{{ $feedback['color'] ?? 'primary' }} ps-3 mb-3">
                            <div class="fw-semibold">{{ $feedback['coach'] ?? 'Coach' }}</div>
                            <div class="small">{{ $feedback['message'] ?? '' }}</div>
                            <div class="text-end text-muted small">{{ $feedback['date'] ?? '' }}</div>
                        </div>
                        @empty
                        <div class="alert alert-secondary mb-0">No feedback yet.</div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ---------- Helper functions for scoring & feedback ---------- */
    function levenshtein(a, b) {
        a = (a || "").toLowerCase().trim();
        b = (b || "").toLowerCase().trim();
        const m = a.length, n = b.length;
        if (m === 0) return n;
        if (n === 0) return m;
        const dp = Array.from({ length: m + 1 }, (_, i) => [i]);
        for (let j = 1; j <= n; j++) dp[0][j] = j;
        for (let i = 1; i <= m; i++) {
            for (let j = 1; j <= n; j++) {
                const cost = a[i - 1] === b[j - 1] ? 0 : 1;
                dp[i][j] = Math.min(
                    dp[i - 1][j] + 1,
                    dp[i][j - 1] + 1,
                    dp[i - 1][j - 1] + cost
                );
            }
        }
        return dp[m][n];
    }
    function percentSimilarity(a, b) {
        const dist = levenshtein(a, b);
        const maxLen = Math.max((a||"").length, (b||"").length) || 1;
        return Math.max(0, Math.round(((maxLen - dist) / maxLen) * 100));
    }
    function feedbackText(score) {
        if (score >= 85) return "Great job!";
        if (score >= 70) return "Nice—almost perfect. Tidy up small sounds.";
        if (score >= 50) return "Getting there. Slow down and stress each syllable.";
        return "Keep practicing—focus on vowel clarity and syllable stress.";
    }
    /* ------------------------------------------------------------- */

    let mediaRecorder, recordedChunks = [], isRecording = false;

    const recordBtn    = document.getElementById('btn-record');
    const playBtn      = document.getElementById('btn-play-example');
    const exampleAudio = document.getElementById('practice-example-audio'); // optional file; TTS fallback
    const feedbackDiv  = document.getElementById('ai-feedback-result');

    const exerciseId     = document.getElementById('exercise-id')?.value || '';
    const exerciseTarget = document.getElementById('exercise-target')?.value || '';
    const csrfToken      = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function updateRecordBtnState(rec) {
        recordBtn.innerHTML = rec
            ? '<i class="fa-solid fa-stop me-2"></i>Stop Recording'
            : '<i class="fa-solid fa-circle-dot me-2"></i>Start Recording';
        recordBtn.classList.toggle('btn-danger', rec);
        recordBtn.classList.toggle('btn-dark', !rec);
    }

    async function handleRecordClick() {
        if (isRecording) {
            mediaRecorder.stop();
            isRecording = false;
            updateRecordBtnState(false);
            return;
        }
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            const mime = MediaRecorder.isTypeSupported('audio/webm;codecs=opus')
                ? 'audio/webm;codecs=opus'
                : (MediaRecorder.isTypeSupported('audio/webm') ? 'audio/webm' : '');
            recordedChunks = [];
            mediaRecorder = new MediaRecorder(stream, mime ? { mimeType: mime } : undefined);
            mediaRecorder.ondataavailable = e => { if (e.data.size > 0) recordedChunks.push(e.data); };
            mediaRecorder.onstop = sendRecording;
            mediaRecorder.start();
            isRecording = true;
            updateRecordBtnState(true);
        } catch (err) {
            console.error(err);
            alert('Microphone permission denied or not supported.');
        }
    }

    async function sendRecording() {
        try {
            const mime = mediaRecorder.mimeType || 'audio/webm';
            const blob = new Blob(recordedChunks, { type: mime });
            const fileName = mime.includes('webm') ? 'recording.webm' : 'recording.wav';

            const form = new FormData();
            form.append('audio', blob, fileName);
            form.append('exercise_id', exerciseId);
            form.append('target_text', exerciseTarget);

            feedbackDiv.innerHTML = '<div class="alert alert-info mb-0">Analyzing… please wait.</div>';

            const res = await fetch(`{{ route('stt.whisper') }}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken },
                body: form,
                credentials: 'same-origin'
            });

            const data = await res.json();
            if (!res.ok) throw new Error(data?.error || data?.message || 'Upload failed');

            // Use backend score if present; otherwise compute similarity
            let said  = (data.text || '').trim();
            let score = Number.isFinite(Number(data.score)) ? Number(data.score) : percentSimilarity(said, exerciseTarget);
            let tip   = data.tips || feedbackText(score);

            feedbackDiv.innerHTML = `
                <div class="p-3 rounded border">
                    <div class="fw-semibold mb-2">AI Feedback</div>
                    <div class="small mb-1"><b>Target:</b> ${escapeHtml(exerciseTarget)}</div>
                    <div class="small mb-2"><b>You said:</b> ${escapeHtml(said)}</div>
                    <div class="mb-2">
                        <span class="badge bg-${scoreColor(score)}">Score: ${score}%</span>
                    </div>
                    <div class="small">${escapeHtml(tip)}</div>
                </div>
            `;
        } catch (e) {
            console.error(e);
            feedbackDiv.innerHTML = `<div class="alert alert-danger mb-0">Error: ${escapeHtml(String(e.message || e))}</div>`;
        }
    }

    // ---------- PLAY EXAMPLE ----------
    async function handlePlayExample() {
        // 1) Try to play the MP3 if it exists
        if (exampleAudio && exampleAudio.src) {
            try {
                await exampleAudio.play();
                return;
            } catch (err) {
                // fall back to TTS
            }
        }
        // 2) Fallback to browser TTS
        speakTTS(exerciseTarget || 'Say the word');
    }

   function speakTTS(text) {
    if (!('speechSynthesis' in window)) {
        alert('Text-to-Speech not supported in this browser.');
        return;
    }
    playBtn.disabled = true;
    const utter = new SpeechSynthesisUtterance(text);

    // Pick female English voice if available
    const pickVoice = () => {
        const voices = window.speechSynthesis.getVoices();
        const femaleVoice = voices.find(v =>
            /en(-|_|$)/i.test(v.lang) && /female|woman|girl/i.test(v.name)
        );
        const enVoice = voices.find(v => /en(-|_|$)/i.test(v.lang));
        utter.voice = femaleVoice || enVoice || voices[0];
    };
    pickVoice();
    window.speechSynthesis.onvoiceschanged = pickVoice;

    utter.rate = 0.8;  // slower speed for clarity
    utter.pitch = 1.2; // slightly higher pitch for female tone
    utter.volume = 1.0;

    utter.onend = () => { playBtn.disabled = false; };
    utter.onerror = () => { playBtn.disabled = false; };

    window.speechSynthesis.cancel();
    window.speechSynthesis.speak(utter);
}

    // -----------------------------------

    function scoreColor(s) {
        s = Number(s || 0);
        if (s >= 85) return 'success';
        if (s >= 70) return 'info';
        if (s >= 50) return 'warning';
        return 'danger';
    }
    function escapeHtml(str) {
        return String(str)
            .replaceAll('&','&amp;').replaceAll('<','&lt;').replaceAll('>','&gt;')
            .replaceAll('"','&quot;').replaceAll("'",'&#039;');
    }

    if (recordBtn) recordBtn.addEventListener('click', handleRecordClick);
    if (playBtn)   playBtn.addEventListener('click', handlePlayExample);
});
</script>
@endsection
