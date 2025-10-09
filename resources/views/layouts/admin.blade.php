<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TalkEase Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap & FontAwesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body { background: #f7f8fd; }
        .sidebar-admin {
            min-height: 100vh;
            background: #fff;
            border-right: 1.5px solid #e6e8ee;
            box-shadow: 2px 0 18px 0 rgba(115,82,255,0.06);
            backdrop-filter: blur(7px);
            transition: all 0.3s;
        }
        .sidebar-admin .nav-link {
            color: #232323;
            font-weight: 500;
            border-radius: 1rem;
            margin-bottom: .3rem;
            font-size: 1.08rem;
            transition: background 0.15s, color 0.15s, box-shadow 0.15s;
            padding: .7rem 1.15rem .7rem 1.35rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }
        .sidebar-admin .nav-link i {
            font-size: 1.13em;
        }
        .sidebar-admin .nav-link.active,
        .sidebar-admin .nav-link:hover {
            background: linear-gradient(90deg, #f3efff 20%, #e7e9fa 100%);
            color: #7352ff !important;
            box-shadow: 0 2px 14px 0 rgba(115,82,255,0.06);
        }
        .sidebar-brand {
            font-weight: bold;
            font-size: 1.37rem;
            display: flex;
            align-items: center;
            gap: 1.1rem;
            letter-spacing: 0.5px;
        }
        .sidebar-brand .mic-badge {
            background: linear-gradient(135deg, #7352ff 0%, #38bdf8 100%);
            color: #fff !important;
            border-radius: 12px;
            padding: 8px 13px 8px 12px;
            font-size: 1.25rem;
            box-shadow: 0 2px 8px 0 rgba(103,82,255,0.10);
            display: inline-block;
            line-height: 1;
        }
        .profile-pic {
            width: 34px;
            height: 34px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #e0d7ff;
            box-shadow: 0 2px 8px 0 rgba(103,82,255,0.10);
        }
        .admin-header {
            background: #fff;
            border-bottom: 1.5px solid #e6e8ee;
            padding: 1.2rem 2.3rem 1.2rem 1.7rem;
            display: flex;
            align-items: center;
            border-top-right-radius: 1.1rem;
            box-shadow: 0 4px 24px 0 rgba(115,82,255,0.05);
        }
        .admin-header .dropdown-menu {
            margin-top: .5rem;
            min-width: 170px;
            border-radius: .9rem;
        }
        .admin-header .btn {
            border-radius: 50%;
            padding: .5rem .65rem;
        }
        .tab-card { background: #fff; border-radius: 1rem; }
        @media (max-width: 900px) {
            .sidebar-admin { width: 100px !important; padding: .4rem !important; }
            .sidebar-admin .nav-link span,
            .sidebar-brand span { display: none !important; }
            .sidebar-brand .mic-badge { padding: 8px; }
        }
        @media (max-width: 700px) {
            .sidebar-admin { display: none; }
        }
    </style>
    @yield('head')
</head>
<body>
<div class="d-flex">
    <!-- Sidebar -->
    <aside class="sidebar-admin p-3" style="width:220px;">
        <div class="mb-4 sidebar-brand">
            <span class="mic-badge">
                <i class="fa-solid fa-microphone fa-lg"></i>
            </span>
            <span>TalkEase</span>
        </div>
        <div class="small text-muted mb-4 ps-2">Admin Dashboard</div>
        <nav class="nav flex-column">
            <a href="{{ route('admin.dashboard') }}"
               class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-table-columns me-2"></i> <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.users') }}"
                   class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                <i class="fa-solid fa-users-gear me-2"></i> <span>User Management</span>
            </a>
            <a href="{{ route('admin.system-logs') }}"
               class="nav-link {{ request()->routeIs('admin.system-logs') ? 'active' : '' }}">
                <i class="fa-solid fa-clipboard-list me-2"></i> <span>System Logs</span>
            </a>
            <a href="{{ route('admin.analytics') }}"
               class="nav-link {{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-line me-2"></i> <span>Analytics</span>
            </a>
            <a href="{{ route('admin.settings') }}"
               class="nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                <i class="fa-solid fa-gear me-2"></i> <span>Settings</span>
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-fill" style="min-width:0;">
        <!-- Top Navbar/Header -->
        <header class="admin-header d-flex justify-content-end align-items-center">
            <div class="me-3 text-end">
                <div class="fw-semibold">
                    {{ Auth::user()->first_name . ' ' . Auth::user()->last_name ?? 'System Administrator' }}
                </div>
                <small class="text-muted">System Administrator</small>
            </div>
            <img src="{{ Auth::user()->avatar 
                        ? asset('storage/avatars/' . Auth::user()->avatar) 
                        : asset('images/avatar.png') }}" alt="Avatar" class="profile-pic me-3">
            <div class="dropdown">
                <button class="btn btn-sm btn-light border-0" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-gear"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item" type="submit">
                                <i class="fa-solid fa-right-from-bracket me-2"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </header>
        <main class="p-4" style="min-height:100vh;">
            @yield('content')
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>
