@extends('layouts.admin')

@section('content')
<div class="container-fluid" style="background: #f7f8fd; min-height: 100vh;">
    <div class="row">
        <div class="col-12">
            <h4 class="fw-bold mb-2">System Overview</h4>
            <div class="text-muted mb-4">Monitor and manage the TalkEase platform</div>
            <ul class="nav nav-tabs mb-4" id="adminTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">Overview</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="user-management-tab" data-bs-toggle="tab" data-bs-target="#user-management" type="button" role="tab">User Management</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="analytics-tab" data-bs-toggle="tab" data-bs-target="#analytics" type="button" role="tab">Analytics</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="system-logs-tab" data-bs-toggle="tab" data-bs-target="#system-logs" type="button" role="tab">System Logs</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button" role="tab">Settings</button>
                </li>
            </ul>

            <div class="tab-content" id="adminTabContent">
                <div class="tab-pane fade show active" id="overview" role="tabpanel">
                    <div class="row g-4 mb-4">
                        <div class="col-md-2"><div class="card shadow-sm border-0 rounded-3 p-3 text-center">
                            <div id="totalUsers" class="fs-4 fw-bold">{{ $totalUsers ?? 0 }}</div>
                            <div class="text-muted">Total Users</div>
                            <div class="small text-success mt-1">+12% from last month</div>
                        </div></div>
                        <div class="col-md-2"><div class="card shadow-sm border-0 rounded-3 p-3 text-center">
                            <div id="activeStudents" class="fs-4 fw-bold">{{ $activeStudents ?? 0 }}</div>
                            <div class="text-muted">Active Students</div>
                            <div class="small text-muted mt-1">{{ ($totalUsers ?? 0) > 0 ? round(($activeStudents ?? 0) / $totalUsers * 100) : 0 }}% of total users</div>
                        </div></div>
                        <div class="col-md-2"><div class="card shadow-sm border-0 rounded-3 p-3 text-center">
                            <div id="coaches" class="fs-4 fw-bold">{{ $coaches ?? 0 }}</div>
                            <div class="text-muted">Coaches</div>
                            <div class="small text-muted mt-1">All active</div>
                        </div></div>
                        <div class="col-md-2"><div class="card shadow-sm border-0 rounded-3 p-3 text-center">
                            <div id="totalSessions" class="fs-4 fw-bold">{{ $totalSessions ?? 0 }}</div>
                            <div class="text-muted">Total Sessions</div>
                            <div class="small text-success mt-1">+8% this week</div>
                        </div></div>
                        <div class="col-md-2"><div class="card shadow-sm border-0 rounded-3 p-3 text-center">
                            <div id="reviewsCount" class="fs-4 fw-bold">{{ $reviewsCount ?? 0 }}</div>
                            <div class="text-muted">Total Reviews</div>
                            <div class="small text-info mt-1">All time</div>
                        </div></div>
                        <div class="col-md-2"><div class="card shadow-sm border-0 rounded-3 p-3 text-center">
                            <div id="uptime" class="fs-4 fw-bold">{{ $uptime ?? 'N/A' }}</div>
                            <div class="text-muted">Uptime</div>
                            <div class="small text-muted mt-1">Last 30 days</div>
                        </div></div>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6"><div class="card shadow-sm border-0 rounded-3 p-4">
                            <h5 class="fw-semibold mb-3">Recent User Registrations</h5>
                            @forelse($recentUsers as $user)
                                <div class="mb-3 d-flex align-items-center justify-content-between border-bottom pb-2">
                                    <div>
                                        <span class="fw-bold">
                                            <i class="fa-solid fa-user-circle me-2 
                                                @if($user->role === 'admin') text-danger
                                                @elseif($user->role === 'coach') text-info
                                                @else text-primary
                                                @endif"></i>
                                            {{ $user->first_name }} {{ $user->last_name }}
                                        </span>
                                        <span class="badge ms-2
                                            @if($user->role === 'admin') bg-danger
                                            @elseif($user->role === 'coach') bg-info text-dark
                                            @else bg-light text-dark
                                            @endif">{{ strtoupper($user->role) }}</span>
                                        <div class="small text-muted">Joined: {{ $user->created_at->format('Y-m-d') }}</div>
                                    </div>
                                    <span class="badge {{ $user->email_verified_at ? 'bg-dark' : 'bg-secondary' }}">
                                        {{ $user->email_verified_at ? 'Active' : 'Pending' }}
                                    </span>
                                </div>
                            @empty
                                <div class="text-muted">No recent registrations found.</div>
                            @endforelse
                        </div></div>

                        <div class="col-md-6"><div class="card shadow-sm border-0 rounded-3 p-4">
                            <h5 class="fw-semibold mb-3">System Health</h5>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div>Server Status</div>
                                <span id="health-server" class="badge bg-dark">{{ $systemHealth['server'] ?? '' }}</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div>Database</div>
                                <span id="health-database" class="badge bg-success">{{ $systemHealth['database'] ?? '' }}</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div>AI Services</div>
                                <span id="health-ai" class="badge bg-dark">{{ $systemHealth['ai'] ?? '' }}</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div>Storage</div>
                                <span id="health-storage" class="badge bg-secondary">{{ $systemHealth['storage'] ?? '' }}</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <div>Response Time</div>
                                <span id="health-response" class="badge bg-secondary">{{ $systemHealth['response'] ?? '' }}</span>
                            </div>
                        </div></div>
                    </div>
                </div>

                <div class="tab-pane fade" id="user-management" role="tabpanel">
                    @include('admin.partials.users')
                </div>

                <div class="tab-pane fade" id="analytics" role="tabpanel">
                    @include('admin.partials.analytics', [
                        'dailyActiveUsers'   => $dailyActiveUsers ?? 0,
                        'avgSessionDuration' => $avgSessionDuration ?? '0s',
                        'completionRate'     => $completionRate ?? 0,
                        'aiAccuracy'         => $aiAccuracy ?? 0,
                        'userSatisfaction'   => $userSatisfaction ?? 0,
                        'errorRate'          => $errorRate ?? 0,
                    ])
                </div>

                <div class="tab-pane fade" id="system-logs" role="tabpanel">
                    @include('admin.partials.system-logs', ['logs' => $logs])
                </div>

                <div class="tab-pane fade" id="settings" role="tabpanel">
                    @include('admin.partials.settings')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
function refreshDashboardStats() {
    $.get("{{ route('admin.dashboard.stats') }}", function(data) {
        $('#totalUsers').text(data.totalUsers);
        $('#activeStudents').text(data.activeStudents);
        $('#coaches').text(data.coaches);
        $('#totalSessions').text(data.totalSessions);
        $('#reviewsCount').text(data.reviewsCount);
        $('#uptime').text(data.uptime);
        $('#health-server').text(data.systemHealth.server);
        $('#health-database').text(data.systemHealth.database);
        $('#health-ai').text(data.systemHealth.ai);
        $('#health-storage').text(data.systemHealth.storage);
        $('#health-response').text(data.systemHealth.response);
    });
}
refreshDashboardStats();
setInterval(refreshDashboardStats, 10000);
</script>
@endpush
