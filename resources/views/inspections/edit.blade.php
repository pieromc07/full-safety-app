@extends('layouts.app')

@section('title', 'Inspecciones')
@section('page', 'Operativa')

@push('styles')
    {{-- estilos para las imagenes --}}
    <style>
        #evidence_one,
        #evidence_two {
            width: 300px;
        }
    </style>
@endpush

@section('content')
    {{ Form::open([
        'route' => ['inspections.update', $inspection->id_inspections],
        'method' => 'PUT',
        'files' => true,
        'id' => 'form-inspections',
    ]) }}
    @csrf
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        Inspección {{ $inspection->inspectionType->name }} - {{ $inspection->folio }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-12 col-sm-12 col-md-3 col-lg-2">
                            <x-input id="date" name="date" label="Fecha" class="form-control" placeholder="Fecha"
                                req={{ true }} autofocus="autofocus" icon="bi-calendar"
                                value="{{ $inspection->date }}" type="date" />
                        </div>
                        <div class="col-12 col-sm-12 col-md-3 col-lg-2 mb-3">
                            <x-input id="hour" name="hour" label="Hora" class="form-control" placeholder="Hora"
                                req={{ true }} autofocus="autofocus" icon="bi-clock"
                                value="{{ $inspection->hour }}" type="time" />
                        </div>
                        <div class="col-12 col-sm-12 col-md-3 col-lg-2">
                            <x-select id="id_checkpoints" name="id_checkpoints" label="Punto de Control"
                                class="form-control" req={{ true }} autofocus="autofocus" icon="bi-geo-alt"
                                value="{{ $inspection->id_checkpoints }}" placeholder="Seleccione un Punto de Control">
                                <x-slot name="options">
                                    @foreach ($checkpoints as $checkpoint)
                                        <option value="{{ $checkpoint->id_checkpoints }}"
                                            {{ $inspection->id_checkpoints == $checkpoint->id_checkpoints ? 'selected' : '' }}>
                                            {{ $checkpoint->name }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                        </div>
                        <div class="col-12 col-sm-12 col-md-4 col-lg-3">
                            <x-select id="id_supplier_enterprises" name="id_supplier_enterprises" label="Empresa Proveedora"
                                class="form-control" req={{ true }} autofocus="autofocus" icon="bi-building"
                                value="{{ $inspection->id_supplier_enterprises }}"
                                placeholder="Seleccione una Empresa Proveedora">
                                <x-slot name="options">
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id_enterprises }}"
                                            {{ $inspection->id_supplier_enterprises == $supplier->id_enterprises ? 'selected' : '' }}>
                                            {{ $supplier->name }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                        </div>
                        <div class="col-12 col-sm-12 col-md-4 col-lg-3">
                            <x-select id="id_transport_enterprises" name="id_transport_enterprises"
                                label="Empresa Transportista" class="form-control" req={{ true }}
                                autofocus="autofocus" icon="bi-building"
                                value="{{ $inspection->id_transport_enterprises }}"
                                placeholder="Seleccione una Empresa Transportista">
                                <x-slot name="options">
                                    @foreach ($transports as $transport)
                                        <option value="{{ $transport->id_enterprises }}"
                                            {{ $inspection->id_transport_enterprises == $transport->id_enterprises ? 'selected' : '' }}>
                                            {{ $transport->name }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                        </div>
                        <div class="col-12 col-sm-12 col-md-4 col-lg-3">
                            <x-select id="id_targeteds" name="id_targeteds" label="Dirigido" class="form-control"
                                req={{ true }} autofocus="autofocus" icon="bi-person"
                                value="{{ $inspection->id_targeteds }}" placeholder="Seleccione un Dirigido">
                                <x-slot name="options">
                                    @foreach ($targeteds as $targeted)
                                        <option value="{{ $targeted->id_targeteds }}"
                                            {{ $inspection->id_targeteds == $targeted->id_targeteds ? 'selected' : '' }}>
                                            {{ $targeted->name }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>

                        </div>
                        <div class="col-3 col-sm-12 col-md-3 col-lg-1">
                            <x-input id="convoy" name="convoy" label="Convoy" class="form-control" placeholder="Convoy"
                                req={{ true }} autofocus="autofocus" icon="bi-truck"
                                value="{{ $inspection->convoy->convoy }}" />
                        </div>
                        <div class="col-3 col-sm-12 col-md-3 col-lg-2">
                            <x-select id="convoy_status" name="convoy_status" label="Estado" class="form-control"
                                req={{ true }} autofocus="autofocus" icon="bi-truck"
                                value="{{ $inspection->convoy->convoy_status == 'Bajada' ? 1 : 2 }}"
                                placeholder="Seleccione un Estado">
                                <x-slot name="options">
                                    <option value="1">
                                        Bajada</option>
                                    <option value="2">
                                        Subida</option>
                                </x-slot>
                            </x-select>
                        </div>
                        <div class="col-3 col-sm-12 col-md-3 col-lg-1">
                            <x-input id="quantity_light_units" name="quantity_light_units" label="Livianas"
                                class="form-control" placeholder="Cantidad de Unidades Livianas" req={{ true }}
                                autofocus="autofocus" icon="bi-truck"
                                value="{{ $inspection->convoy->quantity_light_units }}" />
                        </div>
                        <div class="col-3 col-sm-12 col-md-3 col-lg-1">
                            <x-input id="quantity_heavy_units" name="quantity_heavy_units" label="Pesadas"
                                class="form-control" placeholder="Cantidad de Unidades Pesadas" req={{ true }}
                                autofocus="autofocus" icon="bi-truck"
                                value="{{ $inspection->convoy->quantity_heavy_units }}" />
                        </div>
                        <div class="col-12 col-sm-12 col-md-3 col-lg-2">
                            <x-select id="id_products" name="id_products" label="Producto" class="form-control"
                                req={{ true }} autofocus="autofocus" icon="bi-box"
                                value="{{ $inspection->convoy->id_products }}" placeholder="Seleccione un Producto">
                                <x-slot name="options">
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id_products }}"
                                            {{ $inspection->convoy->id_products == $product->id_products ? 'selected' : '' }}>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                        </div>
                        <div class="col-12 col-sm-12 col-md-3 col-lg-2">
                            <x-select id="id_products_two" name="id_products_two" label="Producto 2"
                                class="form-control" req={{ true }} autofocus="autofocus" icon="bi-box"
                                value="{{ $inspection->convoy->id_products_two }}"
                                placeholder="Seleccione un Producto 2">
                                <x-slot name="options">
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id_products }}"
                                            {{ $inspection->convoy->id_products_two == $product->id_products ? 'selected' : '' }}>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <x-table id="table-evidences">
                                    <x-slot name="header">
                                        <tr>
                                            <th>N°</th>
                                            <th>Evidencia</th>
                                            <th>Condicion</th>
                                            <th>Categoria</th>
                                            <th>Subcategoria</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </x-slot>
                                    <x-slot name="slot">
                                        @if ($inspection->evidences->isEmpty())
                                            <tr>
                                                <td colspan="3" class="text-center">No hay registros</td>
                                            </tr>
                                        @else
                                            @foreach ($inspection->evidences as $key => $evidence)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $evidence->evidence->name }}</td>
                                                    <td>
                                                        @if ($evidence->state == 1)
                                                            <span class="badge badge-success">Conforme</span>
                                                        @elseif ($evidence->state == 2)
                                                            <span class="badge badge-warning">No Conforme</span>
                                                        @else
                                                            <span class="badge badge-danger">Oportunidad de Mejora</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $evidence->evidence->category->name }}</td>
                                                    <td>{{ $evidence->evidence->subcategory->name }}</td>
                                                    <td>
                                                        <x-button-icon type="button" btn="btn-primary"
                                                            icon="bi-eye-fill" title="Ver"
                                                            onclick="Ver({{ $evidence }})" />
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </x-slot>
                                </x-table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <x-button id="btn-store" btn="btn-primary" title="Actualizar" position="left" text="Actualizar"
                        icon="bi-save" type="submit" />
                    <x-link-text-icon id="btn-back" btn="btn-secondary" title="Cancelar" position="left"
                        text="Cancelar" icon="bi-x-circle" href="{{ route('inspections') }}" />
                </div>
            </div>
        </div>
    </div>
    {{ Form::close() }}
    <div class="row">
        <x-modal id="modal-evidence" title="Evidencia" maxWidth="lg">
            <div class="row">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Evidencias</h3>
                    </div>
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-12">
                                        <h5>Evidencia 1</h5>
                                    </div>
                                    <img src="" class="img-fluid" id="evidence_one">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-12">
                                        <h5>Evidencia 2</h5>
                                    </div>
                                    <img src="" class="img-fluid" id="evidence_two">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </x-modal>
    </div>

