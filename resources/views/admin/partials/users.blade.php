{{-- Users table partial.
     Expects $users (Collection of App\Models\User) but will render gracefully if absent. --}}
<div class="card shadow-sm">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0">User Management</h5>
      @if(isset($users))
        <span class="badge bg-secondary">{{ $users->count() }} users</span>
      @endif
    </div>

    @if(!isset($users) || $users->isEmpty())
      <div class="alert alert-info mb-0">
        No users to display yet. Pass a <code>$users</code> collection from your controller to populate this table.
      </div>
    @else
      <div class="table-responsive">
        <table class="table table-sm align-middle">
          <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Email</th>
              <th>Role</th>
              <th>Joined</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($users as $i => $u)
              <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $u->name ?? '—' }}</td>
                <td>{{ $u->email ?? '—' }}</td>
                <td>
                  @php $role = $u->role ?? ($u->is_admin ?? false ? 'admin' : 'user'); @endphp
                  <span class="badge {{ $role==='admin' ? 'bg-primary' : 'bg-secondary' }}">{{ $role }}</span>
                </td>
                <td>{{ optional($u->created_at)->format('Y-m-d') ?? '—' }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>
</div>
