@extends('layouts.app')

@section('title', 'Mantenimiento')
@section('page', 'Empresa del Sistema')

@push('styles')
@endpush

@section('content')
    <div class="row">
        <div class="col-12 col-lg-5">
            <x-form class="card" id="form-create" action="{{ route('company.store') }}" method="POST" role="form"
                enctype="multipart/form-data">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-5">
                            <x-input id="ruc" name="ruc" label="RUC" class="form-control" placeholder="RUC (11 dígitos)"
                                required="required" autofocus="autofocus" icon="bi-hash" />
                        </div>
                        <div class="col-12 col-md-7">
                            <x-input id="name" name="name" label="Razón Social" class="form-control"
                                placeholder="Razón Social" required="required" icon="bi-building" />
                        </div>
                        <div class="col-12">
                            <x-input id="commercial_name" name="commercial_name" label="Nombre Comercial"
                                class="form-control" placeholder="Nombre Comercial" req="0" icon="bi-shop" />
                        </div>
                        <div class="col-12 col-md-6">
                            <x-input id="email" name="email" label="Email" class="form-control"
                                placeholder="empresa@ejemplo.com" req="0" icon="bi-envelope" />
                        </div>
                        <div class="col-12 col-md-6">
                            <x-input id="phone" name="phone" label="Teléfono" class="form-control"
                                placeholder="01-1234567" req="0" icon="bi-telephone" />
                        </div>
                        <div class="col-12">
                            <x-input id="address" name="address" label="Dirección" class="form-control"
                                placeholder="Dirección fiscal" req="0" icon="bi-geo-alt" />
                        </div>
                        <div class="col-12 col-md-6">
                            <x-input id="website" name="website" label="Sitio Web" class="form-control"
                                placeholder="www.ejemplo.com" req="0" icon="bi-globe" />
                        </div>
                        <div class="col-12 col-md-6">
                            <x-input id="representative" name="representative" label="Representante Legal"
                                class="form-control" placeholder="Nombre completo" req="0" icon="bi-person-badge" />
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label" for="logo">Logo</label>
                                <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <x-button id="btn-store" btn="btn-primary" title="Registrar" position="left" text="Registrar"
                        icon="bi-save" />
                    <x-link-text-icon id="btn-back" btn="btn-secondary" title="Cancelar" position="left" text="Cancelar"
                        icon="bi-x-circle" href="{{ route('company') }}" />
                </div>
            </x-form>

            <x-form class="card" id="form-edit" method="POST" role="form" style="display: none;"
                enctype="multipart/form-data">
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-5">
                            <x-input id="edit-ruc" name="ruc" label="RUC" class="form-control"
                                placeholder="RUC (11 dígitos)" required="required" icon="bi-hash" />
                        </div>
                        <div class="col-12 col-md-7">
                            <x-input id="edit-name" name="name" label="Razón Social" class="form-control"
                                placeholder="Razón Social" required="required" icon="bi-building" />
                        </div>
                        <div class="col-12">
                            <x-input id="edit-commercial_name" name="commercial_name" label="Nombre Comercial"
                                class="form-control" placeholder="Nombre Comercial" req="0" icon="bi-shop" />
                        </div>
                        <div class="col-12 col-md-6">
                            <x-input id="edit-email" name="email" label="Email" class="form-control"
                                placeholder="empresa@ejemplo.com" req="0" icon="bi-envelope" />
                        </div>
                        <div class="col-12 col-md-6">
                            <x-input id="edit-phone" name="phone" label="Teléfono" class="form-control"
                                placeholder="01-1234567" req="0" icon="bi-telephone" />
                        </div>
                        <div class="col-12">
                            <x-input id="edit-address" name="address" label="Dirección" class="form-control"
                                placeholder="Dirección fiscal" req="0" icon="bi-geo-alt" />
                        </div>
                        <div class="col-12 col-md-6">
                            <x-input id="edit-website" name="website" label="Sitio Web" class="form-control"
                                placeholder="www.ejemplo.com" req="0" icon="bi-globe" />
                        </div>
                        <div class="col-12 col-md-6">
                            <x-input id="edit-representative" name="representative" label="Representante Legal"
                                class="form-control" placeholder="Nombre completo" req="0" icon="bi-person-badge" />
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label" for="edit-logo">Logo (dejar vacío para mantener actual)</label>
                                <input type="file" class="form-control" id="edit-logo" name="logo" accept="image/*">
                            </div>
                            <div id="edit-logo-preview" class="mb-3" style="display: none;">
                                <label class="form-label">Logo actual:</label><br>
                                <img id="edit-logo-img" src="" alt="Logo" style="max-height: 60px;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <x-button id="btn-update" btn="btn-primary" title="Actualizar" position="left" text="Actualizar"
                        icon="bi-save" type="submit" />
                    <x-link-text-icon id="btn-back" btn="btn-secondary" title="Cancelar" position="left" text="Cancelar"
                        icon="bi-x-circle" href="{{ route('company') }}" />
                </div>
            </x-form>
        </div>
        <div class="col-12 col-lg-7">
            <div class="table-responsive">
                <x-table id="table-companies">
                    <x-slot name="header">
                        <th colspan="1" class="text-center">RUC</th>
                        <th colspan="1" class="text-center">Razón Social</th>
                        <th colspan="1" class="text-center">Email</th>
                        <th colspan="1" class="text-center">Teléfono</th>
                        <th colspan="1" class="text-center">Logo</th>
                        <th colspan="1" class="text-center">Acciones</th>
                    </x-slot>
                    <x-slot name='slot'>
                        @if ($companies->isEmpty())
                            <tr class="text-center fs-5">
                                <td colspan="6">No hay registros</td>
                            </tr>
                        @else
                            @foreach ($companies as $key => $company)
                                <tr class="text-center fs-5">
                                    <td class="text-center">{{ $company->ruc }}</td>
                                    <td class="text-start">{{ $company->name }}</td>
                                    <td class="text-center">{{ $company->email ?? '-' }}</td>
                                    <td class="text-center">{{ $company->phone ?? '-' }}</td>
                                    <td class="text-center">
                                        @if ($company->logo)
                                            <img src="{{ asset($company->logo) }}" alt="Logo" style="max-height: 40px;">
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <x-button-icon btn="btn-warning" icon="bi-pencil-square" title="Editar"
                                            onclick="Editar({{ $company }})" />
                                        <x-form-table id="form-delete-{{ $company->id_companies }}"
                                            action="{{ route('company.destroy', $company->id_companies) }}"
                                            method="POST" role="form">
                                            @method('DELETE')
                                            <x-button-icon btn="btn-danger" icon="bi-trash-fill" title="Eliminar"
                                                type="button"
                                                onclick="Eliminar({{ $company->id_companies }})" />
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
                    <x-pagination page="{{ $companies->currentPage() }}" lastPage="{{ $companies->lastPage() }}"
                        route="company" perPage="{{ $companies->perPage() }}"
                        total="{{ $companies->total() }}" />
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

        function Editar(company) {
            $('#form-edit').show();
            $('#form-create').hide();
            $('#form-edit').attr('action', '/companies/' + company.id_companies);
            $('#form-edit').find('#edit-ruc').val(company.ruc);
            $('#form-edit').find('#edit-name').val(company.name);
            $('#form-edit').find('#edit-commercial_name').val(company.commercial_name);
            $('#form-edit').find('#edit-email').val(company.email);
            $('#form-edit').find('#edit-phone').val(company.phone);
            $('#form-edit').find('#edit-address').val(company.address);
            $('#form-edit').find('#edit-website').val(company.website);
            $('#form-edit').find('#edit-representative').val(company.representative);
            if (company.logo) {
                $('#edit-logo-preview').show();
                $('#edit-logo-img').attr('src', '/' + company.logo);
            } else {
                $('#edit-logo-preview').hide();
            }
            $('#form-edit').find('#edit-ruc').focus();
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
