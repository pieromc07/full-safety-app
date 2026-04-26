@extends('layouts.auth')
@section('title', 'Iniciar Sesión')

@section('content')
<form method="POST" action="{{ route('login') }}" class="form w-100">
    @csrf
    <div class="text-center mb-10">
        <h1 class="text-dark mb-3 text-uppercase">Iniciar Sesión</h1>
        <p class="text-gray-400 fw-semibold fs-4">Ingrese sus credenciales para acceder al sistema</p>
    </div>

    @if (session('error'))
        <div class="alert alert-danger d-flex align-items-center mb-5" role="alert">
            <i class="ki-duotone ki-shield-cross fs-2hx text-danger me-3"><span class="path1"></span><span class="path2"></span></i>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    <div class="fv-row mb-8">
        <label for="username" class="form-label fs-6 fw-bold text-dark">Usuario</label>
        <input id="username" type="text"
            class="form-control form-control-lg form-control-solid @error('username') is-invalid @enderror"
            name="username" value="{{ old('username') }}" required autocomplete="username" autofocus
            placeholder="Ingrese su usuario">
        @error('username')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="fv-row mb-8">
        <label for="password" class="form-label fs-6 fw-bold text-dark">Contraseña</label>
        <input id="password" type="password"
            class="form-control form-control-lg form-control-solid @error('password') is-invalid @enderror"
            name="password" required autocomplete="current-password"
            placeholder="Ingrese su contraseña">
        @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="text-center">
        <button type="submit" id="kt_sign_in_submit" class="btn btn-lg btn-primary w-100 mb-5">
            <span class="indicator-label">Ingresar</span>
            <span class="indicator-progress">
                Por favor espere...
                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </span>
        </button>
    </div>
</form>
@endsection
