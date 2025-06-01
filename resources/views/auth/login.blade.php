@extends('layouts.guest')

@section('content')
    <div class="login-box" style="background-image: url({{ asset('assets/img/bg-login.png') }})">

        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="" class="h1 d-flex align-items-center justify-content-center">
                    <img src="{{ asset('assets/img/logo/logo1.png') }}" alt="Logo Simapres" style="max-height: 40px; margin-right: 10px;">
                    <span style="font-size: 40px; font-weight: bold; color: #1a2151;">Simapres</span>
                </a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Sign in to start your session</p>

                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <p class="text-danger text-center">{{ $error }}</p>
                    @endforeach
                @endif

                <form action="{{ route('login.proces') }}" method="POST">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" name="remember" id="remember">
                                <label for="remember">
                                    Remember Me
                                </label>
                            </div>
                        </div>

                        <div class="col-4">
                            <button type="submit" class="btn btn-block" style="background-color: #1a2151; color: white;">Sign In</button>
                        </div>
                    </div>
                </form>

                {{-- <div class="social-auth-links text-center mt-2 mb-3">
                    <a href="#" class="btn btn-block btn-primary">
                        <i class="fab fa-facebook mr-2"></i> Sign in using Facebook
                    </a>
                    <a href="#" class="btn btn-block btn-danger">
                        <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
                    </a>
                </div> --}}

                {{-- <p class="mb-1">
                    <a href="forgot-password.html">I forgot my password</a>
                </p> --}}
                <p class="mb-0">
                    <a href="{{ route('register') }}" class="text-center">Register a new membership</a>
                </p>
            </div>
        </div>
    </div>
@endsection
