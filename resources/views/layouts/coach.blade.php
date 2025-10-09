<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TalkEase Coach Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS & FontAwesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body { background: linear-gradient(110deg, #f6f7ff 0%, #f5fafc 100%); }
        .sidebar-coach {
            min-height: 100vh;
            background: rgba(255,255,255,0.95);
            border-right: 1.5px solid #e6e8ee;
            box-shadow: 2px 0 18px 0 rgba(103,82,255,0.06);
            backdrop-filter: blur(7px);
            transition: all 0.3s;
        }
        .sidebar-coach .nav-link {
            color: #443c69;
            font-weight: 500;
            border-radius: 1rem;
            margin-bottom: .3rem;
            font-size: 1.07rem;
            transition: background 0.16s, color 0.16s, box-shadow 0.16s;
            padding: .7rem 1.2rem .7rem 1.35rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }
        .sidebar-coach .nav-link i {
            font-size: 1.19em;
        }
        .sidebar-coach .nav-link.active,
        .sidebar-coach .nav-link:hover {
            background: linear-gradient(90deg, #f3efff 20%, #e7e9fa 100%);
            color: #7352ff !important;
            box-shadow: 0 2px 14px 0 rgba(103,82,255,0.07);
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
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #e0d7ff;
            box-shadow: 0 2px 8px 0 rgba(103,82,255,0.10);
        }
        .coach-header {
            background: rgba(255,255,255,0.96);
            border-bottom: 1.5px solid #e6e8ee;
            padding: 1.2rem 2.3rem 1.2rem 1.7rem;
            display: flex;
            align-items: center;
            border-top-right-radius: 1.1rem;
            box-shadow: 0 4px 24px 0 rgba(103,82,255,0.08);
        }
        .coach-header .dropdown-menu {
            margin-top: .5rem;
            min-width: 170px;
            border-radius: .9rem;
        }
        .coach-header .btn {
            border-radius: 50%;
            padding: .5rem .65rem;
        }
        .custom-scroll {
            max-height: 96vh;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #ded8fd #f6f9fc;
        }
        @media (max-width: 900px) {
            .sidebar-coach { width: 100px !important; padding: .4rem !important; }
            .sidebar-coach .nav-link span,
            .sidebar-brand span { display: none !important; }
            .sidebar-brand .mic-badge { padding: 8px; }
        }
        @media (max-width: 700px) {
            .sidebar-coach { display: none; }
        }
    </style>
    @yield('head')
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <aside class="sidebar-coach p-3 custom-scroll" style="width:230px;">
            <div class="mb-4 sidebar-brand">
                <span class="mic-badge">
                    <i class="fa-solid fa-microphone fa-lg"></i>
                </span>
                <span>TalkEase</span>
            </div>
            <nav class="nav flex-column">
                <a href="{{ route('coach.dashboard') }}" class="nav-link {{ Request::routeIs('coach.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-table-columns me-2"></i> <span>Dashboard</span>
                </a>
                <a href="#" class="nav-link {{ Request::is('coach/students*') ? 'active' : '' }}">
                    <i class="fa-solid fa-users me-2"></i> <span>Students</span>
                </a>
                <a href="#" class="nav-link {{ Request::is('coach/reviews*') ? 'active' : '' }}">
                    <i class="fa-solid fa-star me-2"></i> <span>Reviews</span>
                </a>
                <a href="#" class="nav-link {{ Request::is('coach/analytics*') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-bar me-2"></i> <span>Analytics</span>
                </a>
                <a href="#" class="nav-link {{ Request::is('coach/profile*') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-circle me-2"></i> <span>Profile</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-fill" style="min-width:0;">
            <!-- Top Navbar/Header -->
            <header class="coach-header d-flex justify-content-end align-items-center gap-2">
                <div class="me-3 text-end">
                    <div class="fw-semibold">
                        {{ trim(Auth::user()->first_name . ' ' . Auth::user()->last_name) ?: Auth::user()->email }}
                    </div>
                    <small class="text-muted">Coach</small>
                </div>
                <img src="{{ Auth::user()->avatar 
                        ? asset('storage/avatars/' . Auth::user()->avatar) 
                        : asset('images/avatar.png') }}" alt="Avatar" class="profile-pic me-3">
                <!-- Notification bell (optional) -->
                <button class="btn btn-light position-relative d-none d-md-inline-flex" style="margin-right: 8px;">
                    <i class="fa-regular fa-bell"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:.7em;">2</span>
                </button>
                <!-- Settings Dropdown -->
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
