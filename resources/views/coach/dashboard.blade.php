@extends('layouts.coach')

@section('content')
<h3 class="fw-bold mb-1">Welcome back, Coach {{ Auth::user()->first_name }}!</h3>
<div class="text-muted mb-4" style="font-size:1rem;">
    Manage your students and provide personalized feedback
</div>
<!-- Tabs -->
<ul class="nav nav-tabs mb-4" id="coachTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">Overview</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="students-tab" data-bs-toggle="tab" data-bs-target="#students" type="button" role="tab">Students</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab">Pending Reviews</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="analytics-tab" data-bs-toggle="tab" data-bs-target="#analytics" type="button" role="tab">Analytics</button>
    </li>
</ul>
<div class="tab-content" id="coachTabContent">
    <!-- Overview Tab -->
    <div class="tab-pane fade show active" id="overview" role="tabpanel">
        <div class="row g-4 mb-3">
            <div class="col-md-3">
                <div class="card shadow-sm border-0 rounded-3 p-3">
                    <div class="fs-2 fw-bold">{{ $totalStudents ?? 0 }}</div>
                    <div class="text-muted">Total Students</div>
                    <div class="small text-success mt-1">+{{ $newStudentsThisWeek ?? 0 }} new this week</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0 rounded-3 p-3">
                    <div class="fs-2 fw-bold">{{ $pendingReviews ?? 0 }}</div>
                    <div class="text-muted">Pending Reviews</div>
                    <div class="small text-danger mt-1">{{ $urgentReviews ?? 0 }} urgent</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0 rounded-3 p-3">
                    <div class="fs-2 fw-bold">{{ number_format($avgProgress ?? 0, 1) }}%</div>
                    <div class="text-muted">Avg. Student Progress</div>
                    <div class="small text-success mt-1">+{{ number_format($progressThisMonth ?? 0, 1) }}% this month</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0 rounded-3 p-3">
                    <div class="fs-2 fw-bold">{{ number_format($hoursThisWeek ?? 0, 1) }}</div>
                    <div class="text-muted">Hours This Week</div>
                    <div class="small text-muted mt-1">{{ number_format($hoursRemaining ?? 0, 1) }} hours remaining</div>
                </div>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-7">
                <div class="card shadow-sm border-0 rounded-3 p-3">
                    <h5 class="fw-semibold mb-3">Recent Student Activity</h5>
                    @forelse ($recentActivities as $activity)
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div>
                                <span class="fw-bold">{{ $activity['name'] }}</span>
                                <div class="small text-muted">Last session: {{ $activity['last_session'] }}</div>
                            </div>
                            <div>
                                <span class="badge bg-light text-dark px-3 py-2">{{ $activity['progress'] }}%</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-muted">No recent activity found.</div>
                    @endforelse
                </div>
            </div>
            <div class="col-md-5">
                <div class="card shadow-sm border-0 rounded-3 p-3">
                    <h5 class="fw-semibold mb-3">Urgent Reviews</h5>
                    @forelse ($urgentReviewsList as $review)
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div>
                                <span class="fw-bold">{{ $review['name'] }}</span>
                                <div class="small text-muted">{{ $review['category'] }} · {{ $review['submitted'] }}</div>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-light text-dark px-3 py-2">{{ $review['score'] }}%</span>
                                <div class="small text-muted">{{ $review['duration'] }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-muted">No urgent reviews.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    <!-- Students Tab -->
    <div class="tab-pane fade" id="students" role="tabpanel">
        <div class="card shadow-sm border-0 rounded-3 p-3">
            <h5 class="fw-semibold mb-3">Student Management</h5>
            <div class="mb-3">
                <input type="text" class="form-control" placeholder="Search students..." />
            </div>
            @forelse ($students as $student)
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <span class="fw-bold">{{ $student['name'] }}</span>
                        <div class="small text-muted">{{ $student['email'] }}</div>
                    </div>
                    <div>
                        <span class="badge bg-primary text-white px-2 py-1">{{ $student['level'] }}</span>
                        <span class="mx-2">{{ $student['progress'] }}</span>
                        <span class="text-muted small">Last: {{ $student['last_session'] }}</span>
                    </div>
                    <a href="#" class="btn btn-outline-primary btn-sm ms-3">View Details</a>
                </div>
            @empty
                <div class="text-muted">No students found.</div>
            @endforelse
        </div>
    </div>
    <!-- Pending Reviews Tab -->
    <div class="tab-pane fade" id="pending" role="tabpanel">
        <div class="card shadow-sm border-0 rounded-3 p-3">
            <h5 class="fw-semibold mb-3">Pending Reviews</h5>
            @forelse ($pendingReviewsList as $review)
                <div class="mb-4 border-bottom pb-3">
                    <div class="fw-bold mb-1">{{ $review['name'] }}</div>
                    <div class="small text-muted mb-1">{{ $review['category'] }} · Submitted {{ $review['submitted'] }}</div>
                    <span class="badge bg-light text-dark me-2">{{ $review['score'] }}%</span>
                    <span class="text-muted small me-2">Duration: {{ $review['duration'] }}</span>
                    <div class="my-2">
                        <button class="btn btn-outline-secondary btn-sm me-2">Play Recording</button>
                        <button class="btn btn-outline-info btn-sm me-2">Compare with Example</button>
                    </div>
                    <textarea class="form-control mb-2" placeholder="Provide detailed feedback on pronunciation, areas for improvement, and encouragement..."></textarea>
                    <div>
                        <span class="me-2">Rating:</span>
                        <span>
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="far fa-star text-warning"></i>
                            @endfor
                        </span>
                        <button class="btn btn-success btn-sm ms-3">Submit Feedback</button>
                        <button class="btn btn-danger btn-sm ms-2">Request Re-recording</button>
                    </div>
                </div>
            @empty
                <div class="text-muted">No pending reviews.</div>
            @endforelse
        </div>
    </div>
    <!-- Analytics Tab -->
    <div class="tab-pane fade" id="analytics" role="tabpanel">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card shadow-sm border-0 rounded-3 p-3">
                    <h5 class="fw-semibold mb-2">Student Performance Trends</h5>
                    <div class="mb-1">Average Improvement Rate: <strong>+{{ number_format($avgImprovementRate ?? 0, 1) }}% monthly</strong></div>
                    <div class="mb-1">Session Completion Rate: <strong>{{ $completionRate ?? 0 }}%</strong></div>
                    <div class="mb-1">Student Satisfaction: <strong>{{ number_format($studentSatisfaction ?? 0, 1) }}/5</strong></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm border-0 rounded-3 p-3">
                    <h5 class="fw-semibold mb-2">Common Challenges</h5>
                    @forelse ($challenges as $challenge)
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span>{{ $challenge['name'] }}</span>
                            <span class="badge {{ $challenge['level'] === 'High' ? 'bg-danger' : ($challenge['level'] === 'Medium' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                                {{ $challenge['level'] }}
                            </span>
                        </div>
                    @empty
                        <div class="text-muted">No challenge data yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
