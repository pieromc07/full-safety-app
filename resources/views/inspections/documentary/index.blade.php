@extends('layouts.app')

@section('title', 'Inspecciones')
@section('page', 'Inspecciones')

@push('styles')
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="table-reponsive">
                <x-table id="table-inspections">
                    <x-slot name="header">
                        <th colspan="1">Serie</th>
                        <th colspan="1">Fecha</th>
                        <th colspan="1">Supevisor</th>
                        <th colspan="1">Emp. Transportista</th>
                        <th colspan="1">Dirigido</th>
                        <th colspan="1">Acciones</th>
                    </x-slot>
                    <x-slot name="slot">
                        @if ($inspections->isEmpty())
                            <tr>
                                <td colspan="6" class="text-center">No hay registros</td>
                            </tr>
                        @else
                            @foreach ($inspections as $key => $inspection)
                                <tr>
                                    <td> {{ $inspection->folio }}</td>
                                    <td>{{ $inspection->date }}</td>
                                    <td>
                                        {{ $inspection->user->fullname }}
                                    </td>
                                    <td>{{ $inspection->enterpriseTransport->name }}</td>
                                    <td>{{ $inspection->targeted->name }}</td>
                                    <td>
                                        <x-link-icon btn="btn-info" icon="bi-eye-fill" title="Ver"
                                            href="{{ route('inspections.show', $inspection->id_inspections) }}" />
                                        <x-link-icon btn="btn-warning" icon="bi-pencil-square" title="Editar"
                                            href="{{ route('inspections.edit', $inspection->id_inspections) }}" />
                                        <x-form-table id="form-delete-{{ $inspection->id_inspections }}"
                                            action="{{ route('inspections.destroy', $inspection->id_inspections) }}"
                                            method="POST" role="form">
                                            @method('DELETE')
                                            <x-button-icon btn="btn-danger" icon="bi-trash-fill" title="Eliminar"
                                                onclick="Eliminar({{ $inspection->id_inspections }})" />
                                        </x-form-table>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </x-slot>
                </x-table>
            </div>
            <div class="row">
                <div class="col-md-12 d-flex justify-content-end">
                    <x-pagination page="{{ $inspections->currentPage() }}" lastPage="{{ $inspections->lastPage() }}"
                        route="inspections" perPage="{{ $inspections->perPage() }}" total="{{ $inspections->total() }}" />
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
@endpush
