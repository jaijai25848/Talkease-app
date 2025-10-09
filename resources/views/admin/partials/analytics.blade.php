<div class="row g-3">
  <div class="col-md-4">
    <div class="card shadow-sm"><div class="card-body d-flex justify-content-between">
      <span>Daily Active Users</span>
      <span class="fw-bold">{{ $dailyActiveUsers ?? 0 }}</span>
    </div></div>
  </div>

  <div class="col-md-4">
    <div class="card shadow-sm"><div class="card-body d-flex justify-content-between">
      <span>Average Progress (Completion)</span>
      <span class="fw-bold">{{ $avgProgress ?? ($completionRate ?? 0) }}%</span>
    </div></div>
  </div>

  <div class="col-md-4">
    <div class="card shadow-sm"><div class="card-body d-flex justify-content-between">
      <span>Total Sessions</span>
      <span class="fw-bold">{{ $totalSessions ?? 0 }}</span>
    </div></div>
  </div>

  <div class="col-md-4">
    <div class="card shadow-sm"><div class="card-body d-flex justify-content-between">
      <span>Avg Session Duration</span>
      <span class="fw-bold">{{ $avgSessionDuration ?? 'n/a' }}</span>
    </div></div>
  </div>

  <div class="col-md-4">
    <div class="card shadow-sm"><div class="card-body d-flex justify-content-between">
      <span>AI Accuracy</span>
      <span class="fw-bold">{{ $aiAccuracy ?? 0 }}%</span>
    </div></div>
  </div>

  <div class="col-md-4">
    <div class="card shadow-sm"><div class="card-body d-flex justify-content-between">
      <span>User Satisfaction</span>
      <span class="fw-bold">{{ $userSatisfaction ?? 0 }}/5</span>
    </div></div>
  </div>

  <div class="col-md-4">
    <div class="card shadow-sm"><div class="card-body d-flex justify-content-between">
      <span>Error Rate</span>
      <span class="fw-bold">{{ $errorRate ?? 0 }}%</span>
    </div></div>
  </div>
</div>
