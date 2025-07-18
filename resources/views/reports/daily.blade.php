@extends('layouts.app')

@section('title', 'Controlador')
@section('page', 'Reporte Diario')

@push('styles')
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">
            <x-table-filter id="table-filter" action="{{ route('report.daily') }}">
                <div class="col-md-3">
                    <x-select-filter id="checkpoints-filter" label="Punto de Control" name="id_checkpoints"
                        formId="table-filter" placeholder="Selecciona Punto" req="{{ false }}">
                        <x-slot name="options">
                            @foreach ($checkpoints as $checkpoints)
                                <option value="{{ $checkpoints->id_checkpoints }}"
                                    data-local-selected="{{ $checkpoints->id_checkpoints == request('id_checkpoints') ? 1 : $checkpoints->main }}">
                                    {{ $checkpoints->name }}
                                </option>
                            @endforeach
                        </x-slot>
                    </x-select-filter>
                </div>
                <div class="col-md-3 align-middle">
                    <x-date-range id="date-filter" label="Filtrar por fecha" name="rangeDate" formId="table-filter" />
                </div>
                <div class="col-md-4">
                    <x-select-filter id="id_transport_enterprises" label="Emp. Transportista"
                        name="id_transport_enterprises" formId="table-filter" placeholder="Emp. Transportista"
                        req="{{ false }}">
                        <x-slot name="options">
                            @foreach ($transportEnterprises as $transportEnterprise)
                                <option value="{{ $transportEnterprise->id_enterprises }}"
                                    data-local-selected="{{ $transportEnterprise->id_enterprises == request('id_transport_enterprises') ? 1 : 0 }}">
                                    {{ $transportEnterprise->name }}
                                </option>
                            @endforeach
                        </x-slot>
                    </x-select-filter>
                </div>
                <div class="col-md-2">
                    <x-link-text-icon id="btn-export-pdf" class="btn-primary" icon="bi-file-pdf-fill" text="Exportar PDF"
                        href="{{ route('report.daily.pdf', request()->all()) }}" target="_blank" />
                </div>
            </x-table-filter>
        </div>
    </div>
    <div class="row mt-6 mb-3 flex-column flex-md-row justify-content-center gap-5">
        {{-- Inspeccion Operativa / Documentaria --}}
        <div class="col-12 col-md-10 col-lg-10 col-xl-10">
            <div class="row">
                <div class="col-md-12 mb-3 text-center">
                    <span class="text-dark fw-bolder fs-3">
                        Inspecciones Operativas / Documentarias
                    </span>
                </div>
                <div class="col-12 mt-3">
                    <div class="table-responsive">
                        <x-table id="table-inspections">
                            <x-slot name="header">
                                <th colspan="1" class="text-center">ID</th>
                                <th colspan="1" class="text-center">Punto de Control</th>
                                <th colspan="1" class="text-center">Fecha</th>
                                <th colspan="1" class="text-center">Hora</th>
                                <th colspan="1" class="text-center">Tipo</th>
                                <th colspan="1" class="text-center">E. Proveedora</th>
                                <th colspan="1" class="text-center">E. Transportista</th>
                                <th colspan="1" class="text-center">Dirigido</th>
                                <th colspan="1" class="text-center">Supervisor</th>
                            </x-slot>
                            <x-slot name="slot">
                                @if ($inspections->isEmpty())
                                    <tr>
                                        <td colspan="9" class="text-center">No hay registros</td>
                                    </tr>
                                @else
                                    @foreach ($inspections as $key => $inspection)
                                        <tr class="text-center fs-5">
                                            <td> {{ $inspection->id_inspections }}</td>
                                            <td>{{ $inspection->checkpoint->name }}</td>
                                            <td>{{ $inspection->date }}</td>
                                            <td>{{ $inspection->hour }}</td>
                                            <td>{{ $inspection->inspectionType->name }}</td>
                                            <td>{{ $inspection->enterpriseSupplier->name }}</td>
                                            <td>{{ $inspection->enterpriseTransport->name }}</td>
                                            <td>{{ $inspection->targeted->name }}</td>
                                            <td>{{ $inspection->user->fullname }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-table>
                    </div>
                </div>
            </div>
        </div>
        {{-- Dialogo Diario --}}
        <div class="col-12 col-md-10 col-lg-10 col-xl-10">
            <div class="row">
                <div class="col-md-12 mb-3 text-center">
                    <span class="text-dark fw-bolder fs-3">
                        Dialogos Diarios
                    </span>
                </div>
                <div class="col-12 mt-3">
                    <div class="table-responsive">
                        <x-table id="table-dialogs">
                            <x-slot name="header">
                                <th colspan="1" class="text-center">ID</th>
                                <th colspan="1" class="text-center">Punto de Control</th>
                                <th colspan="1" class="text-center">Fecha</th>
                                <th colspan="1" class="text-center">Hora</th>
                                <th colspan="1" class="text-center">E. Proveedora</th>
                                <th colspan="1" class="text-center">E. Transportista</th>
                                <th colspan="1" class="text-center">Tema</th>
                                <th colspan="1" class="text-center">Participantes</th>
                                <th colspan="1" class="text-center">Supervisor</th>
                            </x-slot>
                            <x-slot name="slot">
                                @if ($dialogs->isEmpty())
                                    <tr>
                                        <td colspan="8" class="text-center">No hay registros</td>
                                    </tr>
                                @else
                                    @foreach ($dialogs as $key => $dialog)
                                        <tr class="text-center fs-5">
                                            <td> {{ $dialog->id_daily_dialogs }}</td>
                                            <td>{{ $dialog->checkpoint->name }}</td>
                                            <td>{{ $dialog->date }}</td>
                                            <td>{{ $dialog->hour }}</td>
                                            <td>{{ $dialog->enterpriseSupplier->name }}</td>
                                            <td>{{ $dialog->enterpriseTransport->name }}</td>
                                            <td>{{ $dialog->topic }}</td>
                                            <td>{{ $dialog->participants }}</td>
                                            <td>{{ $dialog->user->fullname }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-table>
                    </div>
                </div>
            </div>
        </div>
        {{-- Pausas Activas --}}
        <div class="col-12 col-md-10 col-lg-10 col-xl-10">
            <div class="row">
                <div class="col-md-12 mb-3 text-center">
                    <span class="text-dark fw-bolder fs-3">
                        Pausas Activas
                    </span>
                </div>
                <div class="col-12 mt-3">
                    <div class="table-responsive">
                        <x-table id="table-breaks">
                            <x-slot name="header">
                                <th colspan="1" class="text-center">ID</th>
                                <th colspan="1" class="text-center">Punto de Control</th>
                                <th colspan="1" class="text-center">Fecha</th>
                                <th colspan="1" class="text-center">Hora</th>
                                <th colspan="1" class="text-center">E. Proveedora</th>
                                <th colspan="1" class="text-center">E. Transportista</th>
                                <th colspan="1" class="text-center">Participantes</th>
                                <th colspan="1" class="text-center">Supervisor</th>
                            </x-slot>
                            <x-slot name="slot">
                                @if ($breaks->isEmpty())
                                    <tr>
                                        <td colspan="8" class="text-center">No hay registros</td>
                                    </tr>
                                @else
                                    @foreach ($breaks as $key => $break)
                                        <tr class="text-center fs-5">
                                            <td> {{ $break->id_active_pauses }}</td>
                                            <td>{{ $break->checkpoint->name }}</td>
                                            <td>{{ $break->date }}</td>
                                            <td>{{ $break->hour }}</td>
                                            <td>{{ $break->enterpriseSupplier->name }}</td>
                                            <td>{{ $break->enterpriseTransport->name }}</td>
                                            <td>{{ $break->participants }}</td>
                                            <td>{{ $break->user->fullname }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-table>
                    </div>
                </div>
            </div>
        </div>
        {{-- Prueba de Alcohol --}}
        <div class="col-12 col-md-10 col-lg-10 col-xl-10">
            <div class="row">
                <div class="col-md-12 mb-3 text-center">
                    <span class="text-dark fw-bolder fs-3">
                        Prueba de Alcohol
                    </span>
                </div>
                <div class="col-12 mt-3">
                    <div class="table-responsive">
                        <x-table id="table-alcohol-tests">
                            <x-slot name="header">
                                <th colspan="1" class="text-center">ID</th>
                                <th colspan="1" class="text-center">Punto de Control</th>
                                <th colspan="1" class="text-center">Fecha</th>
                                <th colspan="1" class="text-center">Hora</th>
                                <th colspan="1" class="text-center">E. Proveedora</th>
                                <th colspan="1" class="text-center">E. Transportista</th>
                                <th colspan="1" class="text-center">Personal</th>
                                <th colspan="1" class="text-center">Supervisor</th>
                            </x-slot>
                            <x-slot name="slot">
                                @if ($alcoholTests->isEmpty())
                                    <tr>
                                        <td colspan="9" class="text-center">No hay registros</td>
                                    </tr>
                                @else
                                    @foreach ($alcoholTests as $key => $alcoholTest)
                                        <tr class="text-center fs-5">
                                            <td> {{ $alcoholTest->id_alcohol_tests }}</td>
                                            <td>{{ $alcoholTest->checkpoint->name }}</td>
                                            <td>{{ $alcoholTest->date }}</td>
                                            <td>{{ $alcoholTest->hour }}</td>
                                            <td>{{ $alcoholTest->enterpriseSupplier->name }}</td>
                                            <td>{{ $alcoholTest->enterpriseTransport->name }}</td>
                                            <td>{{ $alcoholTest->employee->name }}</td>
                                            <td>{{ $alcoholTest->user->fullname }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-table>
                    </div>
                </div>
            </div>
        </div>
        {{-- Control GPS --}}
        <div class="col-12 col-md-10 col-lg-10 col-xl-10">
            <div class="row">
                <div class="col-md-12 mb-3 text-center">
                    <span class="text-dark fw-bolder fs-3">
                        Control GPS
                    </span>
                </div>
                <div class="col-12 mt-3">
                    <div class="table-responsive">
                        <x-table id="table-gps-control">
                            <x-slot name="header">
                                <th colspan="1" class="text-center">ID</th>
                                <th colspan="1" class="text-center">Punto de Control</th>
                                <th colspan="1" class="text-center">Fecha</th>
                                <th colspan="1" class="text-center">Hora</th>
                                <th colspan="1" class="text-center">E. Proveedora</th>
                                <th colspan="1" class="text-center">E. Transportista</th>
                                <th colspan="1" class="text-center">Opcion</th>
                                <th colspan="1" class="text-center">Estado</th>
                                <th colspan="1" class="text-center">Supervisor</th>
                            </x-slot>
                            <x-slot name="slot">
                                @if ($gpsControls->isEmpty())
                                    <tr>
                                        <td colspan="8" class="text-center">No hay registros</td>
                                    </tr>
                                @else
                                    @foreach ($gpsControls as $key => $gpsControl)
                                        <tr class="text-center fs-5">
                                            <td> {{ $gpsControl->id_gps_control }}</td>
                                            <td>{{ $gpsControl->checkpoint->name }}</td>
                                            <td>{{ $gpsControl->date }}</td>
                                            <td>{{ $gpsControl->hour }}</td>
                                            <td>{{ $gpsControl->enterpriseSupplier->name }}</td>
                                            <td>{{ $gpsControl->enterpriseTransport->name }}</td>
                                            <td>
                                                @if ($gpsControl->option == 1)
                                                    <span class="badge badge-success">VELOCIDAD</span>
                                                @elseif ($gpsControl->option == 2)
                                                    <span class="badge badge-success">UBICACION</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($gpsControl->state == 1)
                                                    <span class="badge badge-success">Conforme</span>
                                                @elseif ($gpsControl->state == 2)
                                                    <span class="badge badge-warning">No Conforme</span>
                                                @else
                                                    <span class="badge badge-danger">Oportunidad de Mejora</span>
                                                @endif
                                            </td>
                                            <td>{{ $gpsControl->user->fullname }}</td>
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
@endsection
