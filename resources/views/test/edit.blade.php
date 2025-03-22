@extends('layouts.app')

@section('title', 'Prueba de Alcohol')
@section('page', 'Prueba de Alcohol')

@push('styles')
    {{-- estilos para las imagenes --}}
    <style>
        #photo_one,
        #photo_two {
            width: 300px;
        }
    </style>
@endpush

@section('content')
    {{ Form::open([
        'route' => ['tests.update', $test->id_alcohol_tests],
        'method' => 'PUT',
        'files' => true,
        'id' => 'form-tests',
    ]) }}
    @csrf
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-4 col-lg-3">
                            <x-input id="date" name="date" label="Fecha" class="form-control" placeholder="Fecha"
                                required="required" autofocus="autofocus" icon="bi-calendar" value="{{ $test->date }}"
                                type="date" />
                        </div>
                        <div class="col-12 col-sm-12 col-md-4 col-lg-3 mb-3">
                            <x-input id="hour" name="hour" label="Hora" class="form-control" placeholder="Hora"
                                required="required" autofocus="autofocus" icon="bi-clock" value="{{ $test->hour }}"
                                type="time" />
                        </div>
                        <div class="col-12 col-sm-12 col-md-4 col-lg-3">
                            <x-select id="id_checkpoints" name="id_checkpoints" label="Punto de Control"
                                class="form-control" req={{ true }} autofocus="autofocus" icon="bi-geo-alt"
                                value="{{ $test->id_checkpoints }}" placeholder="Seleccione un Punto de Control">
                                <x-slot name="options">
                                    @foreach ($checkpoints as $checkpoint)
                                        <option value="{{ $checkpoint->id_checkpoints }}"
                                            {{ $test->id_checkpoints == $checkpoint->id_checkpoints ? 'selected' : '' }}>
                                            {{ $checkpoint->name }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                        </div>
                        <div class="col-12 col-sm-12 col-md-4 col-lg-3">
                            <x-select id="id_supplier_enterprises" name="id_supplier_enterprises" label="Empresa Proveedora"
                                class="form-control" req={{ true }} autofocus="autofocus" icon="bi-building"
                                value="{{ $test->id_supplier_enterprises }}"
                                placeholder="Seleccione una Empresa Proveedora">
                                <x-slot name="options">
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id_enterprises }}"
                                            {{ $test->id_supplier_enterprises == $supplier->id_enterprises ? 'selected' : '' }}>
                                            {{ $supplier->name }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                        </div>
                        <div class="col-12 col-sm-12 col-md-4 col-lg-3">
                            <x-select id="id_transport_enterprises" name="id_transport_enterprises"
                                label="Empresa Transportista" class="form-control" req={{ true }}
                                autofocus="autofocus" icon="bi-building" value="{{ $test->id_transport_enterprises }}"
                                placeholder="Seleccione una Empresa Transportista">
                                <x-slot name="options">
                                    @foreach ($transports as $transport)
                                        <option value="{{ $transport->id_enterprises }}"
                                            {{ $test->id_transport_enterprises == $transport->id_enterprises ? 'selected' : '' }}>
                                            {{ $transport->name }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                        </div>
                        <div class="col-3 col-lg-3">
                            <x-select id="id_employees" name="id_employees" label="Personal" class="form-control"
                                req={{ true }} autofocus="autofocus" icon="bi-building"
                                value="{{ $test->id_employees }}" placeholder="Seleccione una Personal">
                                <x-slot name="options">
                                    @foreach ($employees as $employee)
                                        <option value="{{ $employee->id_employees }}"
                                            {{ $test->id_employees == $employee->id_employees ? 'selected' : '' }}>
                                            {{ $employee->fullname }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                        </div>
                        <div class="col-3 col-lg-3">
                            <x-input id="result" name="result" label="Resultado %" class="form-control" min="0" step="0.01"
                                placeholder="Resultado %" required="required" autofocus="autofocus" icon="bi-truck"
                                value="{{ $test->result }}" type="number" />
                        </div>
                        <div class="col-3 col-lg-3">
                            <x-input id="state" name="state" label="Estado" class="form-control" placeholder="Estado"
                                required="required" autofocus="autofocus" icon="bi-truck" :value="$test->state === 1 ? 'POSITIVO' : 'CONFORME'"
                                readonly={{ true }} />
                        </div>
                    </div>
                    <div class="row justify-content-center mt-3">
                        <div class="col-10">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Evidencias</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row justify-content-center">
                                        <div class="col-6">
                                            <div class="row">
                                                <div class="col-12">
                                                    <h5>Foto 1</h5>
                                                </div>
                                                <img src="{{ asset($test->photo_one) }}" class="img-fluid"
                                                    id="photo_one">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="row">
                                                <div class="col-12">
                                                    <h5>Foto 2</h5>
                                                </div>
                                                <img src="{{ asset($test->photo_two) }}" class="img-fluid"
                                                    id="photo_two">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <x-button id="btn-store" btn="btn-primary" title="Actualizar" position="left" text="Actualizar"
                        icon="bi-save" type="submit" />
                    <x-link-text-icon id="btn-back" btn="btn-secondary" title="Cerrar" position="left" text="Cerrar"
                        icon="bi-x-circle" href="{{ route('tests') }}" />
                </div>
            </div>
        </div>
    </div>
    {{ Form::close() }}
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
        });
    </script>
@endpush
