@extends('layouts.app')

@section('title', 'Mantenimiento')
@section('page', 'Unidades de Medida')

@push('styles')
@endpush

@section('content')
    <div class="row">
        <div class="col-12 col-lg-5">
            <x-form class="card" id="form-create" action="{{ route('unit.store') }}" method="POST" role="form">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-7">
                            <x-input id="name" name="name" label="Nombre" class="form-control" placeholder="Nombre"
                                required="required" autofocus="autofocus" icon="bi-rulers" />
                        </div>
                        <div class="col-12 col-md-5">
                            <x-input id="abbreviation" name="abbreviation" label="Abreviatura" class="form-control"
                                placeholder="Abreviatura" required="required" icon="bi-type" />
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <x-button id="btn-store" btn="btn-primary" title="Registrar" position="left" text="Registrar"
                        icon="bi-save" />
                    <x-link-text-icon id="btn-back" btn="btn-secondary" title="Cancelar" position="left" text="Cancelar"
                        icon="bi-x-circle" href="{{ route('unit') }}" />
                </div>
            </x-form>

            <x-form class="card" id="form-edit" method="POST" role="form" style="display: none;">
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-7">
                            <x-input id="edit-name" name="name" label="Nombre" class="form-control" placeholder="Nombre"
                                required="required" autofocus="autofocus" icon="bi-rulers" />
                        </div>
                        <div class="col-12 col-md-5">
                            <x-input id="edit-abbreviation" name="abbreviation" label="Abreviatura" class="form-control"
                                placeholder="Abreviatura" required="required" icon="bi-type" />
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <x-button id="btn-update" btn="btn-primary" title="Actualizar" position="left" text="Actualizar"
                        icon="bi-save" type="submit" />
                    <x-link-text-icon id="btn-back" btn="btn-secondary" title="Cancelar" position="left" text="Cancelar"
                        icon="bi-x-circle" href="{{ route('unit') }}" />
                </div>
            </x-form>
        </div>
        <div class="col-12 col-lg-7">
            <div class="table-responsive">
                <x-table id="table-units">
                    <x-slot name="header">
                        <th colspan="1" class="text-center">ID</th>
                        <th colspan="1" class="text-center">Nombre</th>
                        <th colspan="1" class="text-center">Abreviatura</th>
                        <th colspan="1" class="text-center">Acciones</th>
                    </x-slot>
                    <x-slot name='slot'>
                        @if ($units->isEmpty())
                            <tr class="text-center fs-5">
                                <td colspan="4">No hay registros</td>
                            </tr>
                        @else
                            @foreach ($units as $key => $unit)
                                <tr class="text-center fs-5">
                                    <td class="text-center">{{ $unit->id_units }}</td>
                                    <td class="text-center">{{ $unit->name }}</td>
                                    <td class="text-center">
                                        <span class="badge badge-light-primary">{{ $unit->abbreviation }}</span>
                                    </td>
                                    <td class="text-center">
                                        <x-button-icon btn="btn-warning" icon="bi-pencil-square" title="Editar"
                                            onclick="Editar({{ $unit }})" />
                                        <x-form-table id="form-delete-{{ $unit->id_units }}"
                                            action="{{ route('unit.destroy', $unit->id_units) }}"
                                            method="POST" role="form">
                                            @method('DELETE')
                                            <x-button-icon btn="btn-danger" icon="bi-trash-fill" title="Eliminar"
                                                type="button"
                                                onclick="Eliminar({{ $unit->id_units }})" />
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
                    <x-pagination page="{{ $units->currentPage() }}" lastPage="{{ $units->lastPage() }}"
                        route="unit" perPage="{{ $units->perPage() }}"
                        total="{{ $units->total() }}" />
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(() => {
            $('#btn-store').click(() => {
                $('#form-create').submit();
            });
        });

        function Editar(unit) {
            $('#form-edit').show();
            $('#form-create').hide();
            $('#form-edit').attr('action', '/units/' + unit.id_units);
            $('#form-edit').find('#edit-name').val(unit.name);
            $('#form-edit').find('#edit-abbreviation').val(unit.abbreviation);
            $('#form-edit').find('#edit-name').focus();
        }

        function Eliminar(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '¡Sí, bórralo!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#form-delete-' + id).submit();
                }
            });
        }
    </script>
@endpush
