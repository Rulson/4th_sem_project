<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{asset('assets/img/favicon.png')}}">
    <title>{{getAppDetailsForWeb()->name}} | Registration</title>

    <link rel="stylesheet" href="{{asset('/assets/adminlte/bower_components/font-awesome/css/font-awesome.min.css')}}">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

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
            top: 0; left: 0; right: 0;
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

        .nav-logo img {
            height: 150px;
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

        /* ── PAGE BODY ── */
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

        /* ── LEFT PANEL ── */
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

        /* ── REGISTER CARD ── */
        .login-card {
            background: var(--white);
            border-radius: 20px;
            padding: 40px 40px;
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
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--primary);
            margin: 0 0 24px;
        }

        /* ── FORM ── */
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
            background: #f8f9ff;
        }

        /* password input + toggle wrapper */
        .pw-wrap {
            position: relative;
            display: flex;
            align-items: stretch;
        }

        .pw-wrap .form-control {
            border-radius: 10px;
            padding-right: 44px;
        }

        .pw-toggle {
            position: absolute;
            right: 0;
            top: 0;
            height: 100%;
            padding: 0 13px;
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-muted);
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            transition: color 0.2s;
        }

        .pw-toggle:hover  { color: var(--primary); }
        .pw-toggle:focus  { outline: none; box-shadow: none; }

        .field-error {
            font-size: 0.8rem;
            color: #dc3545;
            margin-top: 4px;
        }

        /* ── AGENT BADGE ── */
        .agent-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            background: var(--primary-light);
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 16px;
        }

        /* ── CHECKBOX ── */
        .terms-check {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin: 16px 0;
        }

        .terms-check input[type="checkbox"] {
            width: 16px;
            height: 16px;
            margin-top: 2px;
            accent-color: var(--primary);
            flex-shrink: 0;
            cursor: pointer;
        }

        .terms-check label {
            font-size: 0.83rem;
            color: var(--text-muted);
            line-height: 1.5;
            cursor: pointer;
        }

        .terms-check label a {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
        }

        .terms-check label a:hover {
            color: var(--accent);
            text-decoration: underline;
        }

        /* ── BUTTON ── */
        .btn-submit {
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
            transition: background 0.25s, transform 0.2s, box-shadow 0.25s;
            box-shadow: 0 4px 16px rgba(0,82,204,0.25);
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 82, 204, 0.35);
        }

        .btn-submit:active { transform: translateY(0); }

        /* ── BOTTOM LINK ── */
        .card-links {
            text-align: center;
            margin-top: 16px;
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        .card-links a {
            font-weight: 700;
            color: var(--primary);
            text-decoration: none;
            transition: color 0.2s;
        }

        .card-links a:hover { color: var(--accent); }

        /* ── ANIMATION ── */
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
            }

            .left-panel { display: none; }

            .login-card {
                padding: 32px 24px;
                border-radius: 16px;
            }

            .card-logo { height: 150px; }
            .card-heading { font-size: 1.1rem; }

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
                <h2>Join Thousands of<br>Happy Senders</h2>
                <p>Fast, secure, and reliable transfers<br>across the globe.</p>
            </div>
        </div>

        <!-- Right: register card -->
        <div>
            <div class="login-card">

                <img
                    class="card-logo"
                    src="{{ url('/application/'. getAppDetailsGeneral()->logo) }}"
                    alt="{{ getAppDetailsForWeb()->name }} logo"
                >

                <h1 class="card-heading">Join {{ getAppDetailsForWeb()->name }} Today!</h1>

                <form method="post" action="{{ route('register.handle-initial-registration') }}">
                    @csrf

                    {{-- Email --}}
                    <div style="margin-bottom: 16px;">
                        <label class="form-label" for="InputEmail1">
                            Email Address <span style="color:#dc3545;">*</span>
                        </label>
                        <input
                            type="email"
                            class="form-control"
                            name="email"
                            id="InputEmail1"
                            value="{{ old('email') }}"
                            placeholder="you@example.com"
                            required
                            autofocus
                        >
                        @if ($errors->has('email'))
                            <div class="field-error">{{ $errors->first('email') }}</div>
                        @endif
                    </div>

                    {{-- Password --}}
                    <div style="margin-bottom: 16px;">
                        <label class="form-label" for="InputPassword1">
                            Password <span style="color:#dc3545;">*</span>
                        </label>
                        <div class="pw-wrap">
                            <input
                                type="password"
                                class="form-control"
                                name="password"
                                id="InputPassword1"
                                required
                            >
                            <button type="button" class="pw-toggle" data-target="InputPassword1" aria-label="Toggle password">
                                <i class='bx bxs-low-vision'></i>
                            </button>
                        </div>
                        @if ($errors->has('password'))
                            <div class="field-error">{{ $errors->first('password') }}</div>
                        @endif
                    </div>

                    {{-- Confirm Password --}}
                    <div style="margin-bottom: 16px;">
                        <label class="form-label" for="ConfirmPassword2">
                            Confirm Password <span style="color:#dc3545;">*</span>
                        </label>
                        <div class="pw-wrap">
                            <input
                                type="password"
                                class="form-control"
                                name="password_confirmation"
                                id="ConfirmPassword2"
                                required
                            >
                            <button type="button" class="pw-toggle" data-target="ConfirmPassword2" aria-label="Toggle confirm password">
                                <i class='bx bxs-low-vision'></i>
                            </button>
                        </div>
                        @if ($errors->has('password_confirmation'))
                            <div class="field-error">{{ $errors->first('password_confirmation') }}</div>
                        @endif
                    </div>

                    {{-- Agent --}}
                    @php $agentId = _getAgentIdFromShareableLink(request('agent')); @endphp
                    @if($agentId)
                        <div class="agent-badge">
                            <i class='bx bx-user-check'></i>
                            Referred by: <strong>{{ getAgentName($agentId) }}</strong>
                        </div>
                    @endif

                    {{-- Terms --}}
                    <div class="terms-check">
                        <input type="checkbox" id="Check1" required>
                        <label for="Check1">
                            By creating an account, I agree to {{ getAppDetailsForWeb()->name }}'s
                            <a href="{{ getAppDetailsGeneral()->terms_and_conditions }}" target="_blank">Terms &amp; Conditions</a>
                        </label>
                    </div>

                    <button type="submit" class="btn-submit">Next</button>
                </form>

                <div class="card-links">
                    Already have an account? <a href="{{ route('login') }}">Log in</a>
                </div>

            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>

<script>
    // Password toggles
    document.querySelectorAll('.pw-toggle').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var field = document.getElementById(this.getAttribute('data-target'));
            var icon  = this.querySelector('i');
            if (!field) return;
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('bxs-low-vision');
                icon.classList.add('bxs-show');
            } else {
                field.type = 'password';
                icon.classList.remove('bxs-show');
                icon.classList.add('bxs-low-vision');
            }
        });
    });

    // Mobile nav toggle
    document.getElementById('navToggle').addEventListener('click', function() {
        var links = document.getElementById('navLinks');
        var icon  = this.querySelector('i');
        links.classList.toggle('open');
        icon.classList.toggle('bx-menu');
        icon.classList.toggle('bx-x');
    });
</script>

</body>
</html>
