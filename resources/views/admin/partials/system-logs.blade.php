<div class="card shadow-sm border-0 rounded-3 p-4">
  <h5 class="fw-semibold mb-3">
    <i class="fa fa-history me-2 text-primary"></i>
    System Activity Logs
  </h5>

  <div class="list-group list-group-flush">
    @forelse($logs as $log)
      @php
        // Safe reads even if props/paths don't exist
        $first = data_get($log, 'user.first_name');
        $last  = data_get($log, 'user.last_name');
        $email = data_get($log, 'user.email') ?? data_get($log, 'user_email');
        $name  = trim(($first ?? '').' '.($last ?? ''));
        $hasUser = ($first || $last || $email);

        $statusRaw = $log->status ?? '';
        $status    = is_string($statusRaw) ? strtolower($statusRaw) : '';
        $dotClass  = $status === 'success' ? 'text-success' : 'text-danger';
        $badge     = $status === 'success' ? 'bg-success' : 'bg-danger';

        $when = \Illuminate\Support\Arr::get((array) $log, 'created_at');
        $whenText = $when ? \Carbon\Carbon::parse($when)->format('Y-m-d H:i') : '';
        $desc = $log->description ?? '';
      @endphp

      <div class="list-group-item d-flex flex-wrap justify-content-between align-items-center px-0 border-0 py-3">
        <div class="d-flex align-items-center">
          <span class="{{ $dotClass }} me-2" title="{{ $statusRaw }}">
            <i class="fa fa-circle"></i>
          </span>
          <span class="fw-bold">{{ $desc }}</span>
          <span class="text-muted ms-2">
            @if($hasUser)
              {{ $name !== '' ? $name : $email }}
              @if($name !== '' && $email)
                <span class="small text-muted">&lt;{{ $email }}&gt;</span>
              @endif
            @else
              <span class="text-danger">[Deleted User]</span>
            @endif
          </span>
        </div>
        <div class="d-flex flex-column align-items-end">
          <span class="text-muted small">{{ $whenText }}</span>
          <span class="badge {{ $badge }} mt-1" style="min-width:75px;">
            {{ $statusRaw ?: 'unknown' }}
          </span>
        </div>
      </div>
    @empty
      <div class="text-center text-muted p-3">No activity logs found.</div>
    @endforelse
  </div>
</div>
