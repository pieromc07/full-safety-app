@extends('layouts.app')

@section('title', 'Registro')
@section('page', 'Asignar Producto a Empresa')

@push('styles')
@endpush

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <x-form class="card" id="form-create" action="{{ route('productenterprises.store') }}" method="POST"
                    role="form">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <x-select id="id_supplier_enterprises" name="id_supplier_enterprises"
                                    label="Empresa Proveedora" class="form-control" required="required"
                                    autofocus="autofocus" placeholder="Seleccione una empresa">
                                    <x-slot name="options">
                                        @foreach ($supplierEnterprises as $supplierEnterprise)
                                            <option value="{{ $supplierEnterprise->id_enterprises }}">
                                                {{ $supplierEnterprise->name }}
                                            </option>
                                        @endforeach
                                    </x-slot>

                                </x-select>
                            </div>
                            <div class="col-md-6">
                                <x-select id="id_transport_enterprises" name="id_transport_enterprises"
                                    label="Empresa Transportadora" class="form-control" required="required"
                                    autofocus="autofocus" placeholder="Seleccione una empresa">
                                    <x-slot name="options">
                                    </x-slot>
                                </x-select>
                            </div>
                            <div class="col-md-6">
                                <x-select id="id_products" name="id_products" label="Producto" class="form-control"
                                    required="required" autofocus="autofocus" placeholder="Seleccione un producto">
                                    <x-slot name="options">
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id_products }}">
                                                {{ $product->name }}
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
                        <x-link-text-icon id="btn-back" btn="btn-secondary" title="Cancelar" position="left"
                            text="Cancelar" icon="bi-x-circle" href="{{ route('productenterprises') }}" />
                    </div>
                </x-form>

                <x-form class="card" id="form-edit" method="POST" role="form" style="display: none;">
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <x-select id="id_supplier_enterprises" name="id_supplier_enterprises"
                                    label="Empresa Proveedora" class="form-control" required="required"
                                    autofocus="autofocus" placeholder="Seleccione una empresa">
                                    <x-slot name="options">
                                        @foreach ($supplierEnterprises as $supplierEnterprise)
                                            <option value="{{ $supplierEnterprise->id_enterprises }}">
                                                {{ $supplierEnterprise->name }}
                                            </option>
                                        @endforeach
                                    </x-slot>

                                </x-select>
                            </div>
                            <div class="col-md-6">
                                <x-select id="id_transport_enterprises" name="id_transport_enterprises"
                                    label="Empresa Transportadora" class="form-control" required="required"
                                    autofocus="autofocus" placeholder="Seleccione una empresa">
                                    <x-slot name="options">
                                    </x-slot>
                                </x-select>
                            </div>
                            <div class="col-md-6">
                                <x-select id="id_products" name="id_products" label="Producto" class="form-control"
                                    required="required" autofocus="autofocus" placeholder="Seleccione un producto">
                                    <x-slot name="options">
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id_products }}">
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </x-slot>
                                </x-select>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <x-button id="btn-update" btn="btn-primary" title="Actualizar" position="left" text="Actualizar"
                            type="submit" icon="bi-save" />
                        <x-link-text-icon id="btn-back" btn="btn-secondary" title="Cancelar" position="left"
                            text="Cancelar" icon="bi-x-circle" href="{{ route('productenterprises') }}" />
                    </div>
                </x-form>
            </div>
            <div class="col-md-12">
                <x-table id="table" class="card" title="Listado de Productos Asignados">
                    <x-slot name="header">
                        <tr>
                            <th>Empresa Proveedora</th>
                            <th>Empresa Transportadora</th>
                            <th>Producto</th>
                            <th>Acciones</th>
                        </tr>
                    </x-slot>
                    <x-slot name="slot">
                        @foreach ($productEnterprises as $productEnterprise)
                            <tr>
                                <td>{{ $productEnterprise->supplierEnterprise->name }}</td>
                                <td>{{ $productEnterprise->transportEnterprise->name }}</td>
                                <td>{{ $productEnterprise->product->name }}</td>
                                <td>
                                    <x-button-icon btn="btn-warning" icon="bi-pencil-square" title="Editar"
                                        onclick="Editar({{ $productEnterprise }})" />
                                    <x-form-table id="form-delete-{{ $productEnterprise->id_product_enterprises }}"
                                        action="{{ route('productenterprises.destroy', $productEnterprise->id_product_enterprises) }}"
                                        method="POST" role="form">
                                        @method('DELETE')
                                        <x-button-icon btn="btn-danger" icon="bi-trash-fill" title="Eliminar"
                                            type="button"
                                            onclick="Eliminar({{ $productEnterprise->id_product_enterprises }})" />
                                    </x-form-table>
                                </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-table>
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

            $('#id_supplier_enterprises').on('change', function() {
                let id_supplier_enterprises = $(this).val();

                $.ajax({
                    url: '/enterprises/' + id_supplier_enterprises,
                    type: 'GET',
                    success: function(data) {
                        data.forEach(element => {
                            $('#id_transport_enterprises').append(
                                `<option value="${element.id_enterprises}">${element.name}</option>`
                            );
                        });
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            });
        });

        function Editar(productEnterprise) {
            $('#form-edit').show();
            $('#form-create').hide();

            $('#form-edit').attr('action', '/productenterprises/' + productEnterprise.id_product_enterprises);
            $('#form-edit').find('#id_supplier_enterprises').val(productEnterprise.id_supplier_enterprises).trigger(
                'change');
            $('#form-edit').find('#id_products').val(productEnterprise.id_products).trigger('change');
            cargarTransporte(productEnterprise.id_supplier_enterprises, productEnterprise.id_transport_enterprises);
        }

        function cargarTransporte(id_supplier_enterprises, id_transport_enterprises) {
            $.ajax({
                url: '/enterprises/' + id_supplier_enterprises,
                type: 'GET',
                success: function(data) {
                    data.forEach(element => {
                        $('#form-edit').find('#id_transport_enterprises').append(
                            `<option value="${element.id_enterprises}">${element.name}</option>`
                        );
                    });
                    $('#form-edit').find('#id_transport_enterprises').val(id_transport_enterprises).trigger(
                        'change');
                },
                error: function(error) {
                    console.log(error);
                }
            });
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
                    $(`#form-delete-${id}`).submit();
                }
            });
        }
    </script>
@endpush
