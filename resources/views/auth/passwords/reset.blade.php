@extends('layouts.app')
@section('content')
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <div class="login100-pic js-tilt" data-tilt>
                    <img src="{{ asset('products/images/img-01.png') }}" alt="IMG">
                </div>

                <form class="login100-form validate-form" action="{{ route('password.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <span class="login100-form-title">
                        Reset password
                    </span>

                    <div class="wrap-input100 validate-input" data-validate = "Valid email is required: ex@abc.xyz">

                        <input type="text" placeholder="Email"
                            class="input100  form-control @error('email') is-invalid @enderror" name="email"
                            value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
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
                            {{ __('Reset Password') }}
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
