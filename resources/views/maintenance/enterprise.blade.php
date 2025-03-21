@extends('layouts.app')

@section('title', 'Registro')
@section('page', 'Empresas')

@push('styles')
@endpush



@section('content')
    <div class="row">
        <div class="col-12 col-lg-4">
            <x-form class="card" id="form-create" action="{{ route('enterprise.store') }}" method="POST" role="form"
                enctype="multipart/form-data">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <x-input id="name" name="name" label="Nombre" class="form-control" placeholder="Nombre"
                                required="required" autofocus="autofocus" icon="bi-building" />
                        </div>
                        <div class="col-12">
                            <x-input id="ruc" name="ruc" label="RUC" class="form-control" placeholder="RUC"
                                required="required" autofocus="autofocus" icon="bi-123" />
                        </div>
                        <div class="col-12">
                            <x-select id="id_enterprise_types" name="id_enterprise_types" label="Tipo de Empresa"
                                placeholder="Seleccione un Tipo de Empresa" icon="bi-building">
                                <x-slot name="options">
                                    @foreach ($enterpriseTypes as $enterpriseType)
                                        <option value="{{ $enterpriseType->id_enterprise_types }}">
                                            {{ $enterpriseType->name }}
                                        </option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                        </div>
                        <div class="col-12">
                            <x-input type="file" id="image" name="image" label="Logo" class="form-control"
                                placeholder="Logo" autofocus="autofocus" icon="bi-image" req="0"
                                accept=".png, .jpg, .jpeg, .gif, .webp" />
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <x-button id="btn-store" btn="btn-primary" title="Registrar" position="left" text="Registrar"
                        icon="bi-save" />
                    <x-link-text-icon id="btn-back" btn="btn-secondary" title="Cancelar" position="left" text="Cancelar"
                        icon="bi-x-circle" href="{{ route('enterprise') }}" />
                </div>
            </x-form>

            <x-form class="card" id="form-edit" method="POST" role="form" style="display: none;"
                enctype="multipart/form-data">
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <x-input id="name" name="name" label="Nombre" class="form-control" placeholder="Nombre"
                                required="required" autofocus="autofocus" icon="bi-building" />
                        </div>
                        <div class="col-12">
                            <x-input id="ruc" name="ruc" label="RUC" class="form-control" placeholder="RUC"
                                required="required" autofocus="autofocus" icon="bi-123" />
                        </div>
                        <div class="col-12">
                            <x-select id="id_enterprise_types" name="id_enterprise_types" label="Tipo de Empresa"
                                placeholder="Seleccione un Tipo de Empresa" icon="bi-building">
                                <x-slot name="options">
                                    @foreach ($enterpriseTypes as $enterpriseType)
                                        <option value="{{ $enterpriseType->id_enterprise_types }}">
                                            {{ $enterpriseType->name }}
                                        </option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                        </div>
                        <div class="col-12">
                            <x-input type="file" id="image" name="image" label="Logo" class="form-control"
                                placeholder="Logo" autofocus="autofocus" icon="bi-image" req="0"
                                accept=".png, .jpg, .jpeg" />
                        </div>


                    </div>
                </div>
                <div class="card-footer">
                    <x-button id="btn-update" btn="btn-primary" title="Actualizar" position="left" text="Actualizar"
                        icon="bi-save" type="submit" />

                    <x-link-text-icon id="btn-back" btn="btn-secondary" title="Cancelar" position="left"
                        text="Cancelar" icon="bi-x-circle" href="{{ route('enterprise') }}" />
                </div>
            </x-form>

        </div>
        <div class="col-12 col-lg-8">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-4">
                            <form id="form-search" action="{{ route('enterprise') }}" method="GET" role="search">
                                <div class="row">
                                    <div class="col-12">
                                        <x-select id="id_enterprise_types" name="id_enterprise_types" filter="true"
                                            icon="bi-building" req="0" onchange="$('#form-search').submit()">
                                            <x-slot name="options">
                                                <option value="">Todos</option>
                                                @foreach ($enterpriseTypes as $enterpriseType)
                                                    <option value="{{ $enterpriseType->id_enterprise_types }}"
                                                        @if ($enterpriseType->id_enterprise_types == request('id_enterprise_types')) selected @endif>
                                                        {{ $enterpriseType->name }}
                                                    </option>
                                                @endforeach
                                            </x-slot>
                                        </x-select>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="table-responsive">
                        <x-table id="table-enterprises">
                            <x-slot name="header">
                                <th colspan="1" class="text-center">ID</th>
                                <th colspan="1" class="text-center">Nombre</th>
                                <th colspan="1" class="text-center">RUC</th>
                                <th colspan="1" class="text-center">Tipo de Empresa</th>
                                <th colspan="1" class="text-center">Logo</th>
                                <th colspan="1" class="text-center">Acciones</th>
                            </x-slot>
                            <x-slot name='slot'>
                                @if ($enterprises->isEmpty())
                                    <tr class="text-center fs-5">
                                        <td colspan="4">No hay registros</td>
                                    </tr>
                                @else
                                    @foreach ($enterprises as $key => $enterprise)
                                        <tr class="text-center fs-5">
                                            <td class="text-center">
                                                {{ $enterprise->id_enterprises }}
                                            </td>
                                            <td class="text-start">
                                                {{ $enterprise->name }}
                                            </td>
                                            <td class="text-center">
                                                {{ $enterprise->ruc }}
                                            </td>
                                            <td class="text-center">
                                                {{ $enterprise->enterpriseType->name }}
                                            </td>
                                            <td class="text-center">
                                              <img src="{{ asset($enterprise->image) }}" alt="{{ $enterprise->name }}"
                                              class="img-thumbnail" style="width: 50px; height: 50px;">

                                            </td>
                                            <td class="text-center">
                                                @if ($enterprise->enterpriseType->id_enterprise_types == 1)
                                                    <x-button-icon btn="btn-primary" icon="bi-card-checklist"
                                                        title="Lista de empresas asignadas"
                                                        onclick="Listar({{ $enterprise }})" />
                                                @else
                                                    <x-button-icon btn="btn-success" icon="bi-building-add"
                                                        onclick="Asignar({{ $enterprise }})" title="Asignar empresa" />
                                                @endif
                                                <x-button-icon btn="btn-warning" icon="bi-pencil-square" title="Editar"
                                                    onclick="Editar({{ $enterprise }})" />
                                                <x-form-table id="form-delete-{{ $enterprise->id_enterprises }}"
                                                    action="{{ route('enterprise.destroy', $enterprise->id_enterprises) }}"
                                                    method="POST" role="form">
                                                    @method('DELETE')
                                                    <x-button-icon btn="btn-danger" icon="bi-trash-fill" title="Eliminar"
                                                        type="button"
                                                        onclick="Eliminar({{ $enterprise->id_enterprises }})" />
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
                            <x-pagination page="{{ $enterprises->currentPage() }}"
                                lastPage="{{ $enterprises->lastPage() }}" route="enterprise"
                                perPage="{{ $enterprises->perPage() }}" total="{{ $enterprises->total() }}" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-modal id="modal-list" maxWidth="lg">
        <x-slot name="slot">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Lista de Empresas Asignadas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">ID</th>
                                <th class="text-center">Nombre</th>
                                <th class="text-center">RUC</th>
                                <th class="text-center">Tipo de Empresa</th>
                                <th class="text-center">Logo</th>
                            </tr>
                        </thead>
                        <tbody id="table-list">
                        </tbody>
                    </table>
                </div>
            </div>
        </x-slot>
    </x-modal>

    <x-modal id="modal-assign" maxWidth="md" data-bs-backdrop="static" data-bs-keyboard="false">
        <x-slot name="slot">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Asignar Empresa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="form-assign" action="{{ route('enterprise.assign') }}" method="POST" role="form">
                        @csrf
                        <input type="hidden" name="id_transport_enterprises" id="id_transport_enterprises">
                        <div class="row">
                            <div class="col-12">
                                <x-select id="id_supplier_enterprises" name="id_supplier_enterprises"
                                    label="Empresa Proveedora" placeholder="Seleccione una Empresa Proveedora"
                                    icon="bi-building">
                                    <x-slot name="options">
                                        @foreach ($onlyTransportEnterprises as $transportEnterprise)
                                            <option value="{{ $transportEnterprise->id_enterprises }}">
                                                {{ $transportEnterprise->name }}
                                            </option>
                                        @endforeach
                                    </x-slot>
                                </x-select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="$('#form-assign').submit()">Asignar</button>
                </div>
            </div>
        </x-slot>
    </x-modal>
