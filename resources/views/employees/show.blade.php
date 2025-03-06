@extends('layouts.app')

@section('title', 'Empleados')
@section('page', 'Editar Empleado')

@push('styles')
@endpush

@section('content')
    @if (session('success'))
        <x-alert type="success" message="{{ session('success') }}" />
    @elseif(session('error'))
        <x-alert type="danger" message="{{ session('error') }}" />
    @elseif(session('info'))
        <x-alert type="info" message="{{ session('info') }}" />
    @endif
    <x-form class="card" id="form-edit" action="{{ route('employees.update', $employee) }}" method="POST" role="form">
        @method('PUT')
        <div class="card-body">
            <div class="row">
                @include('employees.fields', ['readonly' => true, 'view' => null])
            </div>
        </div>
        <div class="card-footer">


            <x-link-text-icon id="btn-back" btn="btn-secondary" title="Cerrar" position="left" text="Cerrar"
                icon="bi-x-circle" href="{{ route('employees') }}" />
        </div>
    </x-form>
@endsection
