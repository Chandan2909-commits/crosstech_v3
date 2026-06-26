@extends('admin.layouts.master')

@push('style')
<style>
/* ── SPLIT LOGIN LAYOUT ── */
.cf-login-page {
    display: flex;
    min-height: 100vh;
    width: 100%;
    background: #ffffff;
    overflow: hidden;
}

/* LEFT PANEL — illustration */
.cf-login-left {
    flex: 0 0 55vw;
    width: 55vw;
    max-width: 55vw;
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, #c9d6ff 0%, #e2e2e2 50%, #ddd6fe 100%);
}

.cf-login-left .cf-left-bg {
    position: absolute;
    inset: 0;
    background-image: url('{{ asset('assets/admin/images/login_screen.png') }}');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}

/* Soft vignette overlay on the left */
.cf-login-left .cf-left-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(
        to right,
        rgba(230, 220, 255, 0.10) 0%,
        rgba(255, 255, 255, 0.35) 100%
    );
}

/* Brand tag bottom-left */
.cf-login-left .cf-brand-tag {
    position: absolute;
    bottom: 32px;
    left: 36px;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: rgba(255,255,255,0.70);
}

/* RIGHT PANEL — form */
.cf-login-right {
    flex: 0 0 420px;
    width: 420px;
    min-height: 100vh;
    background: #ffffff;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: flex-start;
    padding: 60px 52px;
    position: relative;
    z-index: 1;
}

/* Subtle right-panel shadow from the left */
.cf-login-right::before {
    content: '';
    position: absolute;
    top: 0;
    left: -1px;
    width: 1px;
    height: 100%;
    background: rgba(200, 190, 240, 0.25);
}

/* Logo / Site name */
.cf-login-right .cf-logo {
    margin-bottom: 10px;
}

.cf-login-right .cf-logo img {
    height: 40px;
    width: auto;
    object-fit: contain;
}

.cf-login-right .cf-site-name {
    font-size: 26px;
    font-weight: 600;
    letter-spacing: 3px;
    color: #1a1035;
    text-transform: uppercase;
    margin-bottom: 8px;
    line-height: 1.2;
}

.cf-login-right .cf-subtitle {
    font-size: 13px;
    color: #9991b0;
    margin-bottom: 36px;
    line-height: 1.5;
}

/* Form elements */
.cf-login-right .cf-form-group {
    width: 100%;
    margin-bottom: 14px;
}

.cf-login-right label {
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    color: #7b72a0;
    margin-bottom: 6px;
    display: block;
}

.cf-login-right .cf-input {
    width: 100%;
    height: 44px;
    padding: 0 16px;
    background: #f5f4f9;
    border: 1px solid #ece9f5;
    border-radius: 8px;
    font-size: 14px;
    color: #1a1035;
    outline: none;
    transition: all 0.2s ease;
    box-shadow: none;
}

.cf-login-right .cf-input:focus {
    background: #ffffff;
    border-color: #b39ddb;
    box-shadow: 0 0 0 3px rgba(179, 157, 219, 0.18);
}

.cf-login-right .cf-input::placeholder {
    color: #c5bedd;
}

/* Forgot password row */
.cf-forgot-row {
    display: flex;
    justify-content: flex-end;
    margin-top: -6px;
    margin-bottom: 22px;
    width: 100%;
}

.cf-forgot-row a {
    font-size: 12px;
    color: #9b87c7;
    text-decoration: none;
    font-weight: 500;
}

.cf-forgot-row a:hover {
    color: #6d4dff;
    text-decoration: underline;
}

/* Submit button */
.cf-login-right .cf-submit-btn {
    width: 100%;
    height: 46px;
    background: #1a1035;
    color: #ffffff;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    letter-spacing: 1px;
    cursor: pointer;
    transition: background 0.2s ease, transform 0.15s ease;
    margin-top: 4px;
}

.cf-login-right .cf-submit-btn:hover {
    background: #2d1f6e;
    transform: translateY(-1px);
}

.cf-login-right .cf-submit-btn:active {
    transform: translateY(0);
}

/* Captcha wrapper */
.cf-login-right .cf-captcha {
    width: 100%;
    margin-bottom: 16px;
}

/* Responsive */
@media (max-width: 900px) {
    .cf-login-left {
        display: none;
    }
    .cf-login-right {
        flex: 1;
        width: 100%;
        padding: 40px 28px;
        align-items: center;
    }
    .cf-login-right > * {
        width: 100%;
        max-width: 380px;
    }
}

@media (max-width: 480px) {
    .cf-login-right {
        padding: 32px 20px;
    }
}
</style>
@endpush

@section('content')
<div class="cf-login-page">

    {{-- LEFT — illustration panel --}}
    <div class="cf-login-left">
        <div class="cf-left-bg"></div>
        <div class="cf-left-overlay"></div>
        <span class="cf-brand-tag">{{ gs('site_name') }}</span>
    </div>

    {{-- RIGHT — form panel --}}
    <div class="cf-login-right">

        {{-- Logo --}}
        <div class="cf-logo">
            <img src="{{ siteLogo() }}" alt="{{ gs('site_name') }}">
        </div>

        {{-- Site name --}}
        <div class="cf-site-name">{{ gs('site_name') }}</div>

        {{-- Subtitle --}}
        <p class="cf-subtitle">@lang('Sign in to your admin dashboard')</p>

        {{-- Form — action/method/CSRF unchanged --}}
        <form action="{{ route('admin.login') }}" method="POST"
              class="cmn-form verify-gcaptcha"
              style="width:100%">
            @csrf

            <div class="cf-form-group">
                <label>@lang('Username')</label>
                <input type="text"
                       class="cf-input"
                       name="username"
                       value="{{ old('username') }}"
                       placeholder="@lang('Enter your username')"
                       required>
            </div>

            <div class="cf-form-group">
                <label>@lang('Password')</label>
                <input type="password"
                       class="cf-input"
                       name="password"
                       placeholder="@lang('Enter your password')"
                       required>
            </div>

            <div class="cf-forgot-row">
                <a href="{{ route('admin.password.reset') }}">@lang('Forgot Password?')</a>
            </div>

            <div class="cf-captcha">
                <x-captcha />
            </div>

            <button type="submit" class="cf-submit-btn">@lang('LOG IN')</button>

        </form>

    </div>
</div>
@endsection
