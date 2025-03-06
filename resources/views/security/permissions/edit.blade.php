@extends('layouts.app')

@section('title', 'Permisos')
@section('page', 'Editar Permiso')

@push('styles')
@endpush

@section('content')
    <x-form class="card" id="form-edit" action="{{ route('permissions.update', $permission) }}" method="POST" role="form">
        @method('PUT')
        <div class="card-body">
            <div class="row">
                @include('security.permissions.fields', ['readonly' => false, 'view' => null])
            </div>
        </div>
        <div class="card-footer">
            <x-button type="submit" id="btn-update" btn="btn-primary" title="Actualizar" position="left" text="Actualizar"
                icon="bi-save" type="submit" />

            <x-link-text-icon id="btn-back" btn="btn-secondary" title="Cerrar" position="left" text="Cerrar"
                icon="bi-x-circle" href="{{ route('permissions') }}" />
        </div>
    </x-form>
@endsection


@push('scripts')
    <script type="text/javascript">
        $(document).ready(() => {

        });
    </script>
@endpush
