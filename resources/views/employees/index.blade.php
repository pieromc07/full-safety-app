@extends('layouts.app')

@section('title', 'Empleados')
@section('page', 'Lista de Empleados')

@push('styles')
@endpush

@section('content')

    {{-- NOTE: Mensaje de exito al realizar una accion --}}
    @if (session('success'))
        <x-alert type="success" message="{{ session('success') }}" />
    @elseif(session('error'))
        <x-alert type="danger" message="{{ session('error') }}" />
    @endif

    <div class="row mb-3 justify-content-end align-items-center">
        <div class="col-md-3 d-flex justify-content-end">
            <x-link-text-icon id="btn-create" btn="btn-primary" icon="bi-plus-circle" text="Nuevo Empleado" position="right"
                href="{{ route('employees.create') }}" />
        </div>
    </div>

    <div class="row justify-content-start align-items-center">
        <div class="col-md-4">
            <x-search placeholder="Buscar Empleado..." id="search-employee" action="{{ route('employees') }}" />
        </div>
    </div>

    <div class="table-responsive">
        <x-table id="table-employees">
            <x-slot name="header">
                <th colspan="1" class="text-center">ID</th>
                <th colspan="1" class="text-center">Documento</th>
                <th colspan="1" class="text-center">Nº Documento</th>
                <th colspan="1" class="text-center">Nombre</th>
                <th colspan="1" class="text-center">Sucursal</th>
                <th colspan="1" class="text-center">Puesto de Trabajo</th>
                <th colspan="1" class="text-center">Usuario</th>
                <th colspan="1" class="text-center" style="width: 300px">Acciones</th>
            </x-slot>
            <x-slot name='slot'>
                @foreach ($employees as $employee)
                    <tr class="text-center fs-5">
                        <td class="text-center">
                            {{ $employee->id_employees }}
                        </td>
                        <td class="text-center">
                            {{ $employee->documentType->name }}
                        </td>
                        <td class="text-center">
                            {{ $employee->document_number }}
                        </td>
                        <td class="text-center">
                            {{ $employee->fullname }}
                        </td>
                        <td class="text-center">
                            {{ $employee->branch->name }}
                        </td>
                        <td class="text-center">
                            {{ $employee->workstation->name }}
                        </td>
                        <td class="text-center d-flex justify-content-center gap-3">
                            @if ($employee->user)
                                <x-link-icon btn="btn-success" icon="bi-person" title="Ver Usuario"
                                    href="{{ route('users.show', $employee->user) }}" target="_blank" />
                                <x-link-icon btn="btn-warning" icon="bi-pencil-square" title="Editar Usuario"
                                    href="{{ route('users.edit', $employee->user) }}" target="_blank" />
                            @else
                                <!-- Generate user -->
                                <x-form-table id="form-user-{{ $employee->id }}"
                                    action="{{ route('employees.user.generate', $employee) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    <x-button-icon btn="btn-success" icon="bi-person-plus" title="Generar Usuario" />
                                </x-form-table>
                            @endif
                        </td>
                        <td class="text-center gap-3">
                            <x-link-icon btn="btn-info" icon="bi-eye-fill" title="Ver"
                                href="{{ route('employees.show', $employee) }}" />
                            <x-link-icon btn="btn-warning" icon="bi-pencil-square" title="Editar"
                                href="{{ route('employees.edit', $employee) }}" />
                            <x-form-table id="form-delete-{{ $employee->id }}"
                                action="{{ route('employees.destroy', $employee) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <x-button-icon btn="btn-danger" icon="bi-trash" title="Eliminar"
                                    onclick="Eliminar({{ $employee->id }})" />
                            </x-form-table>
                        </td>
                    </tr>
                @endforeach
            </x-slot>
        </x-table>
    </div>
    <div class="row mt-4">
        <div class="col-md-12 d-flex justify-content-end">
            <x-pagination page="{{ $employees->currentPage() }}" lastPage="{{ $employees->lastPage() }}" route="employees"
                perPage="{{ $employees->perPage() }}" total="{{ $employees->total() }}" />
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(() => {

        });


        function Eliminar(id) {
            event.preventDefault();
            const form = $('#form-delete-' + id);
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '¡Sí, bórralo!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
@endpush
