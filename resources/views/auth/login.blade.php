@extends('layouts.auth')
@section('title', 'Login')

@section('content')
    <form method="POST" action="{{ route('login') }}" class="form w-100">
        <!--begin::Heading-->
        <div class="text-center mb-10">
            <!--begin::Title-->
            <h1 class="text-dark mb-3 text-uppercase">
                {{ __('Login') }}
            </h1>
            <!--end::Title-->
        </div>
        <!--end::Heading-->
        @csrf
        <div class="fv-row mb-10">
            <label for="username" class="form-label fs-6 fw-bold text-dark">{{ __('Username') }}</label>
            <input id="username" type="username"
                class="form-control form-control-lg form-control-solid
                @error('username') is-invalid @enderror"
                name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>
            @error('username')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="fv-row mb-10">
            <div class="d-flex flex-stack mb-2">
                <label for="password" class="form-label fs-6 fw-bold text-dark">{{ __('Password') }}</label>
                <a href="{{ route('password.request') }}" class="link-primary fs-6 fw-bold">
                    {{ __('Forgot Your Password?') }}
                </a>
            </div>
            <input id="password" type="password"
                class="form-control form-control-lg form-control-solid @error('password') is-invalid @enderror"
                name="password" required autocomplete="current-password">

            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="text-center">
            <button type="submit" id="kt_sign_in_submit" class="btn btn-lg btn-primary w-100 mb-5">
                <span class="indicator-label">
                    {{ __('Login') }}
                </span>
                <span class="indicator-progress">
                    {{ __('Please wait...') }}
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
            </button>
        </div>
    </form>
@endsection
