@extends('layouts.app')

@section('title', 'Usuarios')
@section('page', 'Editar Usuario')

@push('styles')
@endpush

@section('content')
    <x-form class="card" id="form-edit" action="{{ route('users.update', $user) }}" method="POST" role="form">
        @method('PUT')
        <div class="card-body">
            <div class="row">
                @include('security.users.fields', ['readonly' => false, 'view' => null])
            </div>
        </div>
        <div class="card-footer">
            <x-button type="submit" id="btn-update" btn="btn-primary" title="Actualizar" position="left" text="Actualizar"
                icon="bi-save" type="submit" />

            <x-link-text-icon id="btn-back" btn="btn-secondary" title="Cerrar" position="left" text="Cerrar"
                icon="bi-x-circle" href="{{ route('users') }}" />
        </div>
    </x-form>
@endsection


@push('scripts')
    <script type="text/javascript">
        $(document).ready(() => {

        });
    </script>
@endpush
