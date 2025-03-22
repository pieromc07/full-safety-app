@extends('layouts.app')

@section('title', 'Prueba de Alcohol')
@section('page', 'Prueba de Alcohol')

@push('styles')
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="table-reponsive">
                <x-table id="table-tests">
                    <x-slot name="header">
                        <th colspan="1">Punto C.</th>
                        <th colspan="1">Fecha</th>
                        <th colspan="1">Supevisor</th>
                        <th colspan="1">Emp. Transportista</th>
                        <th colspan="1">Personal</th>
                        <th colspan="1">Acciones</th>
                    </x-slot>
                    <x-slot name="slot">
                        @if ($tests->isEmpty())
                            <tr>
                                <td colspan="6" class="text-center">No hay registros</td>
                            </tr>
                        @else
                            @foreach ($tests as $key => $test)
                                <tr>
                                    <td> {{ $test->checkpoint->name }}</td>
                                    <td>{{ $test->date }}</td>
                                    <td>
                                        {{ $test->user->fullname }}
                                    </td>
                                    <td>{{ $test->enterpriseTransport->name }}</td>
                                    <td>{{ $test->employee->fullname }}</td>
                                    <td>
                                        <x-link-icon btn="btn-info" icon="bi-eye-fill" title="Ver"
                                            href="{{ route('tests.show', $test->id_alcohol_tests) }}" />
                                        <x-link-icon btn="btn-warning" icon="bi-pencil-square" title="Editar"
                                            href="{{ route('tests.edit', $test->id_alcohol_tests) }}" />
                                        <x-form-table id="form-delete-{{ $test->id_alcohol_tests }}"
                                            action="{{ route('tests.destroy', $test->id_alcohol_tests) }}" method="POST"
                                            role="form">
                                            @method('DELETE')
                                        </x-form-table>
                                        <x-button-icon btn="btn-danger" icon="bi-trash-fill" title="Eliminar"
                                            onclick="Eliminar({{ $test->id_alcohol_tests }})" />
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </x-slot>
                </x-table>
            </div>
            <div class="row">
                <div class="col-md-12 d-flex justify-content-end">
                    <x-pagination page="{{ $tests->currentPage() }}" lastPage="{{ $tests->lastPage() }}" route="tests"
                        perPage="{{ $tests->perPage() }}" total="{{ $tests->total() }}" />
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
