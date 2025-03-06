@extends('layouts.app')

@section('title', 'Puestos de Trabajo')
@section('page', 'Crear Puesto de Trabajo')

@push('styles')
@endpush

@section('content')
    <x-form class="card" id="form-create" action="{{ route('workstations.store') }}" method="POST" role="form">
        <div class="card-body">
            <div class="row">
                @include('workstations.fields', ['readonly' => false, 'view' => null])
            </div>
        </div>
        <div class="card-footer">
            <x-button type="submit" id="btn-store" btn="btn-primary" title="Registrar" position="left" text="Registrar"
                icon="bi-save" />

            <x-link-text-icon id="btn-back" btn="btn-secondary" title="Cerrar" position="left" text="Cerrar"
                icon="bi-x-circle" href="{{ route('workstations') }}" />
        </div>
    </x-form>
@endsection


@push('scripts')
    <script type="text/javascript">
        $(document).ready(() => {

        });
    </script>
@endpush