@endsection


@push('scripts')
    <script type="text/javascript">
        $(document).ready(() => {
            $('#btn-store').click(() => {
                $('#form-create').submit();
            });
        });

        function Editar(enterprise) {
            $('#form-edit').show();
            $('#form-create').hide();
            $('#form-edit').attr('action', '/enterprises/' + enterprise.id_enterprises);
            $('#form-edit').find('#name').val(enterprise.name);
            $('#form-edit').find('#ruc').val(enterprise.ruc);
            $('#form-edit').find('#id_enterprise_types').val(enterprise.id_enterprise_types);
            $('#form-edit').find('#image').val(enterprise.image);
            $('#form-edit').find('#name').focus();
        }

        function Listar(enterprise) {
            $('#modal-list').modal('show');
            $('#table-list').empty();
            $.get('enterprises/' + enterprise.id_enterprises + '', (data) => {
                data.forEach((item) => {
                    $('#table-list').append(`
                        <tr>
                            <td class="text-center">${item.id_enterprises}</td>
                            <td class="text-center">${item.name}</td>
                            <td class="text-center">${item.ruc}</td>
                            <td class="text-center">${item.enterprisetype}</td>
                            <td class="text-center">
                                <img src="{{ asset('storage') }}/${item.image}" alt="${item.name}" class="img-thumbnail"
                                    style="width: 50px; height: 50px;">
                            </td>
                        </tr>
                    `);
                });
            });
        }

        function Asignar(enterprise) {
            $('#modal-assign').modal('show');
            $('#id_transport_enterprises').val(enterprise.id_enterprises);
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