@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $('#id_supplier_enterprises').on('change', function() {
                const id_supplier_enterprises = $(this).val();
                $.ajax({
                    url: "{{ url('enterprises') }}/" + id_supplier_enterprises,
                    id_supplier_enterprises,
                    type: 'GET',
                    success: function(data) {
                        $('#id_transport_enterprises').empty();
                        $('#id_transport_enterprises').append(
                            '<option value="">Seleccione una Empresa Transportista</option>'
                        );
                        $.each(data, function(index, enterprise) {
                            $('#id_transport_enterprises').append('<option value="' +
                                enterprise.id_enterprises + '">' + enterprise.name +
                                '</option>');
                        });
                    }
                });
            });

            $('#id_transport_enterprises').on('change', function() {
                const id_transport_enterprises = $(this).val();
                const id_supplier_enterprises = $('#id_supplier_enterprises').val();
                $.ajax({
                    url: "{{ url('enterprises') }}/" + id_supplier_enterprises + '/' +
                        id_transport_enterprises,
                    id_supplier_enterprises,
                    type: 'GET',
                    success: function(data) {
                        $('#id_products').empty();
                        $('#id_products').append(
                            '<option value="">Seleccione Producto</option>'
                        );
                        $.each(data, function(index, productEnterprise) {
                            $('#id_products').append('<option value="' +
                                productEnterprise.product.id_products + '">' +
                                productEnterprise.product.name +
                                '</option>');
                        });

                        $('#id_products_two').empty();
                        $('#id_products_two').append(
                            '<option value="">Seleccione Producto 2</option>'
                        );
                        $.each(data, function(index, productEnterprise) {
                            $('#id_products_two').append('<option value="' +
                                productEnterprise.product.id_products + '">' +
                                productEnterprise.product.name +
                                '</option>');
                        });

                    }
                });
            });

            $('#id_products_two').on('change', function() {
                const id_products = $('#id_products').val();
                const id_products_two = $(this).val();

                if (id_products == id_products_two) {
                    alertMessage('El Producto 1 no puede ser igual al Producto 2', 'warning');
                    return;
                }
            });

        });

        function Ver(evidence) {
            const pathBase = '{{ asset('') }}';
            $('#evidence_one').attr('src', pathBase + evidence.evidence_one);
            $('#evidence_two').attr('src', pathBase + evidence.evidence_two);
            $('#modal-evidence').modal('show');
        }
    </script>
@endpush
