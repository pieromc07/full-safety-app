@extends('layouts.app')

@section('title', 'Control GPS')
@section('page', 'Control GPS')

@push('styles')
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="table-reponsive">
                <x-table id="table-controls">
                    <x-slot name="header">
                        <th colspan="1">Punto C.</th>
                        <th colspan="1">Fecha</th>
                        <th colspan="1">Supevisor</th>
                        <th colspan="1">Emp. Transportista</th>
                        <th colspan="1">Opcion</th>
                        <th colspan="1">Estado</th>
                        <th colspan="1">Acciones</th>
                    </x-slot>
                    <x-slot name="slot">
                        @if ($controls->isEmpty())
                            <tr>
                                <td colspan="7" class="text-center">No hay registros</td>
                            </tr>
                        @else
                            @foreach ($controls as $key => $control)
                                <tr>
                                    <td> {{ $control->checkpoint->name }}</td>
                                    <td>{{ $control->date }}</td>
                                    <td>
                                        {{ $control->user->fullname }}
                                    </td>
                                    <td>{{ $control->enterpriseTransport->name }}</td>
                                    <td>
                                      @if ($control->option == 1)
                                            <span class="badge badge-success">VELOCIDAD</span>
                                        @elseif ($control->option == 2)
                                            <span class="badge badge-success">UBICACION</span>

                                        @endif
                                    </td>
                                    <td>
                                        @if ($control->state == 1)
                                            <span class="badge badge-success">Conforme</span>
                                        @elseif ($control->state == 2)
                                            <span class="badge badge-warning">No Conforme</span>
                                        @else
                                            <span class="badge badge-danger">Oportunidad de Mejora</span>
                                        @endif
                                    </td>
                                    <td>
                                        <x-link-icon btn="btn-info" icon="bi-eye-fill" title="Ver"
                                            href="{{ route('controls.show', $control->id_gps_controls) }}" />
                                        <x-link-icon btn="btn-warning" icon="bi-pencil-square" title="Editar"
                                            href="{{ route('controls.edit', $control->id_gps_controls) }}" />
                                        <x-form-table id="form-delete-{{ $control->id_gps_controls }}"
                                            action="{{ route('controls.destroy', $control->id_gps_controls) }}"
                                            method="POST" role="form">
                                            @method('DELETE')
                                        </x-form-table>
                                        <x-button-icon btn="btn-danger" icon="bi-trash-fill" title="Eliminar"
                                            onclick="Eliminar({{ $control->id_gps_controls }})" />
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </x-slot>
                </x-table>
            </div>
            <div class="row">
                <div class="col-md-12 d-flex justify-content-end">
                    <x-pagination page="{{ $controls->currentPage() }}" lastPage="{{ $controls->lastPage() }}"
                        route="controls" perPage="{{ $controls->perPage() }}" total="{{ $controls->total() }}" />
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {

        });

        function Eliminar(id) {
            Swal.fire({
                title: "¿Estás seguro?",
                text: "Esta acción no se puede deshacer.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-delete-' + id).submit();
                }
            });
        }
    </script>
@endpush
