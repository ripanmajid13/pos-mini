@extends('layouts.auth')

@push('style_plugin')
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@endpush

@push('script_inline')
    <script>
        $('#form-action').on('submit', function() {
            $('#alert-error').remove();
        });
    </script>
@endpush

@section('body-class')
    login-page
@endsection

@section('content')
    <div class="login-box">
        <div class="login-logo text-lg">
            <a href="{{ url('/') }}">
                <img src="<x-layouts.icon />" alt="Laravel" width="60" class="mr-1">
                POS Mini
            </a>
        </div>

        <div class="card">
            <div class="card-body login-card-body rounded px-2 py-1">
                <p class="login-box-msg pb-3">Log in to start your application</p>

                @if (session('error'))
                    <div id="alert-error" class="alert alert-warning alert-dismissible text-sm py-0 px-1">
                        <button type="button" class="close p-0 pr-1" data-dismiss="alert" aria-hidden="true">&times;</button>
                        {{ session('error') }}
                    </div>
                @endif


                <form method="POST" action="{{ route('login') }}" id="form-action">
                    @csrf

                    <div class="input-group mb-1">
                        <input id="username" type="text" class="form-control  form-control-sm @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" placeholder="Username Or Email" required autocomplete="username" autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                        @error('username')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="input-group mb-1">
                        <input id="password" type="password" class="form-control  form-control-sm @error('password') is-invalid @enderror" name="password" placeholder="Password" required autocomplete="current-password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-10">
                            <div class="icheck-primary my-0">
                                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label for="remember">Remember Me</label>
                            </div>
                        </div>

                        <div class="col-2">
                            <button type="submit" class="btn btn-success btn-xs btn-block px-0 pt-0" style="padding-bottom: 3px;">Log In</button>
                        </div>
                    </div>
                </form>

                {{-- <div class="social-auth-links text-center mt-2 mb-3">
                    <p class="mb-1">- OR -</p>

                    <a href="#" class="btn btn-block btn-sm btn-primary">
                        <i class="fab fa-facebook mr-2"></i> Sign in using Facebook
                    </a>

                    <a href="#" class="btn btn-block btn-sm btn-danger mt-1">
                        <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
                    </a>
                </div> --}}

                <p class="mb-0 text-sm">Username : admin</p>
                <p class="mb-0 text-sm">Password : password</p>

                @if (Route::has('password.request') || Route::has('register'))
                    <hr class="my-1" />
                @endif

                @if (Route::has('password.request'))
                    <p class="mb-0 text-sm">
                        <a href="{{ route('password.request') }}">Forgot your password ?</a>
                    </p>
                @endif

                @if (Route::has('register'))
                    <p class="mb-0 text-sm">
                        <a href="{{ route('register') }}" class="text-center">Register a new membership</a>
                    </p>
                @endif
            </div>
        </div>
    </div>
@endsection
