@extends('layouts.app')

@section('title', 'Maestro')
@section('page', 'Dirigido por Tipo de Inspección')

@push('styles')
@endpush

@section('content')
    <div class="row">
        <div class="col-12 col-lg-5">
            <x-form class="card" id="form-create" action="{{ route('targetedrelsinspe.store') }}" method="POST" role="form">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <x-select id="id_targeteds" name="id_targeteds" label="Dirigido" placeholder="Seleccione un dirigido" icon="bi-bullseye">
                                <x-slot name="options">
                                    @foreach ($targeteds as $targeted)
                                        <option value="{{ $targeted->id_targeteds }}">{{ $targeted->name }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                        </div>
                        <div class="col-12">
                            <x-select id="id_inspection_types" name="id_inspection_types" label="Tipo de Inspección" placeholder="Seleccione un tipo" icon="bi-clipboard-check">
                                <x-slot name="options">
                                    @foreach ($inspectionTypes as $type)
                                        <option value="{{ $type->id_inspection_types }}">{{ $type->name }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <x-button id="btn-store" btn="btn-primary" title="Registrar" position="left" text="Registrar"
                        icon="bi-save" />
                    <x-link-text-icon id="btn-back" btn="btn-secondary" title="Cancelar" position="left" text="Cancelar"
                        icon="bi-x-circle" href="{{ route('targetedrelsinspe') }}" />
                </div>
            </x-form>

            <x-form class="card" id="form-edit" method="POST" role="form" style="display: none;">
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label" for="edit-id_targeteds">
                                    Dirigido <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="edit-id_targeteds" name="id_targeteds">
                                    <option value="">Seleccione un dirigido</option>
                                    @foreach ($targeteds as $targeted)
                                        <option value="{{ $targeted->id_targeteds }}">{{ $targeted->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label" for="edit-id_inspection_types">
                                    Tipo de Inspección <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="edit-id_inspection_types" name="id_inspection_types">
                                    <option value="">Seleccione un tipo</option>
                                    @foreach ($inspectionTypes as $type)
                                        <option value="{{ $type->id_inspection_types }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <x-button id="btn-update" btn="btn-primary" title="Actualizar" position="left" text="Actualizar"
                        icon="bi-save" type="submit" />
                    <x-link-text-icon id="btn-back" btn="btn-secondary" title="Cancelar" position="left" text="Cancelar"
                        icon="bi-x-circle" href="{{ route('targetedrelsinspe') }}" />
                </div>
            </x-form>
        </div>
        <div class="col-12 col-lg-7">
            <div class="table-responsive">
                <x-table id="table-targetedRelsInspections">
                    <x-slot name="header">
                        <th colspan="1" class="text-center">ID</th>
                        <th colspan="1" class="text-center">Dirigido</th>
                        <th colspan="1" class="text-center">Tipo de Inspección</th>
                        <th colspan="1" class="text-center">Acciones</th>
                    </x-slot>
                    <x-slot name='slot'>
                        @if ($relations->isEmpty())
                            <tr class="text-center fs-5">
                                <td colspan="4">No hay registros</td>
                            </tr>
                        @else
                            @foreach ($relations as $key => $relation)
                                <tr class="text-center fs-5">
                                    <td class="text-center">{{ $relation->id_targeted_rels_inspections }}</td>
                                    <td class="text-center">{{ $relation->targeted->name ?? '-' }}</td>
                                    <td class="text-center">{{ $relation->inspectionType->name ?? '-' }}</td>
                                    <td class="text-center">
                                        <x-button-icon btn="btn-warning" icon="bi-pencil-square" title="Editar"
                                            onclick="Editar({{ $relation }})" />
                                        <x-form-table id="form-delete-{{ $relation->id_targeted_rels_inspections }}"
                                            action="{{ route('targetedrelsinspe.destroy', $relation->id_targeted_rels_inspections) }}"
                                            method="POST" role="form">
                                            @method('DELETE')
                                            <x-button-icon btn="btn-danger" icon="bi-trash-fill" title="Eliminar"
                                                type="button"
                                                onclick="Eliminar({{ $relation->id_targeted_rels_inspections }})" />
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
                    <x-pagination page="{{ $relations->currentPage() }}" lastPage="{{ $relations->lastPage() }}"
                        route="targetedrelsinspe" perPage="{{ $relations->perPage() }}"
                        total="{{ $relations->total() }}" />
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(() => {
            $('#btn-store').click(() => {
                $('#form-create').submit();
            });
            $('#edit-id_targeteds').select2();
            $('#edit-id_inspection_types').select2();
        });

        function Editar(relation) {
            $('#form-edit').show();
            $('#form-create').hide();
            $('#form-edit').attr('action', '/targetedrelsinspe/' + relation.id_targeted_rels_inspections);
            $('#edit-id_targeteds').val(relation.id_targeteds).trigger('change');
            $('#edit-id_inspection_types').val(relation.id_inspection_types).trigger('change');
        }

        function Eliminar(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '¡Sí, bórralo!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#form-delete-' + id).submit();
                }
            });
        }
    </script>
@endpush
