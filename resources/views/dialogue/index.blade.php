@extends('layouts.app')

@section('title', 'Dialogo Diario')
@section('page', 'Dialogo Diario')

@push('styles')
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="table-reponsive">
                <x-table id="table-dialogues">
                    <x-slot name="header">
                        <th colspan="1">Punto C.</th>
                        <th colspan="1">Fecha</th>
                        <th colspan="1">Supevisor</th>
                        <th colspan="1">Emp. Transportista</th>
                        <th colspan="1">Tema</th>
                        <th colspan="1">Participantes</th>
                        <th colspan="1">Acciones</th>
                    </x-slot>
                    <x-slot name="slot">
                        @if ($dialogues->isEmpty())
                            <tr>
                                <td colspan="6" class="text-center">No hay registros</td>
                            </tr>
                        @else
                            @foreach ($dialogues as $key => $dialogue)
                                <tr>
                                    <td> {{ $dialogue->checkpoint->name }}</td>
                                    <td>{{ $dialogue->date }}</td>
                                    <td>
                                        {{ $dialogue->user->fullname }}
                                    </td>
                                    <td>{{ $dialogue->enterpriseTransport->name }}</td>
                                    <td>{{ $dialogue->topic }}</td>
                                    <td>{{ $dialogue->participants }}</td>
                                    <td>
                                        <x-link-icon btn="btn-info" icon="bi-eye-fill" title="Ver"
                                            href="{{ route('dialogues.show', $dialogue->id_daily_dialogs) }}" />
                                        <x-link-icon btn="btn-warning" icon="bi-pencil-square" title="Editar"
                                            href="{{ route('dialogues.edit', $dialogue->id_daily_dialogs) }}" />
                                        <x-form-table id="form-delete-{{ $dialogue->id_daily_dialogs }}"
                                            action="{{ route('dialogues.destroy', $dialogue) }}" method="POST"
                                            role="form">
                                            @method('DELETE')
                                        </x-form-table>
                                        <x-button-icon btn="btn-danger" icon="bi-trash-fill" title="Eliminar"
                                            onclick="Eliminar({{ $dialogue->id_daily_dialogs }})" />
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </x-slot>
                </x-table>
            </div>
            <div class="row">
                <div class="col-md-12 d-flex justify-content-end">
                    <x-pagination page="{{ $dialogues->currentPage() }}" lastPage="{{ $dialogues->lastPage() }}"
                        route="dialogues" perPage="{{ $dialogues->perPage() }}" total="{{ $dialogues->total() }}" />
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
