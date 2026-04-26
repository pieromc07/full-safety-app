@extends('layouts.app')

@section('title', 'Seguridad')
@section('page', 'Lista de Usuarios')

@push('styles')
@endpush

@section('content')
    <div class="row mb-3 justify-content-between align-items-center">
        <div class="col-md-4">
            <x-search placeholder="Buscar usuario..." id="search-user" action="{{ route('users') }}" />
        </div>
        <div class="col-md-3 d-flex justify-content-end">
            <x-link-text-icon id="btn-create" btn="btn-primary" icon="bi-plus-circle" text="Nuevo Usuario"
                position="right" href="{{ route('users.create') }}" />
        </div>
    </div>

    <div class="table-responsive">
        <x-table id="table-users">
            <x-slot name="header">
                <th colspan="1" class="text-center">ID</th>
                <th colspan="1" class="text-center">Usuario</th>
                <th colspan="1" class="text-center">Nombre Completo</th>
                <th colspan="1" class="text-center">Empresa</th>
                <th colspan="1" class="text-center">Rol</th>
                <th colspan="1" class="text-center">Estado</th>
                <th colspan="1" class="text-center">Acciones</th>
            </x-slot>
            <x-slot name='slot'>
                @if ($users->isEmpty())
                    <tr class="text-center fs-5">
                        <td colspan="7">No hay registros</td>
                    </tr>
                @else
                    @foreach ($users as $user)
                        <tr class="text-center fs-5">
                            <td class="text-center">
                                {{ $user->id_users }}
                            </td>
                            <td class="text-center fw-bold text-uppercase">
                                {{ $user->username }}
                            </td>
                            <td class="text-center">
                                {{ $user->fullname }}
                            </td>
                            <td class="text-center">
                                {{ $user->enterprise->name ?? 'Sistema' }}
                            </td>
                            <td class="text-center">
                                @foreach ($user->roles as $role)
                                    <span class="badge badge-light-primary">{{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td class="text-center">
                                @if ($user->cuid_deleted)
                                    <span class="badge badge-light-danger">Inactivo</span>
                                @else
                                    <span class="badge badge-light-success">Activo</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <x-link-icon btn="btn-info" icon="bi-eye-fill" title="Ver"
                                    href="{{ route('users.show', $user) }}" />
                                <x-link-icon btn="btn-warning" icon="bi-pencil-square" title="Editar"
                                    href="{{ route('users.edit', $user) }}" />
                                <x-form-table id="form-delete-{{ $user->id_users }}"
                                    action="{{ route('users.destroy', $user) }}" method="POST" role="form">
                                    @method('DELETE')
                                    <x-button-icon btn="btn-danger" icon="bi-trash-fill" title="Eliminar"
                                        type="button" onclick="Eliminar({{ $user->id_users }})" />
                                </x-form-table>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </x-slot>
        </x-table>
    </div>
    <div class="row">
        <div class="col-md-12 d-flex justify-content-end">
            <x-pagination page="{{ $users->currentPage() }}" lastPage="{{ $users->lastPage() }}"
                perPage="{{ $users->perPage() }}" total="{{ $users->total() }}" route="users" />
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        function Eliminar(id) {
            event.preventDefault();
            Swal.fire({
                title: '¿Estás seguro de eliminar este usuario?',
                text: "¡Esta acción no se puede revertir!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '¡Sí, eliminar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $(`#form-delete-${id}`).submit();
                }
            });
        }
    </script>
@endpush
