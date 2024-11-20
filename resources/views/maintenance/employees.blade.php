@extends('layouts.app')

@section('title', 'Registro')
@section('page', 'Personal')

@push('styles')
@endpush

@section('content')
    <div class="row">
        <div class="col-12 col-lg-6">
            <x-form class="card" id="form-create" action="{{ route('employee.store') }}" method="POST" role="form"
                enctype="multipart/form-data">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <x-input id="document" name="document" label="N° de Documento" class="form-control"
                                placeholder="N° de documento" required="required" autofocus="autofocus" icon="bi-person" />
                        </div>
                        <div class="col-12">
                            <x-input id="name" name="name" label="Nombre" class="form-control" placeholder="Nombre"
                                required="required" autofocus="autofocus" icon="bi-person" />
                        </div>
                        <div class="col-12">
                            <x-input id="lastname" name="lastname" label="Apellidos" class="form-control"
                                placeholder="Apellidos" required="required" autofocus="autofocus" icon="bi-person" />
                        </div>
                        <div class="col-12">
                            <x-select id="transport_enterprise_id" name="transport_enterprise_id" icon="bi-building"
                                label="Empresa Transportista" placeholder="Seleccione empresa">
                                <x-slot name="options">
                                    @foreach ($enterpriseTransports as $transport)
                                        <option value="{{ $transport->id }}">
                                            {{ $transport->name }}
                                        </option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <x-button id="btn-store" btn="btn-primary" title="Registrar" position="left" text="Registrar"
                        icon="bi-save" />
                    <x-link-text-icon id="btn-back" btn="btn-secondary" title="Cancelar" position="left" text="Cancelar"
                        icon="bi-x-circle" href="{{ route('employee') }}" />
                </div>
            </x-form>

            <x-form class="card" id="form-edit" method="POST" role="form" style="display: none;"
                enctype="multipart/form-data">
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <x-input id="document" name="document" label="N° de Documento" class="form-control"
                                placeholder="N° de documento" required="required" autofocus="autofocus" icon="bi-person" />
                        </div>
                        <div class="col-12">
                            <x-input id="name" name="name" label="Nombre" class="form-control" placeholder="Nombre"
                                required="required" autofocus="autofocus" icon="bi-person" />
                        </div>
                        <div class="col-12">
                            <x-input id="lastname" name="lastname" label="Apellidos" class="form-control"
                                placeholder="Apellidos" required="required" autofocus="autofocus" icon="bi-person" />
                        </div>
                        <div class="col-12">
                            <x-select id="transport_enterprise_id" name="transport_enterprise_id" icon="bi-building"
                                label="Empresa Transportista" placeholder="Seleccione empresa">
                                <x-slot name="options">
                                    @foreach ($enterpriseTransports as $transport)
                                        <option value="{{ $transport->id }}">
                                            {{ $transport->name }}
                                        </option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <x-button id="btn-update" btn="btn-primary" title="Actualizar" position="left" text="Actualizar"
                        icon="bi-save" type="submit" />

                    <x-link-text-icon id="btn-back" btn="btn-secondary" title="Cancelar" position="left"
                        text="Cancelar" icon="bi-x-circle" href="{{ route('employee') }}" />
                </div>
            </x-form>

        </div>
        <div class="col-12 col-lg-6">
            <div class="table-responsive">
                <x-table id="table-employees">
                    <x-slot name="header">
                        <th colspan="1" class="text-center">ID</th>
                        <th colspan="1" class="text-center">Documento</th>
                        <th colspan="1" class="text-center">Nombre</th>
                        <th colspan="1" class="text-center">Acciones</th>
                    </x-slot>
                    <x-slot name='slot'>
                        @if ($employees->isEmpty())
                            <tr class="text-center fs-5">
                                <td colspan="4">No hay registros</td>
                            </tr>
                        @else
                            @foreach ($employees as $key => $employee)
                                <tr class="text-center fs-5">
                                    <td class="text-center">
                                        {{ $employee->id }}
                                    </td>
                                    <td class="text-center">
                                        {{ $employee->document }}
                                    </td>
                                    <td class="text-center">
                                        {{ $employee->fullname }}
                                    </td>
                                    <td class="text-center">
                                        {{-- <x-button-icon btn="btn-info" icon="bi-eye-fill" title="Ver" onclick="" /> --}}
                                        <x-button-icon btn="btn-warning" icon="bi-pencil-square" title="Editar"
                                            onclick="Editar({{ $employee }})" />
                                        <x-form-table id="form-delete-{{ $employee->id }}"
                                            action="{{ route('employee.destroy', $employee) }}" method="POST"
                                            role="form">
                                            @method('DELETE')
                                            <x-button-icon btn="btn-danger" icon="bi-trash-fill" title="Eliminar"
                                                onclick="Eliminar({{ $employee->id }})" />
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
                    <x-pagination page="{{ $employees->currentPage() }}" lastPage="{{ $employees->lastPage() }}"
                        route="employee" perPage="{{ $employees->perPage() }}" total="{{ $employees->total() }}" />
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

        function Editar(employee) {
            $('#form-edit').show();
            $('#form-create').hide();
            $('#form-edit').attr('action', '/employees/' + employee.id);
            $('#form-edit').find('#document').val(employee.document);
            $('#form-edit').find('#name').val(employee.name);
            $('#form-edit').find('#lastname').val(employee.lastname);
            $('#form-edit').find('#transport_enterprise_id').val(employee.transport_enterprise_id);
            $('#form-edit').find('#name').focus();
        }
    </script>
@endpush
