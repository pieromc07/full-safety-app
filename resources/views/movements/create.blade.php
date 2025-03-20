@extends('layouts.app')

@section('title', 'Controlador')
@section('page', 'Crear Movimiento de Unidades')

@push('styles')
@endpush

@section('content')
    {{-- Si tiene algun error de validacion mostarar aqui --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible">
            <div class="d-flex align-items-center">
                <div class="font-weight-bold">{{ __('¡Ups! Algo salió mal.') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <div class="mt-2">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
    <x-form class="card" id="form-create" action="{{ route('unitmovements.store') }}" method="POST" role="form">
        <div class="card-body">
            <div class="row align-items-center g-3 mb-3">
                <div class="col-12 col-md-3">
                    <x-input id="date" label="Fecha" placeholder="Ingrese la fecha" type="datetime-local" required
                        icon="bi-calendar" name="date" />
                </div>
                <div class="col-12 col-md-3">
                    <x-input id="time_arrival" name="time_arrival" label=" Hora de Llegada"
                        placeholder="Ingrese la hora de llegada" type="time" icon="bi-clock" />
                </div>
                <div class="col-12 col-md-3">
                    <x-input id="time_departure" name="time_departure" label=" Hora de Salida"
                        placeholder="Ingrese la hora de salida" type="time" icon="bi-clock" />
                </div>
                <div class="col-12 col-md-3">
                    <x-select id="id_checkpoints" label="Punto de Control" placeholder="Seleccione un punto de control"
                        name="id_checkpoints">
                        <x-slot name="options">
                            @foreach ($checkpoints as $checkpoint)
                                <option value="{{ $checkpoint->id_checkpoints }}">{{ $checkpoint->name }}</option>
                            @endforeach
                        </x-slot>
                    </x-select>
                </div>
                <div class="col-12 col-md-3">
                    <x-select id="convoy" label="N° de Convoy" placeholder="N° de Convoy" name="convoy">
                        <x-slot name="options">
                            <option value="1">1ro</option>
                            <option value="2">2do</option>
                            <option value="3">3ro</option>
                            <option value="4">4to</option>
                            <option value="5">5to</option>
                            <option value="6">6to</option>
                            <option value="7">7mo</option>
                            <option value="8">8vo</option>
                        </x-slot>
                    </x-select>
                </div>

                <div class="col-12 col-md-2">
                    <x-input id="heavy_vehicle" name="heavy_vehicle" label="N° Pesado" placeholder="N° Pesado"
                        type="number" max="9" min="0" value="0" icon="bi-truck" />
                </div>
                <div class="col-12 col-md-2">
                    <x-input id="light_vehicle" name="light_vehicle" label="N° Liviano" placeholder="N° Liviano"
                        type="number" max="3" min="0" value="0" icon="bi-car-front-fill" />
                </div>
                <div class="col-12 col-md-2">
                    <div class="row d-flex justify-content-center align-items-center">
                        <label for="direction" class="col-12 col
                    -form-label">Dirección</label>
                        <div class="col-4 mt-2">
                            <div class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input" type="radio" value="1" id="direction-up"
                                    name="direction" required />
                                <label class="form-check-label" for="direction-up">
                                    Subida
                                </label>
                            </div>

                        </div>
                        <div class="col-4 mt-2">
                            <div class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input" type="radio" value="2" id="direction-down"
                                    name="direction" required />
                                <label class="form-check-label" for="direction-down">
                                    Bajada
                                </label>
                            </div>

                        </div>
                    </div>
                    @error('direction')
                        <div class="invalid-feedback" role="alert">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                {{-- convoy_state --}}
                <div class="col-12 col-md-3">
                    <x-select id="convoy_state" label="Estado de Convoy" placeholder="Seleccione un estado de convoy"
                        name="convoy_state">
                        <x-slot name="options">
                            <option value="1">Cargado</option>
                            <option value="2">Vacio</option>
                        </x-slot>
                    </x-select>
                </div>
                <div class="col-md-3">
                    <x-select id="id_supplier_enterprises" name="id_supplier_enterprises" label="Empresa Proveedora"
                        class="form-control" required="required" autofocus="autofocus"
                        placeholder="Seleccione una empresa">
                        <x-slot name="options">
                            @foreach ($supplierEnterprises as $supplierEnterprise)
                                <option value="{{ $supplierEnterprise->id_enterprises }}">
                                    {{ $supplierEnterprise->name }}
                                </option>
                            @endforeach
                        </x-slot>

                    </x-select>
                </div>
                <div class="col-md-3">
                    <x-select id="id_transport_enterprises" name="id_transport_enterprises"
                        label="Empresa Transportadora" class="form-control" required="required" autofocus="autofocus"
                        placeholder="Seleccione una empresa">
                        <x-slot name="options">
                        </x-slot>
                    </x-select>
                </div>
                <div class="col-md-3">
                    <x-select id="id_products" name="id_products" label="Producto" class="form-control"
                        required="required" autofocus="autofocus" placeholder="Seleccione un producto">
                        <x-slot name="options">
                        </x-slot>
                    </x-select>
                </div>
                <div class="col-12 col-md-2 d-flex justify-content-center align-items-center py-2">
                    <x-button type="button" id="btn-generate" btn="btn-primary" title="Generar" position="left"
                        text="Generar" icon="bi-arrow-repeat" />
                </div>
            </div>
            {{-- <div class="row align-items-center g-3">
                <div class="col-12"> --}}
            <div class="table-responsive">
                <x-table id="table-products" class="table table-striped">
                    <x-slot name="header">
                        <tr class="align-middle">
                            <th class="text-center">Producto</th>
                            <th class="text-center">Peso 1</th>
                            <th class="text-center">Producto 2</th>
                            <th class="text-center">Peso 2</th>
                            <th class="text-center">U.M.</th>
                            <th class="text-center">Guía de Remisión</th>
                        </tr>
                    </x-slot>
                    <x-slot name="slot">
                    </x-slot>
                </x-table>
            </div>
            {{--
                </div>
            </div> --}}
        </div>
        <div class="card-footer">
            <x-button type="submit" id="btn-store" btn="btn-primary" title="Registrar" position="left"
                text="Registrar" icon="bi-save" />

            <x-link-text-icon id="btn-back" btn="btn-secondary" title="Cerrar" position="left" text="Cerrar"
                icon="bi-x-circle" href="{{ route('unitmovements') }}" />
        </div>
    </x-form>
@endsection
@push('scripts')
    <script type="text/javascript">
        $(document).ready(() => {
            $('#id_supplier_enterprises').on('change', function() {
                let id_supplier_enterprises = $(this).val();
                $.ajax({
                    url: '/enterprises/' + id_supplier_enterprises,
                    type: 'GET',
                    success: function(data) {
                        $('#id_transport_enterprises').empty();
                        $('#id_products').empty();
                        $('#id_transport_enterprises').append(
                            `<option value="">Seleccione una empresa</option>`
                        );
                        $('#id_products').append(
                            `<option value="">Seleccione un producto</option>`
                        );
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

            $('#id_transport_enterprises').on('change', function() {
                let id_transport_enterprises = $(this).val();
                let id_supplier_enterprises = $('#id_supplier_enterprises').val();
                $.ajax({
                    url: '/productenterprises/' + id_supplier_enterprises + '/' +
                        id_transport_enterprises,
                    type: 'GET',
                    success: function(data) {
                        $('#id_products').empty();
                        $('#id_products').append(
                            `<option value="">Seleccione un producto</option>`
                        );
                        data.forEach(element => {
                            $('#id_products').append(
                                `<option value="${element.product.id_products}">${element.product.name}</option>`
                            );
                        });
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            });

            $('#btn-generate').on('click', function() {
                let id_products = $('#id_products').val();
                if (id_products == '') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Debe seleccionar un producto',
                    });
                    return;
                }

                $.ajax({
                    url: '/products/' + id_products,
                    type: 'GET',
                    success: function(data) {
                        $('#table-products tbody').empty();
                        let heavy_vehicle = $('#heavy_vehicle').val();
                        if (heavy_vehicle == 0) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Debe ingresar al menos un vehículo pesado',
                            });
                            return;
                        }
                        console.log(data);

                        for (let i = 0; i < heavy_vehicle; i++) {
                            $('#table-products tbody').append(`
                              <tr class="text-center align-middle">
                                  <td>
                                      <img src="/assets/media/resources/units/${data.product.id_unit_types}.png"
                                          alt="${data.product.name}" class="img-thumbnail"
                                          style="max-width: 80px; height: auto;">
                                      <span class="mt-1">${data.product.name}</span>
                                  </td>
                                  <td>
                                      <input type="number" class="form-control form-control-sm" name="weight[]"
                                            id="weight${i}" placeholder="Peso" required style="width: 100px;">
                                  </td>
                                  <td>
                                      <select name="id_products_two[]" id="id_products_two${i}"
                                              class="form-control form-control-sm" style="width: 150px;">
                                          <option value="">Seleccione un producto</option>
                                          ${data.products.map(item =>
                                              `<option value="${item.product.id_products}">${item.product.name}</option>`
                                          ).join('')}
                                      </select>
                                  </td>
                                  <td>
                                      <input type="number" class="form-control form-control-sm" name="weight_two[]"
                                            id="weight_two${i}" placeholder="Peso" style="width: 100px;">
                                  </td>
                                  <td>
                                      <select name="id_units[]" id="id_units${i}"
                                              class="form-control form-control-sm" required style="width: 150px;">
                                          <option value="">Seleccione una unidad</option>
                                          ${data.units.map(unit =>
                                              `<option value="${unit.id_units}">${unit.abbreviation}</option>`
                                          ).join('')}
                                      </select>
                                  </td>
                                  <td>
                                      <input type="text" class="form-control form-control-sm" name="guide[]"
                                            id="guide${i}" placeholder="Guia de Remisión" style="width: 150px;">
                                  </td>
                              </tr>
                          `);

                        }

                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            });

        });
    </script>
@endpush
