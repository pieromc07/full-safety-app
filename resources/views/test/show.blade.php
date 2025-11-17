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
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-4 col-lg-3">
                            <x-input id="date" name="date" label="Fecha" class="form-control" placeholder="Fecha"
                                required="required" autofocus="autofocus" icon="bi-calendar" value="{{ $test->date }}"
                                readonly={{ true }} />
                        </div>
                        <div class="col-12 col-sm-12 col-md-4 col-lg-3 mb-3">
                            <x-input id="hour" name="hour" label="Hora" class="form-control" placeholder="Hora"
                                required="required" autofocus="autofocus" icon="bi-clock" value="{{ $test->hour }}"
                                readonly={{ true }} />
                        </div>
                        <div class="col-12 col-sm-12 col-md-4 col-lg-3">
                            <x-select id="id_checkpoints" name="id_checkpoints" label="Punto de Control"
                                class="form-control" req={{ true }} autofocus="autofocus" icon="bi-geo-alt"
                                value="{{ $test->id_checkpoints }}" placeholder="Seleccione un Punto de Control"
                                disabled={{ true }}>
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
                                placeholder="Seleccione una Empresa Proveedora" disabled={{ true }}>
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
                                placeholder="Seleccione una Empresa Transportista" disabled={{ true }}>
                                <x-slot name="options">
                                    @foreach ($transports as $transport)
                                        <option value="{{ $transport->id_enterprises }}"
                                            {{ $test->id_transport_enterprises == $transport->id_enterprises ? 'selected' : '' }}>
                                            {{ $transport->name }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                        </div>
                        <!-- Campos personales y resultados movidos a detalles; se omiten aquí -->
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Detalles</h3>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Empleado</th>
                                                    <th>Resultado</th>
                                                    <th>Estado</th>
                                                    <th>Foto 1</th>
                                                    <th>Foto 2</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($test->details as $detail)
                                                    <tr>
                                                        <td>{{ $detail->employee->fullname ?? '-' }}</td>
                                                        <td>{{ $detail->result }}</td>
                                                        <td>{{ $detail->state === 1 ? 'POSITIVO' : 'CONFORME' }}</td>
                                                        <td>
                                                            @if ($detail->photo_one)
                                                                <img src="{{ asset($detail->photo_one) }}"
                                                                    width="100" />
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($detail->photo_two)
                                                                <img src="{{ asset($detail->photo_two) }}"
                                                                    width="100" />
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <x-link-text-icon id="btn-back" btn="btn-secondary" title="Cerrar" position="left" text="Cerrar"
                        icon="bi-x-circle" href="{{ route('tests') }}" />
                </div>
            </div>
        </div>
    </div>


@endsection

@push('scripts')
    <script type="text/javascript"></script>
@endpush
