@extends('layouts.guest')

@section('content')
    <div
        style="min-height: 100vh; width: 100vw; display: flex; align-items: center; justify-content: center; background-image: url('{{ asset('assets/img/background.jpg') }}'); background-size: cover; background-position: center;">
        <div class="register-box">
            <div class="card card-outline card-primary">
                <div class="card-header text-center">
                    <a href="" class="h1 d-flex align-items-center justify-content-center">
                        <img src="{{ asset('assets/img/logo/logo1.png') }}" alt="Logo Simapres"
                            style="max-height: 40px; margin-right: 10px;">
                        <span style="font-size: 40px; font-weight: bold; color: #1a2151;">Simapres</span>
                    </a>
                </div>
                <div class="card-body">
                    <p class="login-box-msg">Register a new account</p>

                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <p class="text-danger text-center">{{ $error }}</p>
                        @endforeach
                    @endif

                    <form action="{{ route('register.proces') }}" method="POST">
                        @csrf
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Full name" name="name" value="{{ old('name') }}" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="email" class="form-control" placeholder="Email" name="email" value="{{ old('email') }}" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" placeholder="Password" name="password" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" placeholder="Retype password" name="password_confirmation" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8">
                                <div class="icheck-primary">
                                    <input type="checkbox" id="agreeTerms" name="terms" value="agree">
                                    <label for="agreeTerms">
                                        I agree to the <a href="#" style="color: #1a2151">terms</a>
                                    </label>
                                </div>
                            </div>
                            <div class="col-4">
                                <button type="submit" class="btn btn-block"
                                    style="background-color: #1a2151; color: white;">Register</button>
                            </div>
                        </div>
                    </form>

                    {{-- <div class="social-auth-links text-center mt-2 mb-3">
                        <a href="#" class="btn btn-block btn-primary">
                            <i class="fab fa-facebook mr-2"></i> Sign up using Facebook
                        </a>
                        <a href="#" class="btn btn-block btn-danger">
                            <i class="fab fa-google-plus mr-2"></i> Sign up using Google+
                        </a>
                    </div> --}}

                    <p class="mb-0">
                        <a href="{{ route('login') }}" class="text-center">I already have a account</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
