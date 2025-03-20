@extends('layouts.app')

@section('title', 'Pausa Activa')
@section('page', 'Pausa Activa')

@push('styles')
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="table-reponsive">
                <x-table id="table-actives">
                    <x-slot name="header">
                        <th colspan="1">Punto C.</th>
                        <th colspan="1">Fecha</th>
                        <th colspan="1">Supevisor</th>
                        <th colspan="1">Emp. Transportista</th>
                        <th colspan="1">Participantes</th>
                        <th colspan="1">Acciones</th>
                    </x-slot>
                    <x-slot name="slot">
                        @if ($actives->isEmpty())
                            <tr>
                                <td colspan="6" class="text-center">No hay registros</td>
                            </tr>
                        @else
                            @foreach ($actives as $key => $active)
                                <tr>
                                    <td> {{ $active->checkpoint->name }}</td>
                                    <td>{{ $active->date }}</td>
                                    <td>

                                        {{ $active->user->fullname }}
                                    </td>
                                    <td>{{ $active->enterpriseTransport->name }}</td>
                                    <td>{{ $active->participants }}</td>
                                    <td>
                                        <x-link-icon btn="btn-info" icon="bi-eye-fill" title="Ver"
                                            href="{{ route('actives.show', $active->id_active_pauses) }}" />


                                        <x-button-icon btn="btn-warning" icon="bi-pencil-square" title="Editar"
                                            onclick="Editar({{ $active }})" />
                                        <x-form-table id="form-delete-{{ $active->id_active_pauses }}"
                                            action="{{ route('actives.destroy', $active->id_active_pauses) }}" method="POST" role="form">
                                            @method('DELETE')
                                            <x-button-icon btn="btn-danger" icon="bi-trash-fill" title="Eliminar"
                                                onclick="Eliminar({{ $active->id_active_pauses }})" />
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
                    <x-pagination page="{{ $actives->currentPage() }}" lastPage="{{ $actives->lastPage() }}"
                        route="actives" perPage="{{ $actives->perPage() }}" total="{{ $actives->total() }}" />
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
@endpush
