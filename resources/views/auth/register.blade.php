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

    {{-- Show validation errors --}}
    @if ($errors->any())
        <div class="alert alert-danger mb-3 mx-auto" style="max-width: 400px;">
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
                    <h4 class="fw-semibold mt-2 mb-0" style="font-size:1.35rem;">Create Account</h4>
                    <div class="text-muted mb-2" style="font-size: 1.05rem;">Start your pronunciation improvement journey today</div>
                </div>
                {{-- Registration Form --}}
                <div class="card-body pb-2 pt-1">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="row mb-3">
                            <div class="col">
                                <label for="first_name" class="form-label small">First Name</label>
                                <input type="text" name="first_name" id="first_name" class="form-control rounded-3" placeholder="First name" value="{{ old('first_name') }}" required autofocus>
                            </div>
                            <div class="col">
                                <label for="last_name" class="form-label small">Last Name</label>
                                <input type="text" name="last_name" id="last_name" class="form-control rounded-3" placeholder="Last name" value="{{ old('last_name') }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label small">Email Address</label>
                            <input type="email" name="email" id="email" class="form-control rounded-3" placeholder="Enter your email" value="{{ old('email') }}" required>
                        </div>
                        {{-- Role Selector --}}
                        <div class="mb-3">
                            <label for="role" class="form-label small">Register As</label>
                            <select name="role" id="role" class="form-select rounded-3" required>
                                <option value="" disabled {{ old('role') ? '' : 'selected' }}>Select your role</option>
                                <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
                                <option value="coach" {{ old('role') == 'coach' ? 'selected' : '' }}>Coach</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label small">Password</label>
                            <div class="input-group">
                                <input type="password" name="password" id="password" class="form-control rounded-3" placeholder="Enter your password" required>
                                <span class="input-group-text bg-white border-0" style="cursor:pointer;">
                                    <i class="fas fa-eye" id="togglePassword"></i>
                                </span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label small">Confirm Password</label>
                            <div class="input-group">
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control rounded-3" placeholder="Confirm your password" required>
                                <span class="input-group-text bg-white border-0" style="cursor:pointer;">
                                    <i class="fas fa-eye" id="toggleConfirmPassword"></i>
                                </span>
                            </div>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="terms" required>
                            <label class="form-check-label small" for="terms">
                                I agree to the <a href="#" target="_blank">Terms of Service</a> and <a href="#" target="_blank">Privacy Policy</a>
                            </label>
                        </div>
                        <button type="submit" class="btn w-100 fw-semibold rounded-3 mb-2"
                                style="background: linear-gradient(90deg,#6f57e7 0,#4786fc 100%); color: #fff; font-size: 1.1em; box-shadow: 0 4px 14px 0 rgba(85,85,120,0.08);">
                            Create Account
                        </button>
                    </form>
                    {{-- Divider --}}
                    <div class="my-3 d-flex align-items-center">
                        <hr class="flex-grow-1 border-light m-0">
                        <span class="mx-2 text-muted small">Or continue with</span>
                        <hr class="flex-grow-1 border-light m-0">
                    </div>
                    {{-- Social Login --}}
                    <div class="d-flex gap-2 mb-2">
                        <a href="{{ route('social.redirect', ['provider' => 'google']) }}" class="btn btn-light w-50 border rounded-3 d-flex align-items-center justify-content-center" style="font-weight:500;">
                            <img src="{{ asset('asset/images/google.png') }}" alt="Google" width="20" class="me-2"> Google
                        </a>
                        <a href="{{ route('social.redirect', ['provider' => 'facebook']) }}" class="btn btn-light w-50 border rounded-3 d-flex align-items-center justify-content-center" style="font-weight:500;">
                            <img src="{{ asset('asset/images/fb.png') }}" alt="Facebook" width="20" class="me-2"> Facebook
                        </a>
                    </div>
                    <div class="text-center mt-1 mb-2 small">
                        Already have an account?
                        <a href="{{ route('login') }}" class="fw-semibold" style="color:#4766dc;">Sign in</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .form-control:focus, .form-select:focus {
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
        const confirmPasswordInput = document.getElementById("password_confirmation");
        const togglePassword = document.getElementById("togglePassword");
        const toggleConfirmPassword = document.getElementById("toggleConfirmPassword");

        togglePassword.addEventListener("click", function() {
            const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
            passwordInput.setAttribute("type", type);
            this.classList.toggle("fa-eye");
            this.classList.toggle("fa-eye-slash");
        });

        toggleConfirmPassword.addEventListener("click", function() {
            const type = confirmPasswordInput.getAttribute("type") === "password" ? "text" : "password";
            confirmPasswordInput.setAttribute("type", type);
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
