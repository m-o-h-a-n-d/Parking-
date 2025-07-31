@extends('layouts.app')
@section('content')
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100 ">
                <div class="login100-pic js-tilt" data-tilt>
                    <img src="{{ asset('products/images/img-01.png') }}" alt="IMG">
                </div>
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form class="login100-form validate-form" action="{{ route('password.email') }}" method="POST">
                    @csrf
                    <span class="login100-form-title">
                        Member Login
                    </span>

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



                    <div class="container-login100-form-btn">
                        <button class="login100-form-btn" type="submit">
                            {{ __('Send Password Reset Link') }}
                        </button>
                    </div>

                     <div class="text-center p-t-136">
                        <a class="txt2" href="{{ route('login') }}">
                            {{__('LOGIN')}}
                            <i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
                        </a>
                    </div>


                </form>
            </div>
        </div>
    </div>
@endsection
