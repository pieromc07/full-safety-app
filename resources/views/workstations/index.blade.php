@extends('layouts.app')

@section('title', 'Puestos de Trabajo')
@section('page', 'Lista de Puestos de Trabajo')

@push('styles')
@endpush

@section('content')
    <div class="row mb-3 justify-content-end align-items-center">
        <div class="col-md-3 d-flex justify-content-end">
            <x-link-text-icon id="btn-create" btn="btn-primary" icon="bi-plus-circle" text="Nuevo Puesto de Trabajo"
                position="right" href="{{ route('workstations.create') }}" />
        </div>
    </div>

    <div class="row justify-content-start align-items-center">
        <div class="col-md-4">
            <x-search placeholder="Buscar Puesto de Trabajo..." id="search-workstation"
                action="{{ route('workstations') }}" />
        </div>
    </div>

    <div class="table-responsive">
        <x-table id="table-workstations">
            <x-slot name="header">
                <th colspan="1" class="text-center">ID</th>
                <th colspan="1" class="text-center">Nombre</th>
                @if (session('role') == 'master')
                    <th colspan="1" class="text-center">Empresa</th>
                @endif
                <th colspan="1" class="text-center">Acciones</th>
            </x-slot>
            <x-slot name='slot'>
                @foreach ($workstations as $workstation)
                    <tr class="text-center fs-5">
                        <td class="text-center">
                            {{ $workstation->id_workstations }}
                        </td>
                        <td class="text-center">
                            {{ $workstation->name }}
                        </td>
                        @if (session('role') == 'master')
                            <td class="text-center">
                                {{ $workstation->enterprise->commercial_name }}
                            </td>
                        @endif
                        <td class="text-center">
                            <x-button-icon btn="btn-info" icon="bi-eye-fill" title="Ver"
                                onclick="Ver({{ $workstation }})" />
                            <x-link-icon btn="btn-warning" icon="bi-pencil-square" title="Editar"
                                href="{{ route('workstations.edit', $workstation) }}" />
                            <x-form-table id="form-delete-{{ $workstation->id_workstations }}"
                                action="{{ route('workstations.destroy', $workstation) }}" method="POST" role="form">
                                @method('DELETE')
                                <x-button-icon btn="btn-danger" icon="bi-trash-fill" title="Eliminar"
                                    onclick="Eliminar({{ $workstation->id_workstations }})" />
                            </x-form-table>
                        </td>
                    </tr>
                @endforeach
            </x-slot>
        </x-table>
    </div>
    <div class="row">
        <div class="col-md-12 d-flex justify-content-end">
            <x-pagination page="{{ $workstations->currentPage() }}" lastPage="{{ $workstations->lastPage() }}"
                route="workstations" perPage="{{ $workstations->perPage() }}" total="{{ $workstations->total() }}" />
        </div>
    </div>
    @include('workstations.show')
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(() => {

        });

        function Ver(workstation) {
            $('#nameshow').val(workstation.name);
            $('#modal-show').modal('show');
        }

        function Eliminar(id) {
            event.preventDefault();
            const form = $('#form-delete-' + id);
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#dc3545',
                confirmButtonText: 'Sí, eliminar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
@endpush
