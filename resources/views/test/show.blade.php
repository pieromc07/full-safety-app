@extends('layouts.app')

@section('title', 'Prueba de Alcohol')
@section('page', 'Prueba de Alcohol')

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
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    {{-- <h3 class="card-title">{{$test->topic}}</h3> --}}
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-lg-3">
                            <x-input id="date" name="date" label="Fecha" class="form-control" placeholder="Fecha"
                                required="required" autofocus="autofocus" icon="bi-calendar" value="{{ $test->date }}"
                                readonly />
                        </div>
                        <div class="col-12 col-lg-3 mb-3">
                            <x-input id="hour" name="hour" label="Hora" class="form-control" placeholder="Hora"
                                required="required" autofocus="autofocus" icon="bi-clock" value="{{ $test->hour }}"
                                readonly />
                        </div>
                        <div class="col-12 col-lg-3">
                            <x-input id="checkpoint" name="checkpoint" label="Punto de Control" class="form-control"
                                placeholder="Punto de Control" required="required" autofocus="autofocus" icon="bi-geo-alt"
                                value="{{ $test->checkpoint->name }}" readonly />
                        </div>
                        <div class="col-12 col-lg-3">
                            <x-input id="enterprise" name="enterprise" label="Empresa Transportista" class="form-control"
                                placeholder="Empresa Transportista" required="required" autofocus="autofocus"
                                icon="bi-building" value="{{ $test->enterpriseTransport->name }}" readonly />
                        </div>
                        <div class="col-3 col-lg-3">
                            <x-input id="employee" name="employee" label="Personal" class="form-control"
                                placeholder="Personal" required="required" autofocus="autofocus" icon="bi-truck"
                                value="{{ $test->employee->fullname }}" readonly />
                        </div>
                        <div class="col-3 col-lg-3">
                            <x-input id="result" name="result" label="Resultado" class="form-control"
                                placeholder="Resultado" required="required" autofocus="autofocus" icon="bi-truck"
                                value="{{ $test->result }}" readonly />
                        </div>
                        <div class="col-3 col-lg-3">
                            <x-input id="state" name="state" label="Estado" class="form-control" placeholder="Estado"
                                required="required" autofocus="autofocus" icon="bi-truck" :value="$test->state === 1 ? 'POSITIVO' : 'CONFORME'" readonly />
                        </div>
                    </div>
                </div>
            </div>
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
                                <img src="{{ asset($test->photo_one) }}" class="img-fluid" id="photo_one">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="row">
                                <div class="col-12">
                                    <h5>Foto 2</h5>
                                </div>
                                <img src="{{ asset($test->photo_two) }}" class="img-fluid" id="photo_two">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script type="text/javascript"></script>
@endpush
