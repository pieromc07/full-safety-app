@extends('layouts.app')

@section('title', 'Permisos')
@section('page', 'Lista de Permisos')

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
            <x-link-text-icon id="btn-create" btn="btn-primary" icon="bi-plus-circle" text="Nuevo Permiso" position="right"
                href="{{ route('permissions.create') }}" />
        </div>
    </div>

    <div class="row justify-content-start align-items-center">
        <div class="col-md-4">
            <x-search placeholder="Buscar Permiso..." id="search-permission" action="{{ route('permissions') }}" />
        </div>
    </div>

    <div class="table-responsive">
        <x-table id="table-permisos">
            <x-slot name="header">
                <th colspan="1" class="text-center">ID</th>
                <th colspan="1" class="text-center">Nombre</th>
                <th colspan="1" class="text-center">Permiso</th>
                <th colspan="1" class="text-center">guard</th>
                <th colspan="1" class="text-center">Acciones</th>
            </x-slot>
            <x-slot name='slot'>
                @foreach ($permissions as $permission)
                    <tr class="text-center fs-5">
                        <td class="text-center">
                            {{ $permission->id }}
                        </td>
                        <td class="text-center">
                            {{ $permission->description }}
                        </td>
                        <td class="text-center">
                            {{ $permission->name }}
                        </td>
                        <td class="text-center">
                            {{ $permission->guard_name }}
                        </td>
                        <td class="text-center">
                            <x-button-icon btn="btn-info" icon="bi-eye-fill" title="Ver"
                                onclick="Ver({{ $permission }})" />
                            <x-link-icon btn="btn-warning" icon="bi-pencil-square" title="Editar"
                                href="{{ route('permissions.edit', $permission) }}" />

                            <x-form-table id="form-delete-{{ $permission->id }}"
                                action="{{ route('permissions.destroy', $permission) }}" method="POST" role="form">
                                @method('DELETE')
                                <x-button-icon btn="btn-danger" icon="bi-trash-fill" title="Eliminar"
                                    onclick="Eliminar({{ $permission->id }})" />
                            </x-form-table>
                        </td>
                    </tr>
                @endforeach
            </x-slot>
        </x-table>
    </div>
    <div class="row">
        <div class="col-md-12 d-flex justify-content-end">
            <x-pagination page="{{ $permissions->currentPage() }}" lastPage="{{ $permissions->lastPage() }}" route="permissions"
                perPage="{{ $permissions->perPage() }}" total="{{ $permissions->total() }}" />
        </div>
    </div>
    @include('security.permissions.show')
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(() => {

        });

        function Ver(per) {
            $('#modal-showLabel').html('Ver Permiso');
            $('#nameshow').val(per.name);
            $('#descriptionshow').val(per.description);
            $('#guard_nameshow').val(per.guard_name);
            $('#modal-show').modal('show');
        }

        function Eliminar(id) {
            event.preventDefault();
            const form = $(`#form-delete-${id}`);
            Swal.fire({
                title: '¿Estas seguro de eliminar el registro?',
                text: "Esta accion no se puede revertir!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#dc3545',
                confirmButtonText: 'Si, eliminar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            })
        }
    </script>
@endpush
