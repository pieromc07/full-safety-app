@extends('layouts.app')

@section('title', 'Usuarios')
@section('page', 'Lista de Usuarios')

@push('styles')
@endpush

@section('content')
    <div class="row mb-3 justify-content-end align-items-center">
        {{-- <div class="col-md-3 d-flex justify-content-end">
            <x-link-text-icon id="btn-create" btn="btn-primary" icon="bi-plus-circle" text="Nuevo Usuario" position="right"
                href="{{ route('users.create') }}" />
        </div> --}}
    </div>

    <div class="row justify-content-start align-items-center">
        <div class="col-md-4">
            <x-search placeholder="Buscar Rol..." id="search-user" action="{{ route('users') }}" />
        </div>
    </div>

    <div class="table-responsive">
        <x-table id="table-users">
            <x-slot name="header">
                <th colspan="1" class="text-center">ID</th>
                <th colspan="1" class="text-center">Nombre de Usuario</th>
                <th colspan="1" class="text-center">Sucursal</th>
                <th colspan="1" class="text-center">Colaborador</th>
                <th colspan="1" class="text-center">Acciones</th>
            </x-slot>
            <x-slot name='slot'>
                @foreach ($users as $user)
                    <tr class="text-center fs-5">
                        <td class="text-center">
                            {{ $user->id_users }}
                        </td>
                        <td class="text-center fs-5 fw-bold text-uppercase">
                            {{ $user->username }}
                        </td>
                        <td class="text-center">
                            {{ $user->branch->name }}
                        </td>
                        <td class="text-center">
                            {{ $user->fullname }}
                        </td>
                        <td class="text-center">
                            <x-link-icon btn="btn-info" icon="bi-eye-fill" title="Ver"
                                href="{{ route('users.show', $user) }}" />
                            <x-link-icon btn="btn-warning" icon="bi-pencil-square" title="Editar"
                                href="{{ route('users.edit', $user) }}" />

                            <x-form-table id="form-delete-{{ $user->id_users }}" action="{{ route('users.destroy', $user) }}"
                                method="POST" user="form">
                                @method('DELETE')
                                <x-button-icon btn="btn-danger" icon="bi-trash-fill" title="Eliminar"
                                    onclick="Eliminar({{ $user->user_id }})" />
                            </x-form-table>
                        </td>
                    </tr>
                @endforeach
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
        $(document).ready(() => {

        });

        function Ver(user) {
            $('#modal-showLabel').html('Ver Usuario');
            $('#usernameshow').val(user.username);
            $('#employee_idshow').val(user.fullname);
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
