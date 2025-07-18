@extends('layouts.app')

@section('title', 'Controlador')
@section('page', 'Lista Movimiento de Unidades')

@push('styles')
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">
            <x-table-filter id="table-filter" action="{{ route('unitmovements') }}">
                <div class="col-md-2">
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
                <div class="col-md-2">
                    <x-select-filter id="direction-filter" label="Dirección" name="direction" formId="table-filter"
                        placeholder="Dirección" req="{{ false }}">
                        <x-slot name="options">
                            <option value="1" data-local-selected="{{ request('direction') == 1 ? 1 : 0 }}">Subida
                            </option>
                            <option value="2" data-local-selected="{{ request('direction') == 2 ? 1 : 0 }}">Bajada
                            </option>
                        </x-slot>
                    </x-select-filter>
                </div>
                <div class="col-md-2">
                    <x-select-filter id="convoy-state-filter" label="Estado de Convoy" name="convoy_state"
                        formId="table-filter" placeholder="Estado de Convoy" req="{{ false }}">
                        <x-slot name="options">
                            <option value="1" data-local-selected="{{ request('convoy_state') == 1 ? 1 : 0 }}">Cargado
                            </option>
                            <option value="2" data-local-selected="{{ request('convoy_state') == 2 ? 1 : 0 }}">Vacio
                            </option>
                        </x-slot>
                    </x-select-filter>
                </div>
                <div class="col-md-3">
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
            </x-table-filter>
        </div>
        <div class="row">
            <div class="col-3">
                <x-link-text-icon id="btn-export-pdf" class="btn-primary" icon="bi-file-pdf-fill" text="Exportar PDF"
                    href="{{ route('unitmovements.export.pdf', request()->all()) }}" target="_blank" />
            </div>
        </div>
        <div class="col-md-12">
            @foreach ($unitmovements as $unitmovement)
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        <table class="table table-bordered text-center align-middle">
                            <tbody>
                                <tr class="table-primary">
                                    <th>Fecha</th>
                                    <th>Semana</th>
                                    <th>Punto de Control</th>
                                    <th>Acción</th>
                                    <th>Hora de llegada al PO (24hrs)</th>

                                </tr>
                                <tr>
                                    <td>
                                        {{ date('d/m/Y', strtotime($unitmovement->date)) }}
                                    </td>
                                    <td>
                                        {{ date('W', strtotime($unitmovement->date)) }}
                                    </td>
                                    <td>
                                        {{ $unitmovement->checkpoint->name }}
                                    </td>
                                    <td>
                                        {{ $unitmovement->direction == 1 ? 'Subida' : 'Bajada' }}
                                    </td>
                                    <td>
                                        {{ $unitmovement->time_arrival ?? '-' }}
                                    </td>

                                </tr>
                                <tr class="table-primary">
                                    <th>Estado de Convoy</th>
                                    <th>Emp. Proveedora</th>
                                    <th>Emp. Transportista</th>
                                    <th>Administrador</th>
                                    <th>Hora de salida del PO (24hrs)</th>
                                </tr>
                                <tr>
                                    <td>
                                        {{ $unitmovement->convoy_state == 1 ? 'Cargado' : 'Vacio' }}
                                    </td>
                                    <td>
                                        {{ $unitmovement->supplierEnterprise->name }}
                                    </td>
                                    <td>
                                        {{ $unitmovement->transportEnterprise->name }}
                                    </td>

                                    <td>
                                        {{ $unitmovement->user->fullname }}
                                    </td>
                                    <td>
                                        {{ $unitmovement->time_departure ?? '-' }}
                                    </td>

                                </tr>
                                <tr class="table-primary">
                                    <th>Producto</th>
                                    <th>Unidades Livianas</th>
                                    <th>Unidades Pesadas</th>
                                    <th>Nro. de Convoy</th>
                                    <th>
                                        Eliminar
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        {{ $unitmovement->product->name }}
                                    </td>
                                    <td>
                                        {{ $unitmovement->light_vehicle }}
                                    </td>
                                    <td>
                                        {{ $unitmovement->heavy_vehicle }}
                                    </td>
                                    <td>
                                        {{ $unitmovement->nroConvoy() }}
                                    </td>
                                    <td>
                                        <form
                                            action="{{ route('unitmovements.destroy', $unitmovement->id_unit_movements) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
