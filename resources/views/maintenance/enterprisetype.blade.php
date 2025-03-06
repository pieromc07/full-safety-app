@extends('layouts.app')

@section('title', 'Mantenimiento')
@section('page', 'Tipo de Empresa')

@push('styles')
@endpush

@section('content')
    <div class="row">
        <div class="col-12 col-lg-6">
            <x-form class="card" id="form-create" action="{{ route('enterprisetype.store') }}" method="POST" role="form">
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
                        icon="bi-x-circle" href="{{ route('enterprisetype') }}" />
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
                        icon="bi-x-circle" href="{{ route('enterprisetype') }}" />
                </div>
            </x-form>

        </div>
        <div class="col-12 col-lg-6">
            <div class="table-responsive">
                <x-table id="table-enterpriseTypes">
                    <x-slot name="header">
                        <th colspan="1" class="text-center">ID</th>
                        <th colspan="1" class="text-center">Nombre</th>
                        <th colspan="1" class="text-center">Descripción</th>
                        <th colspan="1" class="text-center">Acciones</th>
                    </x-slot>
                    <x-slot name='slot'>
                        @if ($enterpriseTypes->isEmpty())
                            <tr class="text-center fs-5">
                                <td colspan="4">No hay registros</td>
                            </tr>
                        @else
                            @foreach ($enterpriseTypes as $key => $enterpriseType)
                                <tr class="text-center fs-5">
                                    <td class="text-center">
                                        {{ $enterpriseType->id_enterprise_types }}
                                    </td>
                                    <td class="text-center">
                                        {{ $enterpriseType->name }}
                                    </td>
                                    <td class="text-center">
                                        {{ $enterpriseType->description }}
                                    </td>
                                    <td class="text-center">
                                        {{-- <x-button-icon btn="btn-info" icon="bi-eye-fill" title="Ver" onclick="" /> --}}
                                        <x-button-icon btn="btn-warning" icon="bi-pencil-square" title="Editar"
                                            onclick="Editar({{ $enterpriseType }})" />
                                        <x-form-table id="form-delete-{{ $enterpriseType->id_enterprise_types }}"
                                            action="{{ route('enterprisetype.destroy', $enterpriseType->id_enterprise_types) }}"
                                            method="POST" role="form">
                                            @method('DELETE')
                                            <x-button-icon btn="btn-danger" icon="bi-trash-fill" title="Eliminar"
                                                type="button"
                                                onclick="Eliminar({{ $enterpriseType->id_enterprise_types }})" />
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
                    <x-pagination page="{{ $enterpriseTypes->currentPage() }}"
                        lastPage="{{ $enterpriseTypes->lastPage() }}" route="enterprisetype"
                        perPage="{{ $enterpriseTypes->perPage() }}" total="{{ $enterpriseTypes->total() }}" />
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

        function Editar(enterpriseType) {
            $('#form-edit').show();
            $('#form-create').hide();
            $('#form-edit').attr('action', '/enterprisetypes/' + enterpriseType.id_enterprise_types);
            $('#form-edit').find('#name').val(enterpriseType.name);
            $('#form-edit').find('#description').val(enterpriseType.description);
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
