@extends('layouts.app')

@section('title', 'Seguridad')
@section('page', 'Editar Usuario')

@push('styles')
@endpush

@section('content')
    <x-form class="card" id="form-edit" action="{{ route('users.update', $user) }}" method="POST" role="form">
        @method('PUT')
        <div class="card-body">
            <div class="row">
                @include('security.users.fields', ['readonly' => false, 'view' => 'edit'])
            </div>
        </div>
        <div class="card-footer">
            <x-button type="submit" id="btn-update" btn="btn-primary" title="Actualizar" position="left"
                text="Actualizar" icon="bi-save" />
            <x-link-text-icon id="btn-back" btn="btn-secondary" title="Cerrar" position="left" text="Cerrar"
                icon="bi-x-circle" href="{{ route('users') }}" />
        </div>
    </x-form>
@endsection
