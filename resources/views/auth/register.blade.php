@extends('layouts.app')
@section('content')
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <div class="login100-pic js-tilt" data-tilt>
                    <img src="{{ asset('products/images/img-01.png') }}" alt="IMG">
                </div>

                <form class="login100-form validate-form" action="{{ route('register') }}" method="POST">
                    @csrf
                    <span class="login100-form-title">
                        Member Login
                    </span>
                    <!--===============================================================================================-->

                    {{-- NAME --}}
                    <div class="wrap-input100 validate-input">

                        <input type="text" placeholder="User name"
                            class="form-control input100 @error('name') is-invalid @enderror" name="name"
                            value="{{ old('name') }}" required autocomplete="name" autofocus>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa-solid fa-user"></i>
                        </span>
                    </div>
                    <!--=======================================================================================================-->

                    {{-- Email --}}
                    <div class="wrap-input100 validate-input" data-validate = "Valid email is required: ex@abc.xyz">

                        <input type="text" placeholder="Email"
                            class="input100  form-control @error('email') is-invalid @enderror" name="email"
                            value="{{ old('email') }}" required autocomplete="email" autofocus>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                        </span>
                    </div>
                    <!--===============================================================================================-->

                    {{-- Password --}}

                    <div class="wrap-input100 validate-input" data-validate = "Password is required">
                        <input type="password" placeholder="Password"
                            class="input100 form-control @error('password') is-invalid @enderror" name="password" required
                            autocomplete="current-password">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                        </span>
                    </div>
                    <!--===============================================================================================-->


                    {{-- Confirm Password --}}
                    <div class="wrap-input100 validate-input" data-validate = "Password is required">
                        <input id="password-confirm" type="password" class="form-control input100"
                            name="password_confirmation" required autocomplete="new-password"
                            placeholder="Confirm Password">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                        </span>
                    </div>

                    <div class="container-login100-form-btn">
                        <button class="login100-form-btn" type="submit">
                            {{ __('Register') }}
                        </button>
                    </div>


                    <div class="text-center p-t-50">
                        <a class="txt2" href="{{ route('login') }}">
                            {{ __('Do you have an account? Sign In') }}
                            <i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
