<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - CMCMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
</head>
<body class="auth-page">
    <div class="auth-wrapper">
        <section class="auth-brand d-none d-lg-flex">
            <div class="d-flex align-items-center gap-2">
                <span class="sidebar-logo"><i class="bi bi-heart-pulse-fill"></i></span>
                <span class="fw-bold fs-5">CMCMS</span>
            </div>

            <div>
                <h1 class="auth-brand-title">Continuous care for chronic &amp; maternal patients.</h1>
                <p class="auth-brand-text">Manage packages, bookings, consultations and patient health records in one place.</p>
                <ul class="auth-features">
                    <li><i class="bi bi-check2-circle"></i>Track blood pressure, sugar and maternal vitals</li>
                    <li><i class="bi bi-check2-circle"></i>Coordinate agents, doctors and patients</li>
                    <li><i class="bi bi-check2-circle"></i>Real-time reports and revenue insights</li>
                </ul>
            </div>

            <div class="small opacity-75">&copy; {{ date('Y') }} Chronic &amp; Maternal Care Management System</div>
        </section>

        <section class="auth-form-panel">
            <div class="auth-form">
                <div class="d-flex d-lg-none align-items-center gap-2 mb-4">
                    <span class="sidebar-logo"><i class="bi bi-heart-pulse-fill"></i></span>
                    <span class="fw-bold fs-5">CMCMS</span>
                </div>

                <h2 class="auth-title">Welcome back</h2>
                <p class="text-muted mb-4">Sign in with your phone number to continue.</p>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="/login">
                    @csrf

                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone number</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                            <input type="text"
                                   class="form-control"
                                   id="phone"
                                   name="phone"
                                   value="{{ old('phone') }}"
                                   placeholder="e.g., 612345678"
                                   required
                                   autofocus>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                        </div>
                    </div>

                    <div class="mb-4 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100">
                        Sign in <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </form>
            </div>
        </section>
    </div>
</body>
</html>
