@extends('layouts.app')

@section('title', 'Registro')
@section('page', 'Producto')

@push('styles')
@endpush

@section('content')
    <div class="row">
        <div class="col-12 col-lg-6">
            <x-form class="card" id="form-create" action="{{ route('products.store') }}" method="POST" role="form">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-8">
                            <x-input id="name" name="name" label="Nombre" class="form-control" placeholder="Nombre"
                                required="required" autofocus="autofocus" icon="bi-building" />
                        </div>
                        <div class="col-12 col-md-4">
                            <x-input id="number_onu" name="number_onu" label="Número ONU" class="form-control"
                                placeholder="Número ONU" required="required" autofocus="autofocus" icon="bi-123" />
                        </div>
                        <div class="col-12 col-md-12">
                            <div class="row d-flex justify-content-center align-items-center">
                                <div class="col-12 col-md-8">
                                    <x-select id="id_unit_types" name="id_unit_types" label="Tipo de Unidad"
                                        class="form-control" required="required" autofocus="autofocus" icon="bi-unit"
                                        placeholder="Tipo de Unidad">
                                        <x-slot name="options">
                                            @foreach ($unit_types as $unit_type)
                                                <option value="{{ $unit_type->id_unit_types }}">{{ $unit_type->name }}
                                                </option>
                                            @endforeach
                                        </x-slot>
                                    </x-select>
                                </div>
                                <div class="col-12 col-md-4">
                                    <img src="{{ asset('assets/media/resources/no-image.png') }}" alt="No Image"
                                        class="img-fluid" style="width: 80px;;" id="unit-image">
                                </div>
                            </div>


                        </div>
                        <div class="col-12 col-md-12">
                            <x-select id="parent_id" name="parent_id" label="Clase ONU" class="form-control"
                                required="required" autofocus="autofocus" icon="bi-unit" placeholder="Placa DOT">
                                <x-slot name="options">
                                    @foreach ($parents as $parent)
                                        <option value="{{ $parent->id_product_types }}">{{ $parent->name }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                        </div>
                        <div class="col-12 col-md-12">
                            <x-select id="id_product_types" name="id_product_types" label="Clase ONU" class="form-control"
                                required="required" autofocus="autofocus" icon="bi-unit" placeholder="Sub Placa">
                                <x-slot name="options">
                                </x-slot>
                            </x-select>
                        </div>
                        <div class="col-12 col-md-12">
                            <div class="row d-flex justify-content-center align-items-center">
                                <div class="col-12 col-md-4">
                                    <img src="{{ asset('assets/media/resources/diamante.png') }}" alt="Diamante"
                                        class="img-fluid" style="width: 150px;">
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="row d-flex flex-column">
                                        <select name="health" id="health" class="form-select mb-2"
                                            style="background-color: #001781; color: white; width: 50%;">
                                            <option value="0">0</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                        </select>
                                        <select name="flammability" id="flammability" class="form-select mb-2"
                                            style="background-color: #DE0209; color: white; width: 50%;">
                                            <option value="0">0</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                        </select>
                                        <select name="reactivity" id="reactivity" class="form-select mb-2"
                                            style="background-color: #FDEE01; color: black; width: 50%;">
                                            <option value="0">0</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                        </select>
                                        <select name="special" id="special" class="form-select mb-2"
                                            style="background-color: #ffffff; color: black; width: 50%;">
                                            <option value="0">0</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <x-button id="btn-store" btn="btn-primary" title="Registrar" position="left"
                                text="Registrar" icon="bi-save" />
                            <x-link-text-icon id="btn-back" btn="btn-secondary" title="Cancelar" position="left"
                                text="Cancelar" icon="bi-x-circle" href="{{ route('products') }}" />
                        </div>
                    </div>
                </div>
            </x-form>

            <x-form class="card" id="form-edit" method="POST" role="form" style="display: none;">
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <x-input id="name" name="name" label="Nombre" class="form-control"
                                placeholder="Nombre" required="required" autofocus="autofocus" icon="bi-building" />
                        </div>
                        <div class="col-12 col-md-6">
                            <x-input id="number_onu" name="number_onu" label="Número ONU" class="form-control"
                                placeholder="Número ONU" required="required" autofocus="autofocus" icon="bi-123" />
                        </div>
                        <div class="col-12 col-md-12">
                            <div class="row d-flex justify-content-center align-items-center">
                                <div class="col-12 col-md-8">
                                    <x-select id="id_unit_types" name="id_unit_types" label="Tipo de Unidad"
                                        class="form-control" required="required" autofocus="autofocus" icon="bi-unit"
                                        placeholder="Tipo de Unidad">
                                        <x-slot name="options">
                                            @foreach ($unit_types as $unit_type)
                                                <option value="{{ $unit_type->id_unit_types }}">{{ $unit_type->name }}
                                                </option>
                                            @endforeach
                                        </x-slot>
                                    </x-select>
                                </div>
                                <div class="col-12 col-md-4">
                                    <img src="{{ asset('assets/media/resources/no-image.png') }}" alt="No Image"
                                        class="img-fluid" style="width: 80px;;" id="unit-image">
                                </div>
                            </div>


                        </div>
                        <div class="col-12 col-md-12">
                            <x-select id="parent_id" name="parent_id" label="Clase ONU" class="form-control"
                                required="required" autofocus="autofocus" icon="bi-unit" placeholder="Placa DOT">
                                <x-slot name="options">
                                    @foreach ($parents as $parent)
                                        <option value="{{ $parent->id_product_types }}">{{ $parent->name }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                        </div>
                        <div class="col-12 col-md-12">
                            <x-select id="id_product_types" name="id_product_types" label="Clase ONU"
                                class="form-control" required="required" autofocus="autofocus" icon="bi-unit"
                                placeholder="Sub Placa">
                                <x-slot name="options">
                                </x-slot>
                            </x-select>
                        </div>
                        <div class="col-12 col-md-12">
                            <div class="row d-flex justify-content-center align-items-center">
                                <div class="col-12 col-md-4">
                                    <img src="{{ asset('assets/media/resources/diamante.png') }}" alt="Diamante"
                                        class="img-fluid" style="width: 150px;">
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="row d-flex flex-column">
                                        <select name="health" id="health" class="form-select mb-2"
                                            style="background-color: #001781; color: white; width: 50%;">
                                            <option value="0">0</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                        </select>
                                        <select name="flammability" id="flammability" class="form-select mb-2"
                                            style="background-color: #DE0209; color: white; width: 50%;">
                                            <option value="0">0</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                        </select>
                                        <select name="reactivity" id="reactivity" class="form-select mb-2"
                                            style="background-color: #FDEE01; color: black; width: 50%;">
                                            <option value="0">0</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                        </select>
                                        <select name="special" id="special" class="form-select mb-2"
                                            style="background-color: #ffffff; color: black; width: 50%;">
                                            <option value="0">0</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <x-button id="btn-update" btn="btn-primary" title="Actualizar" position="left" text="Actualizar"
                        icon="bi-save" type="submit" />

                    <x-link-text-icon id="btn-back" btn="btn-secondary" title="Cancelar" position="left"
                        text="Cancelar" icon="bi-x-circle" href="{{ route('products') }}" />
                </div>
            </x-form>

        </div>
        <div class="col-12 col-lg-6">
            <div class="table-responsive">
                <x-table id="table-products">
                    <x-slot name="header">
                        <th colspan="1" class="text-center">ID</th>
                        <th colspan="1" class="text-center">Nombre</th>
                        <th colspan="1" class="text-center">T. Unidad</th>
                        <th colspan="1" class="text-center">Clase ONU</th>
                        <th colspan="1" class="text-center">Diamante</th>
                        <th colspan="1" class="text-center">Placa DOT</th>
                        <th colspan="1" class="text-center">Acciones</th>
                    </x-slot>
                    <x-slot name='slot'>
                        @if ($products->isEmpty())
                            <tr class="text-center fs-5">
                                <td colspan="4">No hay registros</td>
                            </tr>
                        @else
                            @foreach ($products as $key => $product)
                                <tr class="text-center fs-5 align-middle">
                                    <td class="text-center">
                                        {{ $product->id_products }}
                                    </td>
                                    <td class="text-center">
                                        {{ $product->name }}
                                    </td>
                                    <td class="text-center">
                                        {{ $product->unitType->name }}
                                    </td>
                                    <td class="text-center">
                                        {{ $product->productType->parent->name }}
                                    </td>
                                    <td class="text-center">
                                        <div class="row d-flex justify-content-center align-items-center">
                                            <span class="badge bg-primary p-2"
                                                style="width: 20px; height: 20px; border-radius: 50%;">
                                                {{ $product->health }}
                                            </span>
                                            <span class="badge bg-danger p-2"
                                                style="width: 20px; height: 20px; border-radius: 50%;">
                                                {{ $product->flammability }}
                                            </span>
                                            <span class="badge bg-warning p-2"
                                                style="width: 20px; height: 20px; border-radius: 50%;">
                                                {{ $product->reactivity }}
                                            </span>
                                            <span class="badge bg-light p-2"
                                                style="width: 20px; height: 20px; border-radius: 50%;">
                                                {{ $product->special }}
                                            </span>

                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <img src="{{ asset('assets/media/resources/placa') }}/{{ $product->productType->code }}.png"
                                            alt="Placa DOT" class="img-fluid" style="width: 50px;">
                                    </td>
                                    <td class="text-center">
                                        {{-- <x-button-icon btn="btn-info" icon="bi-eye-fill" title="Ver" onclick="" /> --}}
                                        <x-button-icon btn="btn-warning" icon="bi-pencil-square" title="Editar"
                                            onclick="Editar({{ $product }}, {{ $product->productType->parent }})" />
                                        <x-form-table id="form-delete-{{ $product->id_products }}"
                                            action="{{ route('products.destroy', $product->id_products) }}"
                                            method="POST" role="form">
                                            @method('DELETE')
                                            <x-button-icon btn="btn-danger" icon="bi-trash-fill" title="Eliminar"
                                                type="button" onclick="Eliminar({{ $product->id_products }})" />
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
                    <x-pagination page="{{ $products->currentPage() }}" lastPage="{{ $products->lastPage() }}"
                        route="products" perPage="{{ $products->perPage() }}" total="{{ $products->total() }}" />
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

            $('#parent_id').on('change', function() {
                let id = $(this).val();
                $.ajax({
                    url: '/productstypes/parent/' + id,
                    type: 'GET',
                    success: function(response) {
                        $('#id_product_types').empty();
                        $('#id_product_types').append(
                            '<option value="">Seleccione una opción</option>');
                        response.forEach(element => {
                            $('#id_product_types').append('<option value="' + element
                                .id_product_types + '">' +
                                element.name + '</option>');
                        });
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            });

            $('#id_unit_types').on('change', function() {
                let id = $(this).val();
                if (id == "") {
                    $('#unit-image').attr('src', "{{ asset('assets/media/resources/no-image.png') }}");
                    return;
                }
                const path = "{{ asset('assets/media/resources/') }}";
                $('#unit-image').attr('src', path + '/units/' + id + '.png');
            });
        });

        function Editar(product, parent) {
            $('#form-edit').show();
            $('#form-create').hide();
            $('#form-edit').attr('action', '/products/' + product.id_products);
            $('#form-edit').find('#name').val(product.name);
            $('#form-edit').find('#number_onu').val(product.number_onu);
            $('#form-edit').find('#id_unit_types').val(product.id_unit_types).trigger('change');
            $('#form-edit').find('#parent_id').val(parent.id_product_types);
            $('#form-edit').find('#health').val(product.health).trigger('change');
            $('#form-edit').find('#flammability').val(product.flammability).trigger('change');
            $('#form-edit').find('#reactivity').val(product.reactivity).trigger('change');
            $('#form-edit').find('#special').val(product.special).trigger('change');
            $('#form-edit').find('#name').focus();
            // lanzar evento para cargar las sub placas
            cargarSubPlacas(parent.id_product_types, product.id_product_types);

        }

        function cargarSubPlacas(parent_id, id_product_types) {
            $.ajax({
                url: '/productstypes/parent/' + parent_id,
                type: 'GET',
                success: function(response) {
                    console.log(response);

                    $('#form-edit').find('#id_product_types').empty();
                    $('#form-edit').find('#id_product_types').append(
                        '<option value="">Seleccione una opción</option>');
                    response.forEach(element => {
                        $('#form-edit').find('#id_product_types').append('<option value="' + element
                            .id_product_types + '">' +
                            element.name + '</option>');
                    });
                    $('#form-edit').find('#id_product_types').val(id_product_types).trigger('change');
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
                    $('#form-delete-' + id).submit();
                }
            });
        }
    </script>
@endpush
