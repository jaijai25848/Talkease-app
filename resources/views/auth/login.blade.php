@extends('layouts.app')

@section('content')
    {{-- Registration Success Toast --}}
    @if(session('success'))
        <div id="registration-success-toast" class="position-fixed top-0 start-50 translate-middle-x mt-4"
             style="z-index: 9999; min-width: 320px;" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="alert alert-success d-flex align-items-center shadow"
                 style="font-size:1.1em; background:linear-gradient(90deg,#6f57e7 0,#4786fc 100%); color:#fff; border:none;">
                <i class="fas fa-check-circle me-2" style="font-size:1.7em;"></i>
                <div>{{ session('success') }}</div>
            </div>
        </div>
    @endif

    {{-- Show login errors --}}
    @if ($errors->any())
        <div class="alert alert-danger mb-3 mt-3 mx-auto" style="max-width: 400px;">
            <ul class="mb-0 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="login-page" style="min-height:100vh; background:linear-gradient(135deg, #f3f4fa 0%, #f7faff 100%);">
        <div class="container d-flex justify-content-center align-items-center" style="min-height:100vh;">
            <div class="card shadow-lg border-0 rounded-4 p-0" style="width: 400px; background: #fff;">
                {{-- Logo and Heading --}}
                <div class="text-center pt-4 pb-1">
                    <div class="mx-auto mb-2" style="width:60px; height:60px; background:linear-gradient(135deg,#7352ff,#437ffb); border-radius:16px; display:flex; align-items:center; justify-content:center;">
                        <span style="font-size:2.2rem; font-weight:700; color:#fff;">T</span>
                    </div>
                    <h2 class="fw-bold mb-0" style="font-size: 2rem; color:#21235f;">TalkEase</h2>
                    <h4 class="fw-semibold mt-2 mb-0" style="font-size:1.35rem;">Welcome Back</h4>
                    <div class="text-muted mb-2" style="font-size: 1.05rem;">Sign in to continue your pronunciation journey</div>
                </div>
                {{-- Login Form --}}
                <div class="card-body pb-2 pt-1">
                    <form action="{{ route('login') }}" method="POST" autocomplete="off">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold small mb-1">Email Address</label>
                            <input type="email" id="email" name="email" class="form-control rounded-3" placeholder="Enter your email" required autofocus>
                        </div>
                        <div class="mb-1">
                            <label for="password" class="form-label fw-semibold small mb-1">Password</label>
                            <div class="input-group">
                                <input type="password" id="password" name="password" class="form-control rounded-3" placeholder="Enter your password" required>
                                <span class="input-group-text bg-white border-0" style="cursor:pointer;">
                                    <i class="fas fa-eye" id="togglePassword"></i>
                                </span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2 mt-1">
                            <div class="form-check mb-0">
                                <input class="form-check-input" type="checkbox" value="" id="remember" name="remember">
                                <label class="form-check-label small" for="remember">
                                    Remember me
                                </label>
                            </div>
                            <a href="{{ route('password.request') }}" class="small text-decoration-none" style="color:#4560db;">Forgot password?</a>
                        </div>
                        <button type="submit" class="btn w-100 fw-semibold rounded-3 mb-2" style="background: linear-gradient(90deg,#6f57e7 0,#4786fc 100%); color: #fff; font-size: 1.1em; box-shadow: 0 4px 14px 0 rgba(85,85,120,0.08);">Sign In</button>
                    </form>
                    {{-- Divider --}}
                    <div class="my-3 d-flex align-items-center">
                        <hr class="flex-grow-1 border-light m-0">
                        <span class="mx-2 text-muted small">Or continue with</span>
                        <hr class="flex-grow-1 border-light m-0">
                    </div>
                    {{-- Social Login --}}
                    <div class="d-flex gap-2 mb-2">
                        <a href="{{ route('social.redirect', 'google') }}" class="btn btn-light w-50 border rounded-3 d-flex align-items-center justify-content-center" style="font-weight:500;">
                            <img src="{{ asset('asset/images/google.png') }}" alt="Google" width="20" class="me-2"> Google
                        </a>
                        <a href="{{ route('social.redirect', 'facebook') }}" class="btn btn-light w-50 border rounded-3 d-flex align-items-center justify-content-center" style="font-weight:500;">
                            <img src="{{ asset('asset/images/fb.png') }}" alt="Facebook" width="20" class="me-2"> Facebook
                        </a>
                    </div>
                    <div class="text-center mt-1 mb-2 small">
                        Don't have an account?
                        <a href="{{ route('register') }}" class="fw-semibold" style="color:#4766dc;">Sign up</a>
                    </div>
                </div>
                {{-- Demo account note --}}
                <div class="text-center small pb-2" style="color:#577199; opacity:.86;">
                    <span style="font-size:.98em;">Demo Account: <span class="fw-semibold">Use any email and password to login</span></span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body, .login-page {
            background: linear-gradient(135deg, #f3f4fa 0%, #f7faff 100%) !important;
        }
        .form-control:focus {
            border-color: #7465e4;
            box-shadow: 0 0 0 0.1rem rgba(115, 82, 255, .14);
        }
        .btn:active, .btn:focus {
            outline: none;
            box-shadow: 0 0 0 0.08rem #6f57e7;
        }
        .btn-light {
            transition: box-shadow 0.2s;
        }
        .btn-light:hover {
            box-shadow: 0 2px 10px 0 rgba(70, 120, 240, 0.09);
            background: #f5f9ff;
        }
    </style>
@endpush

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Toggle password visibility
        const passwordInput = document.getElementById("password");
        const togglePassword = document.getElementById("togglePassword");
        togglePassword.addEventListener("click", function() {
            const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
            passwordInput.setAttribute("type", type);
            this.classList.toggle("fa-eye");
            this.classList.toggle("fa-eye-slash");
        });

        // Auto-hide registration success toast
        const toast = document.getElementById("registration-success-toast");
        if (toast) {
            setTimeout(() => {
                toast.style.display = 'none';
            }, 3500);
        }
    });
</script>
@endpush