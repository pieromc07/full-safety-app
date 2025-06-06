@extends('layouts.app')

@section('title', 'Control GPS')
@section('page', 'Control GPS')

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
                                required="required" autofocus="autofocus" icon="bi-calendar" value="{{ $control->date }}"
                                readonly={{ true }} />
                        </div>
                        <div class="col-12 col-sm-12 col-md-4 col-lg-3 mb-3">
                            <x-input id="hour" name="hour" label="Hora" class="form-control" placeholder="Hora"
                                required="required" autofocus="autofocus" icon="bi-clock" value="{{ $control->hour }}"
                                readonly={{ true }} />
                        </div>
                        <div class="col-12 col-sm-12 col-md-4 col-lg-3">
                            <x-select id="id_checkpoints" name="id_checkpoints" label="Punto de Control"
                                class="form-control" req={{ true }} autofocus="autofocus" icon="bi-geo-alt"
                                value="{{ $control->id_checkpoints }}" placeholder="Seleccione un Punto de Control"
                                disabled={{ true }}>
                                <x-slot name="options">
                                    @foreach ($checkpoints as $checkpoint)
                                        <option value="{{ $checkpoint->id_checkpoints }}"
                                            {{ $control->id_checkpoints == $checkpoint->id_checkpoints ? 'selected' : '' }}>
                                            {{ $checkpoint->name }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                        </div>
                        <div class="col-12 col-sm-12 col-md-4 col-lg-3">
                            <x-select id="id_supplier_enterprises" name="id_supplier_enterprises" label="Empresa Proveedora"
                                class="form-control" req={{ true }} autofocus="autofocus" icon="bi-building"
                                value="{{ $control->id_supplier_enterprises }}"
                                placeholder="Seleccione una Empresa Proveedora" disabled={{ true }}>
                                <x-slot name="options">
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id_enterprises }}"
                                            {{ $control->id_supplier_enterprises == $supplier->id_enterprises ? 'selected' : '' }}>
                                            {{ $supplier->name }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                        </div>
                        <div class="col-12 col-sm-12 col-md-4 col-lg-3">
                            <x-select id="id_transport_enterprises" name="id_transport_enterprises"
                                label="Empresa Transportista" class="form-control" req={{ true }}
                                autofocus="autofocus" icon="bi-building" value="{{ $control->id_transport_enterprises }}"
                                placeholder="Seleccione una Empresa Transportista" disabled={{ true }}>
                                <x-slot name="options">
                                    @foreach ($transports as $transport)
                                        <option value="{{ $transport->id_enterprises }}"
                                            {{ $control->id_transport_enterprises == $transport->id_enterprises ? 'selected' : '' }}>
                                            {{ $transport->name }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                        </div>
                        <div class="col-3 col-lg-3">
                            <x-select id="option" name="option" label="Opcion" class="form-control"
                                req={{ true }} autofocus="autofocus" icon="bi-building"
                                value="{{ $control->option }}" placeholder="Seleccione una Opcion"
                                disabled={{ true }}>
                                <x-slot name="options">
                                    <option value="">
                                        Seleccionar Opcion
                                    </option>
                                    <option value="1">
                                        VELOCIDAD
                                    </option>
                                    <option value="2">
                                        UBICACION
                                    </option>
                                </x-slot>
                            </x-select>
                        </div>
                        <div class="col-3 col-lg-3">
                            <x-select id="state" name="state" label="Estado" class="form-control"
                                req={{ true }} autofocus="autofocus" icon="bi-building"
                                value="{{ $control->state }}" placeholder="Seleccione una Estado"
                                disabled={{ true }}>
                                <x-slot name="options">
                                    <option value="">
                                        Seleccionar Estado
                                    </option>
                                    <option value="1">
                                        CONFORME
                                    </option>
                                    <option value="2">
                                        NO CONFORME
                                    </option>
                                    <option value="3">
                                        OPORTUNIDAD DE MEJORA
                                    </option>
                                </x-slot>
                            </x-select>
                        </div>
                        <div class="col-3 col-lg-3">
                          <x-textarea id="observation" name="observation" label="Observacion" class="form-control"
                              placeholder="Observacion" autofocus="autofocus" icon="bi-clock"
                              value="{{ $control->observation }}" uppercase={{true}} ></x-textarea>
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
                                                <img src="{{ asset($control->photo_one) }}" class="img-fluid"
                                                    id="photo_one">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="row">
                                                <div class="col-12">
                                                    <h5>Foto 2</h5>
                                                </div>
                                                <img src="{{ asset($control->photo_two) }}" class="img-fluid"
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
                    <x-link-text-icon id="btn-back" btn="btn-secondary" title="Cerrar" position="left" text="Cerrar"
                        icon="bi-x-circle" href="{{ route('controls') }}" />

                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script type="text/javascript"></script>
@endpush
