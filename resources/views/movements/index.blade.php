@extends('layouts.app')

@section('title', 'Controlador')
@section('page', 'Lista Movimiento de Unidades')

@push('styles')
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">
            <x-table-filter id="table-filter" action="{{ route('unitmovements') }}">
                <div class="col-md-4">
                    <x-select-filter id="checkpoints-filter" label="Punto de Control" name="id_checkpoints" formId="table-filter"
                        placeholder="Punto de Control" req="{{ false }}">
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
                <div class="col-md-3 mb-8">
                    <x-date-range id="date-filter" label="Filtrar por fecha" name="rangeDate" formId="table-filter" />
                </div>
            </x-table-filter>
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
                                    {{-- <th>Hora de llegada al PO (24hrs)</th>
                                    <th>Hora de salida del PO (24hrs)</th> --}}
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
                                    {{-- <td>
                                        -
                                    </td>
                                    <td>
                                        -
                                    </td> --}}
                                </tr>
                                <tr class="table-primary">
                                    {{-- <th>Hora de llegada a KW (24hrs)</th> --}}

                                    {{-- <th>Estado de Convoy</th> --}}
                                    <th>Emp. Proveedora</th>
                                    <th>Emp. Transportista</th>
                                    <th>Administrador</th>
                                    <th>Nro. de Convoy</th>
                                </tr>
                                <tr>
                                    {{-- <td>
                                        -
                                    </td> --}}

                                    {{-- <td>
                                        -
                                    </td> --}}
                                    <td>
                                        {{ $unitmovement->supplierEnterprise->name }}
                                    </td>
                                    <td>
                                        {{ $unitmovement->transportEnterprise->name }}
                                    </td>
                                    <td>
                                        -
                                    </td>
                                    <td>
                                        {{ $unitmovement->convoy }}
                                    </td>
                                </tr>
                                <tr class="table-primary">
                                    <th>Producto</th>
                                    <th>Unidades Livianas</th>
                                    <th>Unidades Pesadas</th>
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
