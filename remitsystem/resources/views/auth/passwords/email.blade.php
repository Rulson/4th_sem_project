@extends('layouts.min')
@section('title','Reset Password')
@section('content')
    <?php
    $color = '';
    if(str_contains(request()->getHttpHost() ,'nepalpaisa')){
        $color = 'nepal-paisa';
    } elseif (str_contains(request()->getHttpHost() , 'dollarrupiya')){
        $color = 'dollar-rupiya';
    } else {
        $color = 'cash-nepal';
    }
    ?>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />

    <style>
        *, *::before, *::after { box-sizing: border-box; }

        :root {
            --primary: #0052CC;
            --primary-dark: #003D99;
            --primary-light: #e8f0fe;
            --accent: #FF8C00;
            --text-dark: #1a1f36;
            --text-muted: #6b7280;
            --border: #e5e7eb;
            --bg: #f0f4ff;
            --white: #ffffff;
            --card-shadow: 0 20px 60px rgba(0, 82, 204, 0.12);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 16px;
            margin: 0;
        }

        /* ── CARD ── */
        .reset-card {
            background: var(--white);
            border-radius: 20px;
            padding: 44px 40px;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(0,82,204,0.08);
            width: 100%;
            max-width: 460px;
            animation: fadeUp 0.6s ease both;
        }

        /* ── LOGO ── */
        .card-logo {
            display: block;
            margin: 0 auto 20px;
            height: 150px;
            width: auto;
            max-width: 260px;
            object-fit: contain;
        }

        /* ── ICON BADGE ── */
        .icon-badge {
            width: 60px;
            height: 60px;
            background: var(--primary-light);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .icon-badge i {
            font-size: 1.8rem;
            color: var(--primary);
        }

        /* ── HEADINGS ── */
        .card-heading {
            text-align: center;
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--primary);
            margin: 0 0 8px;
        }

        .card-subheading {
            text-align: center;
            font-size: 0.9rem;
            color: var(--text-muted);
            line-height: 1.6;
            margin: 0 0 28px;
        }

        /* ── ALERT ── */
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 0.875rem;
            margin-bottom: 20px;
            border: 1px solid #a7f3d0;
        }

        /* ── FORM ── */
        .form-label {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 6px;
            display: block;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 1.1rem;
            pointer-events: none;
        }

        .form-control {
            width: 100%;
            padding: 11px 14px 11px 40px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-size: 0.9rem;
            font-family: inherit;
            color: var(--text-dark);
            background: var(--white);
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 82, 204, 0.1);
            background: #f8f9ff;
        }

        .field-error {
            font-size: 0.8rem;
            color: #dc3545;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* ── BUTTON ── */
        .btn-reset {
            display: block;
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, var(--primary) 0%, #1a6edd 100%);
            color: var(--white);
            border: none;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            letter-spacing: 0.3px;
            margin-top: 20px;
            transition: background 0.25s, transform 0.2s, box-shadow 0.25s;
            box-shadow: 0 4px 16px rgba(0,82,204,0.25);
        }

        .btn-reset:hover {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 82, 204, 0.35);
        }

        .btn-reset:active { transform: translateY(0); }

        /* ── BACK LINK ── */
        .back-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-top: 20px;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--primary);
            text-decoration: none;
            transition: color 0.2s;
        }

        .back-link:hover { color: var(--accent); }

        /* ── FOOTER ── */
        .card-footer-text {
            text-align: center;
            font-size: 0.78rem;
            color: var(--text-muted);
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
        }

        /* ── ANIMATION ── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 480px) {
            .reset-card { padding: 32px 22px; }
            .card-logo  { height: 150px; }
            .card-heading { font-size: 1.2rem; }
        }
    </style>

    <div class="reset-card">

        {{-- Logo --}}
        <img
            class="card-logo"
            src="{{ url('/application/'. getAppDetailsGeneral()->logo) }}"
            alt="{{ getAppDetailsGeneral()->name }} logo"
        >

        <h1 class="card-heading">Forgot Password?</h1>
        <p class="card-subheading">
            Enter the email address associated with your account and we'll send you a link to reset your password.
        </p>

        @if (session('status'))
            <div class="alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div style="margin-bottom: 4px;">
                <label class="form-label" for="email">
                    Email Address <span style="color:#dc3545;">*</span>
                </label>
                <div class="input-wrap">
                    <i class='bx bx-envelope'></i>
                    <input
                        type="email"
                        class="form-control"
                        name="email"
                        id="email"
                        placeholder="you@example.com"
                        value="{{ old('email') }}"
                        required
                        autofocus
                    >
                </div>
                @if($errors->has('email'))
                    <div class="field-error">
                        <i class='bx bx-error-circle'></i>
                        {{ $errors->first('email') }}
                    </div>
                @endif
            </div>

            <button type="submit" class="btn-reset">
                Send Reset Link
            </button>
        </form>

        <a href="{{ route('login') }}" class="back-link">
            <i class='bx bx-arrow-back'></i> Back to Sign In
        </a>

        <div class="card-footer-text">
            &copy; {{ date('Y') }} {{ getAppDetailsGeneral()->name }}. All rights reserved.
        </div>

    </div>

@endsection
