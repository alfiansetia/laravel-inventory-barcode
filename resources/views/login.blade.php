<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Sistem Informasi Gudang">
    <link rel="icon" href="{{ asset('kai/assets/img/kaiadmin/favicon.ico') }}" type="image/x-icon" />
    <title>Login - {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('kai/assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" />
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <style>
        body {
            font-family: Nunito, sans-serif;
            font-size: 15px;
            background-color: #6861ce;
            color: #29343d;
        }

        h1,
        .h1,
        h2,
        .h2,
        h3,
        .h3,
        h4,
        .h4,
        h5,
        .h5,
        h6,
        .h6 {
            color: #29343d;
        }

        .page-wrapper {
            position: relative;
        }

        .card {
            --bs-card-spacer-y: 1rem;
            --bs-card-spacer-x: 1.75rem;
            border-radius: 12px !important;
            box-shadow: 2px 6px 15px 0 rgba(69, 65, 78, .1);
            border: 0;
        }

        .auth-card {
            max-width: 500px;
            width: 100%;
        }

        .card .card-body {
            padding: 1.75rem 2rem;
            color: #29343d;
        }

        .login-title {
            font-size: 21px;
        }

        .form-control {
            color: #29343d !important;
            border-color: #e0e6eb;
            border-radius: 8px;
        }

        .form-control:focus {
            border-color: #b1adff;
            box-shadow: none;
        }

        .form-floating>.form-control {
            padding: 1rem 1.1rem;
        }

        .form-floating>.form-control:focus {
            box-shadow: none;
        }

        .form-floating>label {
            color: #29343d;
        }

        .form-floating>label>i {
            font-size: 18px;
            margin-left: .25rem !important;
            margin-right: .5rem !important;
            vertical-align: middle;
        }

        .form-floating>label>span {
            vertical-align: middle;
        }

        .btn {
            padding: .75rem 1rem;
            border-radius: 10px;
        }

        .btn-secondary {
            background: #6861ce !important;
            border-color: #6861ce !important;
        }

        .btn-secondary:hover,
        .btn-secondary:focus,
        .btn-secondary:disabled {
            color: #ffffff !important;
            background: #5c55bf !important;
            border-color: #5c55bf !important;
        }

        .alert {
            padding: 16px 18px;
            border-radius: 12px;
            color: #29343d !important;
            box-shadow: none;
        }

        .alert-title {
            margin-bottom: 40px;
        }

        .alert-success {
            color: #36c76c !important;
            background-color: #ebfaf0 !important;
            border: 1px solid #cbf4da !important;
            border-left: none;
        }

        .alert-danger {
            color: #ff6692 !important;
            background-color: #ffccdb !important;
            border: 1px solid #ffc2d3 !important;
            border-left: none;
        }

        .text-brand {
            color: #ee5050 !important;
        }

        .border-start {
            border-left: 1px solid #e0e6eb !important;
        }
    </style>

</head>

<body>
    <div class="page-wrapper">
        <div class="position-relative overflow-hidden min-vh-100 d-flex align-items-center justify-content-center">
            <div class="d-flex align-items-center justify-content-center w-100">
                <div class="card auth-card mx-3 mb-0">
                    <div class="card-body">
                        <a href="{{ route('login') }}" class="text-nowrap text-center d-block py-3 w-100 mb-2">
                            <img src="{{ asset('kai/assets/img/kaiadmin/favicon.png') }}" alt="Logo" width="90px">
                        </a>
                        <h5 class="login-title text-center lh-base px-4 mb-5">{{ config('app.name') }}</h5>
                        <form action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="form-floating mb-3">
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror" placeholder="Email"
                                    value="{{ old('email') }}" autocomplete="off" autofocus required>
                                <label>
                                    <i class="ti ti-user"></i>
                                    <span class="border-start ps-3">Email</span>
                                </label>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-floating mb-3">
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror" placeholder="Password"
                                    autocomplete="off">
                                <label>
                                    <i class="ti ti-lock"></i>
                                    <span class="border-start ps-3">Password</span>
                                </label> @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="remember" class="form-check-input me-2" tabindex="3"
                                        id="remember-me" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="remember-me">Remember Me</label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-secondary w-100 mt-3 mb-5">LOGIN</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('kai/assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('kai/assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('kai/assets/js/core/bootstrap.min.js') }}"></script>
</body>

</html>
