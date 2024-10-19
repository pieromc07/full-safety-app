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
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Inspección</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-lg-3">
                            <x-input id="date" name="date" label="Fecha" class="form-control" placeholder="Fecha"
                                required="required" autofocus="autofocus" icon="bi-calendar" value="{{ $inspection->date }}"
                                readonly />
                        </div>
                        <div class="col-12 col-lg-3 mb-3">
                            <x-input id="hour" name="hour" label="Hora" class="form-control" placeholder="Hora"
                                required="required" autofocus="autofocus" icon="bi-clock" value="{{ $inspection->hour }}"
                                readonly />
                        </div>
                        <div class="col-12 col-lg-3">
                            <x-input id="checkpoint" name="checkpoint" label="Punto de Control" class="form-control"
                                placeholder="Punto de Control" required="required" autofocus="autofocus" icon="bi-geo-alt"
                                value="{{ $inspection->checkpoint->name }}" readonly />
                        </div>
                        <div class="col-12 col-lg-3">
                            <x-input id="enterprise" name="enterprise" label="Empresa Transportista" class="form-control"
                                placeholder="Empresa Transportista" required="required" autofocus="autofocus"
                                icon="bi-building" value="{{ $inspection->enterprise->name }}" readonly />
                        </div>
                        <div class="col-12 col-lg-3">
                            <x-input id="targeted" name="targeted" label="Dirigido" class="form-control"
                                placeholder="Dirigido" required="required" autofocus="autofocus" icon="bi-person"
                                value="{{ $inspection->targeted->name }}" readonly />
                        </div>
                        <div class="col-3 col-lg-3">
                            <x-input id="convoy" name="convoy" label="Convoy" class="form-control" placeholder="Convoy"
                                required="required" autofocus="autofocus" icon="bi-truck"
                                value="{{ $inspection->convoy->convoy }}" readonly />
                        </div>
                        <div class="col-3 col-lg-3">
                            <x-input id="convoy_status" name="convoy_status" label="Estado" class="form-control"
                                placeholder="Estado del Convoy" required="required" autofocus="autofocus" icon="bi-truck"
                                value="{{ $inspection->convoy->convoy_status }}" readonly />
                        </div>
                        <div class="col-3 col-lg-3">
                            <x-input id="quantity_light_units" name="quantity_light_units"
                                label="Livianas" class="form-control"
                                placeholder="Cantidad de Unidades Livianas" required="required" autofocus="autofocus"
                                icon="bi-truck" value="{{ $inspection->convoy->quantity_light_units }}" readonly />
                        </div>
                        <div class="col-3 col-lg-3">
                            <x-input id="quantity_heavy_units" name="quantity_heavy_units"
                                label="Pesadas" class="form-control"
                                placeholder="Cantidad de Unidades Pesadas" required="required" autofocus="autofocus"
                                icon="bi-truck" value="{{ $inspection->convoy->quantity_heavy_units }}" readonly />
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
                                                        <x-button-icon btn="btn-primary" icon="bi-eye-fill"
                                                            title="Ver" onclick="Ver({{ $evidence }})" />
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
            </div>
        </div>
    </div>

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
        function Ver(evidence) {
            const pathBase = '{{ asset('storage') }}';
            $('#evidence_one').attr('src', pathBase + '/' + evidence.evidence_one);
            $('#evidence_two').attr('src', pathBase + '/' + evidence.evidence_two);
            $('#modal-evidence').modal('show');

        }
    </script>
@endpush
