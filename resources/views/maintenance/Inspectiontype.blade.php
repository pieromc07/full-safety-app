@extends('layouts.app')

@section('title', 'Mantenimiento')
@section('page', 'Tipo de Inspección')

@push('styles')
@endpush

@section('content')
    <div class="row">
        <div class="col-12 col-lg-6">
            <x-form class="card" id="form-create" action="{{ route('inspectiontype.store') }}" method="POST" role="form">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <x-input id="name" name="name" label="Nombre" class="form-control" placeholder="Nombre"
                                required="required" autofocus="autofocus" icon="bi-building" />
                        </div>
                        <div class="col-12">
                            <x-textarea id="description" name="description" label="Descripción" class="form-control"
                                placeholder="Descripción" req="0" />
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <x-button id="btn-store" btn="btn-primary" title="Registrar" position="left" text="Registrar"
                        icon="bi-save" />
                    <x-link-text-icon id="btn-back" btn="btn-secondary" title="Cancelar" position="left" text="Cancelar"
                        icon="bi-x-circle" href="{{ route('inspectiontype') }}" />
                </div>
            </x-form>

            <x-form class="card" id="form-edit" method="POST" role="form" style="display: none;">
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <x-input id="name" name="name" label="Nombre" class="form-control" placeholder="Nombre"
                                required="required" autofocus="autofocus" icon="bi-building" />
                        </div>
                        <div class="col-12">
                            <x-textarea id="description" name="description" label="Descripción" class="form-control"
                                placeholder="Descripción" req="0" />
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <x-button id="btn-update" btn="btn-primary" title="Actualizar" position="left" text="Actualizar"
                        icon="bi-save" type="submit" />

                    <x-link-text-icon id="btn-back" btn="btn-secondary" title="Cancelar" position="left" text="Cancelar"
                        icon="bi-x-circle" href="{{ route('inspectiontype') }}" />
                </div>
            </x-form>

        </div>
        <div class="col-12 col-lg-6">
            <div class="table-responsive">
                <x-table id="table-inspectiontypes">
                    <x-slot name="header">
                        <th colspan="1" class="text-center">ID</th>
                        <th colspan="1" class="text-center">Nombre</th>
                        <th colspan="1" class="text-center">Descripción</th>
                        <th colspan="1" class="text-center">Acciones</th>
                    </x-slot>
                    <x-slot name='slot'>
                        @if ($inspectionTypes->isEmpty())
                            <tr class="text-center fs-5">
                                <td colspan="4">No hay registros</td>
                            </tr>
                        @else
                            @foreach ($inspectionTypes as $key => $inspectionType)
                                <tr class="text-center fs-5">
                                    <td class="text-center">
                                        {{ $inspectionType->id_inspection_types }}
                                    </td>
                                    <td class="text-center">
                                        {{ $inspectionType->name }}
                                    </td>
                                    <td class="text-center">
                                        {{ $inspectionType->description }}
                                    </td>
                                    <td class="text-center">
                                        <x-button-icon btn="btn-warning" icon="bi-pencil-square" title="Editar"
                                            onclick="Editar({{ $inspectionType }})" />
                                        <x-form-table id="form-delete-{{ $inspectionType->id_inspection_types }}"
                                            action="{{ route('inspectiontype.destroy', $inspectionType->id_inspection_types) }}" method="POST"
                                            role="form">
                                            @method('DELETE')
                                            <x-button-icon btn="btn-danger" icon="bi-trash-fill" title="Eliminar" type="button"
                                                onclick="Eliminar({{ $inspectionType->id_inspection_types }})" />
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
                    <x-pagination page="{{ $inspectionTypes->currentPage() }}"
                        lastPage="{{ $inspectionTypes->lastPage() }}" route="inspectiontype"
                        perPage="{{ $inspectionTypes->perPage() }}" total="{{ $inspectionTypes->total() }}" />
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

        function Editar(inspectiontype) {
            $('#form-edit').show();
            $('#form-create').hide();
            $('#form-edit').attr('action', '/inspectiontypes/' + inspectiontype.id_inspection_types);
            $('#form-edit').find('#name').val(inspectiontype.name);
            $('#form-edit').find('#description').val(inspectiontype.description);
            $('#form-edit').find('#name').focus();
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
