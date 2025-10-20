<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TalkEase Student Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS & FontAwesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body { background: linear-gradient(110deg, #eaf5ff 0%, #f5fafc 100%); }
        .sidebar-student {
            min-height: 100vh;
            background: rgba(255,255,255,0.92);
            border-right: 1.5px solid #e6e8ee;
            box-shadow: 2px 0 18px 0 rgba(0,30,80,0.06);
            backdrop-filter: blur(9px);
            transition: all 0.3s;
        }
        .sidebar-student .nav-link {
            color: #1d1d32;
            font-weight: 500;
            border-radius: 1rem;
            margin-bottom: .3rem;
            font-size: 1.05rem;
            transition: background 0.15s, color 0.15s, box-shadow 0.15s;
            padding: .7rem 1.1rem .7rem 1.3rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }
        .sidebar-student .nav-link i {
            font-size: 1.16em;
        }
        .sidebar-student .nav-link.active,
        .sidebar-student .nav-link:hover,
        .sidebar-student .dropdown-menu .dropdown-item.active,
        .sidebar-student .dropdown-menu .dropdown-item:hover {
            background: linear-gradient(90deg, #e8f0ff 20%, #eafaf2 100%);
            color: #2563eb !important;
            box-shadow: 0 2px 14px 0 rgba(56,134,240,0.07);
        }
        .sidebar-student .dropdown-menu {
            background: rgba(255,255,255,0.97);
            border-radius: 1rem;
            border: 1px solid #e6e8ee;
            box-shadow: 0 2px 16px 0 rgba(54,100,190,0.12);
            margin-left: 10px;
            padding: .7rem .4rem .7rem .2rem;
            min-width: 210px;
        }
        .sidebar-brand {
            font-weight: bold;
            font-size: 1.37rem;
            display: flex;
            align-items: center;
            gap: 1.1rem;
            letter-spacing: 0.5px;
        }
        .sidebar-brand i {
            background: linear-gradient(135deg, #2563eb 0%, #3ee57b 80%);
            color: #fff !important;
            border-radius: 10px;
            padding: 6px 10px 6px 10px;
            font-size: 1.2rem;
            box-shadow: 0 2px 8px 0 rgba(44,146,252,0.07);
        }
        .profile-pic {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 50%;
            border: 2.5px solid #d6e7fb;
            box-shadow: 0 2px 8px 0 rgba(60,170,250,0.07);
        }
        .student-header {
            background: rgba(255,255,255,0.93);
            border-bottom: 1.5px solid #e6e8ee;
            padding: 1.3rem 2.4rem 1.3rem 1.6rem;
            display: flex;
            align-items: center;
            border-top-right-radius: 1.2rem;
            box-shadow: 0 4px 24px 0 rgba(56,134,240,0.06);
        }
        .student-header .dropdown-menu {
            margin-top: .5rem;
            min-width: 170px;
            border-radius: .9rem;
        }
        .student-header .btn {
            border-radius: 50%;
            padding: .5rem .65rem;
        }
        .custom-scroll {
            max-height: 96vh;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #bbd1fa #f6f9fc;
        }
        @media (max-width: 900px) {
            .sidebar-student { width: 100px !important; padding: .4rem !important; }
            .sidebar-student .nav-link span,
            .sidebar-brand span { display: none !important; }
            .sidebar-brand i { padding: 6px; }
            .sidebar-student .dropdown-menu { left: 40px !important; min-width: 175px;}
        }
        @media (max-width: 700px) {
            .sidebar-student { display: none; }
        }
    </style>
    @yield('head')
</head>
<body>
    <div class="d-flex">
                    <!-- Sidebar -->
                    <aside class="sidebar-student p-3 custom-scroll" style="width:230px;">
                    <div class="mb-4 sidebar-brand">
                <span style="background:linear-gradient(135deg,#2563eb 0%,#3ee57b 80%);border-radius:12px;padding:9px 11px;display:inline-block;">
                    <i class="fa-solid fa-microphone fa-lg" style="color:#fff;"></i>
                </span>
                <span>TalkEase</span>
            </div>

                <!-- Dashboard -->
                <a href="{{ route('student.dashboard') }}"
                   class="nav-link {{ Request::routeIs('student.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-table-columns me-2"></i>
                    <span>Dashboard</span>
                </a>

               <!-- Exercises Dropdown (Level + Category) -->
<!-- Exercises Dropdown (Level only) -->
<div class="dropdown mb-2">
    <a class="nav-link dropdown-toggle d-flex align-items-center {{ Request::routeIs('exercises.practice') ? 'active' : '' }}"
       href="#"
       id="exercisesDropdown"
       role="button"
       data-bs-toggle="dropdown"
       aria-expanded="false">
        <i class="fa-solid fa-list-ul me-2"></i>
        <span>Exercises</span>
    </a>
    <ul class="dropdown-menu border-0 shadow" aria-labelledby="exercisesDropdown" style="min-width: 210px; border-radius:1rem;">
        <li class="px-2 pt-2 pb-1 text-muted small fw-bold">Level</li>
        @foreach(['easy', 'medium', 'hard', 'insane'] as $level)
            <li>
                <a class="dropdown-item px-3 py-2 rounded"
                   style="font-size:1.03em;"
                   href="{{ route('exercises.practice', [$level, 'word']) }}"> <!-- Always goes to 'word' category by default -->
                    <i class="fa fa-chevron-right fa-xs text-muted me-2"></i>
                    {{ ucfirst($level) }} Practice
                </a>
            </li>
        @endforeach
        <li><hr class="dropdown-divider"></li>
        <li>
            <a class="dropdown-item px-3" href="{{ route('exercises.select') }}">
                <i class="fa fa-sliders-h me-2"></i> Custom Selection
            </a>
        </li>
    </ul>
</div>

                <!-- Coach Feedback -->
                <a href="{{ route('student.dashboard') }}#coach-feedback"
                   class="nav-link {{ Request::is('student/feedback*') ? 'active' : '' }}">
                    <i class="fa-solid fa-comments me-2"></i>
                    <span>Coach Feedback</span>
                </a>

                <!-- Profile -->
                <a href="{{ route('profile.edit') }}"
                   class="nav-link {{ Request::routeIs('profile.edit') ? 'active' : '' }}">
                    <i class="fa-solid fa-user me-2"></i>
                    <span>Profile</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-fill" style="min-width:0;">
            <!-- Top Navbar/Header -->
            <header class="student-header d-flex justify-content-end align-items-center gap-2">
                <div class="me-3 text-end">
                    <div class="fw-semibold">
                        {{ trim(Auth::user()->first_name . ' ' . Auth::user()->last_name) ?: Auth::user()->email }}
                    </div>
                </div>
                <!-- Profile Picture -->
                <img 
                    src="{{ Auth::user()->avatar 
                        ? asset('storage/avatars/' . Auth::user()->avatar) 
                        : asset('images/default-avatar.png') 
                    }}" 
                    alt="Avatar" 
                    class="profile-pic me-3">
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
