<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{asset('assets/img/favicon.png')}}">
    <title>{{ config('app.name') }} | Log in</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('/assets/adminlte/bower_components/font-awesome/css/font-awesome.min.css')}}">
    <!-- Boxicons icons -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <style>
        *, *::before, *::after { box-sizing: border-box; }

        :root {
            --primary: #0052CC;
            --primary-dark: #003D99;
            --primary-light: #e8f0fe;
            --accent: #FF8C00;
            --accent-dark: #E07A00;
            --text-dark: #1a1f36;
            --text-muted: #6b7280;
            --border: #e5e7eb;
            --bg: #f0f4ff;
            --white: #ffffff;
            --card-shadow: 0 20px 60px rgba(0, 82, 204, 0.12);
            --navbar-h: 72px;
        }

        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text-dark);
        }

        /* ── NAVBAR ── */
        .site-navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--navbar-h);
            background: var(--white);
            border-bottom: 1px solid var(--border);
            box-shadow: 0 1px 8px rgba(0,0,0,0.06);
            display: flex;
            align-items: center;
            z-index: 1000;
        }

        .site-navbar .inner {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .nav-logo {
            display: flex;
            align-items: center;
            text-decoration: none;
            flex-shrink: 0;
        }

        /* KEY FIX: logo constrained by height only, width auto */
        .nav-logo img {
            height: 170px;
            width: auto;
            max-width: 200px;
            object-fit: contain;
            display: block;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 8px;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .nav-links a {
            display: block;
            padding: 8px 16px;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-dark);
            text-decoration: none;
            border-radius: 8px;
            transition: background 0.2s, color 0.2s;
        }

        .nav-links a:hover {
            background: var(--primary-light);
            color: var(--primary);
        }

        .nav-toggle {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px;
            color: var(--text-dark);
            font-size: 1.5rem;
        }

        /* ── MAIN LAYOUT ── */
        .page-body {
            min-height: 100vh;
            padding-top: var(--navbar-h);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 48px;
            width: 100%;
            max-width: 980px;
            padding: 48px 24px;
            align-items: center;
        }

        /* ── LEFT ILLUSTRATION ── */
        .left-panel {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 24px;
            animation: fadeUp 0.7s ease both;
        }

        .left-panel .tagline {
            text-align: center;
        }

        .left-panel .tagline h2 {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 10px;
            line-height: 1.25;
        }

        .left-panel .tagline p {
            font-size: 0.95rem;
            color: var(--text-muted);
            line-height: 1.6;
        }

        .illustration-wrap img {
            width: 100%;
            max-width: 420px;
            height: auto;
            border-radius: 16px;
            filter: drop-shadow(0 12px 32px rgba(0,82,204,0.1));
        }

        /* ── LOGIN CARD ── */
        .login-card {
            background: var(--white);
            border-radius: 20px;
            padding: 44px 40px;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(0,82,204,0.08);
            animation: fadeUp 0.7s 0.15s ease both;
        }

        .card-logo {
            display: block;
            margin: 0 auto 12px;
            height: 150px;
            width: auto;
            max-width: 260px;
            object-fit: contain;
        }

        .card-heading {
            text-align: center;
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--primary);
            margin-top: 0;
            margin-bottom: 28px;
        }

        /* ── FORM ELEMENTS ── */
        .form-label {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 6px;
            display: block;
        }

        .form-control {
            width: 100%;
            padding: 11px 14px;
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
        }

        .input-group {
            display: flex;
            align-items: stretch;
        }

        .input-group .form-control {
            border-radius: 10px 0 0 10px;
            border-right: none;
            flex: 1;
        }

        .pw-toggle {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 14px;
            border: 1.5px solid var(--border);
            border-left: none;
            border-radius: 0 10px 10px 0;
            background: #fafafa;
            cursor: pointer;
            color: var(--text-muted);
            font-size: 1.1rem;
            transition: background 0.2s, color 0.2s;
        }

        .pw-toggle:hover {
            background: var(--primary-light);
            color: var(--primary);
        }

        .field-error {
            font-size: 0.8rem;
            color: #dc3545;
            margin-top: 4px;
        }

        /* ── BUTTON — blue theme matching logo ── */
        .btn-signin {
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
            margin-top: 24px;
            transition: background 0.25s, transform 0.2s, box-shadow 0.25s;
            box-shadow: 0 4px 16px rgba(0,82,204,0.25);
        }

        .btn-signin:hover {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 82, 204, 0.35);
        }

        .btn-signin:active {
            transform: translateY(0);
        }

        /* ── LINKS ── */
        .card-links {
            margin-top: 16px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            text-align: center;
        }

        .card-links a {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--primary);
            text-decoration: none;
            transition: color 0.2s;
        }

        .card-links a:hover {
            color: var(--accent);
            text-decoration: underline;
        }

        /* divider between links */
        .card-links .divider {
            width: 40px;
            height: 1px;
            background: var(--border);
            margin: 0 auto;
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

        /* ── ANIMATIONS ── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            .login-grid {
                grid-template-columns: 1fr;
                gap: 0;
                padding: 24px 16px 40px;
                align-items: start;
            }

            .left-panel {
                display: none;
            }

            .login-card {
                padding: 32px 24px;
                border-radius: 16px;
            }

            .card-logo {
                height: 150px;
            }

            .card-heading {
                font-size: 1.15rem;
            }

            .nav-links { display: none; }
            .nav-links.open {
                display: flex;
                flex-direction: column;
                position: absolute;
                top: var(--navbar-h);
                left: 0; right: 0;
                background: var(--white);
                border-bottom: 1px solid var(--border);
                padding: 8px 16px 16px;
                gap: 4px;
                animation: fadeUp 0.2s ease;
            }
            .nav-toggle { display: block; }
        }

        @media (max-width: 480px) {
            .login-card { padding: 28px 18px; }
        }
    </style>
</head>

<body>

<!-- ── NAVBAR ── -->
<nav class="site-navbar">
    <div class="inner">
        <a class="nav-logo" href="#">
            <img src="{{ url('/application/'. getAppDetailsGeneral()->logo) }}" alt="{{ config('app.name') }} logo">
        </a>

        <button class="nav-toggle" id="navToggle" aria-label="Toggle menu">
            <i class='bx bx-menu'></i>
        </button>

        <ul class="nav-links" id="navLinks">
            <li><a href="https://RemitSystem.mantraideas.com.np/">Home</a></li>
            <li><a href="https://RemitSystem.mantraideas.com.np/contact/">Contact us</a></li>
        </ul>
    </div>
</nav>

<!-- ── PAGE BODY ── -->
<div class="page-body">
    <div class="login-grid">

        <!-- Left: illustration -->
        <div class="left-panel">
            <div class="illustration-wrap">
                <img src="{{ asset('assets/images/transfer.jpg') }}" alt="Money Transfer Illustration">
            </div>
            <div class="tagline">
                <h2>Fast &amp; Secure<br>Money Transfers</h2>
                <p>Send money anywhere, anytime with<br>confidence and zero hassle.</p>
            </div>
        </div>

        <!-- Right: login card -->
        <div>
            <div class="login-card">
                <img
                    class="card-logo"
                    src="{{ url('/application/'. getAppDetailsGeneral()->logo) }}"
                    alt="{{ config('app.name') }} logo"
                >
                <h1 class="card-heading">Welcome to {{ getAppDetailsForWeb()->name }}</h1>

                @if(session('account_deleted'))
                    <div class="alert-success" role="alert">
                        Your account has been deleted successfully.
                    </div>
                @endif

                <form method="post" action="{{ route('login') }}">
                    {!! csrf_field() !!}

                    <div style="margin-bottom: 18px;">
                        <label class="form-label" for="InputEmail1">
                            Email Address <span style="color:#dc3545;">*</span>
                        </label>
                        <input
                            type="email"
                            class="form-control"
                            name="email"
                            id="InputEmail1"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            placeholder="you@example.com"
                        >
                        @if ($errors->has('email'))
                            <div class="field-error">{{ $errors->first('email') }}</div>
                        @endif
                    </div>

                    <div style="margin-bottom: 4px;">
                        <label class="form-label" for="InputPassword1">
                            Password <span style="color:#dc3545;">*</span>
                        </label>
                        <div class="input-group">
                            <input
                                type="password"
                                class="form-control"
                                name="password"
                                id="InputPassword1"
                                required
                                placeholder="••••••••"
                            >
                            <button type="button" class="pw-toggle" id="togglePassword" aria-label="Toggle password">
                                <i class='bx bxs-low-vision' id="pwIcon"></i>
                            </button>
                        </div>
                        @if ($errors->has('password'))
                            <div class="field-error">{{ $errors->first('password') }}</div>
                        @endif
                    </div>

                    <button type="submit" class="btn-signin">Sign In</button>
                </form>

                <div class="card-links">
                    <a href="{{ url('register') }}">Create a {{ getAppDetailsForWeb()->name }} Account</a>
                    <div class="divider"></div>
                    <a href="{{ route('email.request') }}">Forgot Password?</a>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>

<script>
    // Password toggle
    document.getElementById('togglePassword').addEventListener('click', function () {
        const field = document.getElementById('InputPassword1');
        const icon  = document.getElementById('pwIcon');
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.replace('bxs-low-vision', 'bxs-show');
        } else {
            field.type = 'password';
            icon.classList.replace('bxs-show', 'bxs-low-vision');
        }
    });

    // Mobile nav toggle
    document.getElementById('navToggle').addEventListener('click', function () {
        const links = document.getElementById('navLinks');
        const icon  = this.querySelector('i');
        links.classList.toggle('open');
        icon.classList.toggle('bx-menu');
        icon.classList.toggle('bx-x');
    });
</script>

</body>
</html>
