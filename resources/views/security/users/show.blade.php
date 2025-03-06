@extends('layouts.app')

@section('title', 'Usuarios')
@section('page', 'Crear Usuario')

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
    <x-form class="card" id="form-create" action="{{ route('users.store') }}" method="POST" role="form">
        <div class="card-body">
            <div class="row">
                @include('security.users.fields', ['readonly' => true, 'view' => null])
            </div>
        </div>
        <div class="card-footer">
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
