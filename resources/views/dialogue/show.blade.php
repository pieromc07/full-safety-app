@extends('layouts.app')

@section('title', 'Dialogo Diario')
@section('page', 'Dialogo Diario')

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
                    <h3 class="card-title">{{ $dialogue->topic }}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-lg-3">
                            <x-input id="date" name="date" label="Fecha" class="form-control" placeholder="Fecha"
                                required="required" autofocus="autofocus" icon="bi-calendar" value="{{ $dialogue->date }}"
                                readonly />
                        </div>
                        <div class="col-12 col-lg-3 mb-3">
                            <x-input id="hour" name="hour" label="Hora" class="form-control" placeholder="Hora"
                                required="required" autofocus="autofocus" icon="bi-clock" value="{{ $dialogue->hour }}"
                                readonly />
                        </div>
                        <div class="col-12 col-lg-3">
                            <x-input id="checkpoint" name="checkpoint" label="Punto de Control" class="form-control"
                                placeholder="Punto de Control" required="required" autofocus="autofocus" icon="bi-geo-alt"
                                value="{{ $dialogue->checkpoint->name }}" readonly />
                        </div>
                        <div class="col-12 col-lg-3">
                            <x-input id="enterprise" name="enterprise" label="Empresa Transportista" class="form-control"
                                placeholder="Empresa Transportista" required="required" autofocus="autofocus"
                                icon="bi-building" value="{{ $dialogue->enterpriseTransport->name }}" readonly />
                        </div>
                        <div class="col-12 col-lg-3">
                            <x-input id="topic" name="topic" label="Tema" class="form-control" placeholder="Tema"
                                required="required" autofocus="autofocus" icon="bi-person" value="{{ $dialogue->topic }}"
                                readonly />
                        </div>
                        <div class="col-3 col-lg-3">
                            <x-input id="participants" name="participants" label="participants" class="form-control"
                                placeholder="participantes" required="required" autofocus="autofocus" icon="bi-truck"
                                value="{{ $dialogue->participants }}" readonly />
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
                                <img src="{{ asset('storage/' . $dialogue->photo_one) }}" class="img-fluid" id="photo_one">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="row">
                                <div class="col-12">
                                    <h5>Foto 2</h5>
                                </div>
                                <img src="{{ asset('storage/' . $dialogue->photo_two) }}" class="img-fluid" id="photo_two">
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
