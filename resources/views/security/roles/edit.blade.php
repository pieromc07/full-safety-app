@extends('layouts.app')

@section('title', 'Roles')
@section('page', 'Editar Rol')

@push('styles')
@endpush

@section('content')
    <x-form class="card" id="form-edit" action="{{ route('roles.update', $role) }}" method="POST" role="form">
        @method('PUT')
        <div class="card-body">
            <div class="row">
                @include('security.roles.fields', [
                    'readonly' => false,
                    'view' => null,
                    'permissions' => $permissions,
                ])
            </div>
        </div>
        <div class="card-footer">
            <x-button type="submit" id="btn-update" btn="btn-primary" title="Actualizar" position="left" text="Actualizar"
                icon="bi-save" type="submit" />
            <x-link-text-icon id="btn-back" btn="btn-secondary" title="Cerrar" position="left" text="Cerrar"
                icon="bi-x-circle" href="{{ route('roles') }}" />
        </div>
    </x-form>
@endsection


@push('scripts')
    <script type="text/javascript">
        $(document).ready(() => {

        });
    </script>
@endpush
