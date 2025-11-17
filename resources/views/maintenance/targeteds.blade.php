@extends('layouts.app')

@section('title', 'Registro')
@section('page', 'Dirigidos')

@push('styles')
@endpush

@section('content')
    <div class="row">
        <div class="col-12 col-lg-6">
            <x-form class="card" id="form-create" action="{{ route('targeted.store') }}" method="POST" role="form"
                enctype="multipart/form-data">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <x-input id="name" name="name" label="Nombre" class="form-control" placeholder="Nombre"
                                required="required" autofocus="autofocus" icon="bi-person" />
                        </div>
                        <div class="col-12">
                            <x-input type="file" id="image" name="image" label="Logo" class="form-control"
                                placeholder="Logo" autofocus="autofocus" icon="bi-image" req="0"
                                accept=".png, .jpg, .jpeg, .gif, .webp" />
                        </div>
                        <div class="col-12">
                            <x-select-multiple id="id_inspection_types" name="id_inspection_types"
                                label="Tipo de Inspección" icon="bi-card-checklist"
                                placeholder="Seleccione un Tipo de Inspección">
                                <x-slot name="options">
                                    @foreach ($inspectionTypes as $inspectionType)
                                        <option value="{{ $inspectionType->id_inspection_types }}">
                                            {{ $inspectionType->name }}
                                        </option>
                                    @endforeach
                                </x-slot>
                            </x-select-multiple>
                        </div>
                        <div class="col-12">
                            <x-select id="id_load_types" name="id_load_types" label="Tipo de Carga" class="form-control"
                                req="0" autofocus="autofocus" icon="bi-box-seam" value=""
                                placeholder="Seleccione un Tipo de Carga">
                                <x-slot name="options">
                                    @foreach ($loadTypes as $loadType)
                                        <option value="{{ $loadType->id_load_types }}">
                                            {{ $loadType->name }}
                                        </option>
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
                        icon="bi-x-circle" href="{{ route('targeted') }}" />
                </div>
            </x-form>

            <x-form class="card" id="form-edit" method="POST" role="form" style="display: none;"
                enctype="multipart/form-data">
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <x-input id="name" name="name" label="Nombre" class="form-control" placeholder="Nombre"
                                required="required" autofocus="autofocus" icon="bi-person" />
                        </div>
                        <div class="col-12">
                            <x-input type="file" id="image" name="image" label="Logo" class="form-control"
                                placeholder="Logo" autofocus="autofocus" icon="bi-image" req="0"
                                accept=".png, .jpg, .jpeg, .gif, .webp" />
                        </div>
                        <div class="col-12">
                            <x-select-multiple id="id_inspection_types" name="id_inspection_types"
                                label="Tipo de Inspección" icon="bi-card-checklist"
                                placeholder="Seleccione un Tipo de Inspección">
                                <x-slot name="options">
                                    @foreach ($inspectionTypes as $inspectionType)
                                        <option value="{{ $inspectionType->id_inspection_types }}">
                                            {{ $inspectionType->name }}
                                        </option>
                                    @endforeach
                                </x-slot>
                            </x-select-multiple>
                        </div>
                        <div class="col-12">
                            <x-select id="id_load_types" name="id_load_types" label="Tipo de Carga" class="form-control"
                                req="0" autofocus="autofocus" icon="bi-box-seam" value=""
                                placeholder="Seleccione un Tipo de Carga">
                                <x-slot name="options">
                                    @foreach ($loadTypes as $loadType)
                                        <option value="{{ $loadType->id_load_types }}">
                                            {{ $loadType->name }}
                                        </option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <x-button id="btn-update" btn="btn-primary" title="Actualizar" position="left" text="Actualizar"
                        icon="bi-save" type="submit" />

                    <x-link-text-icon id="btn-back" btn="btn-secondary" title="Cancelar" position="left"
                        text="Cancelar" icon="bi-x-circle" href="{{ route('targeted') }}" />
                </div>
            </x-form>

        </div>
        <div class="col-12 col-lg-6">
            <div class="table-responsive">
                <x-table id="table-targeteds">
                    <x-slot name="header">
                        <th colspan="1" class="text-center">ID</th>
                        <th colspan="1" class="text-center">Nombre</th>
                        <th colspan="1" class="text-center">Imagen</th>
                        <th colspan="1" class="text-center">Tipo de Inspección</th>
                        <th colspan="1" class="text-center">Tipo de Carga</th>
                        <th colspan="1" class="text-center">Acciones</th>
                    </x-slot>
                    <x-slot name='slot'>
                        @if ($targeteds->isEmpty())
                            <tr class="text-center fs-5">
                                <td colspan="5">No hay registros</td>
                            </tr>
                        @else
                            @foreach ($targeteds as $key => $targeted)
                                <tr class="text-center fs-5">
                                    <td class="text-center">
                                        {{ $targeted->id_targeteds }}
                                    </td>
                                    <td class="text-center">
                                        {{ $targeted->name }}
                                    </td>
                                    <td class="text-center">
                                        <img src="{{ asset($targeted->image) }}" alt="{{ $targeted->name }}"
                                            class="img-thumbnail" style="width: 50px; height: 50px;">
                                    </td>
                                    <td class="text-center">
                                        @if ($targeted->targetedRelsInspections->isNotEmpty())
                                            {{ $targeted->targetedRelsInspections->map(function ($rel) {return optional($rel->inspectionType)->name;})->filter()->implode(', ') }}
                                        @else
                                            {{ '-' }}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{ $targeted->loadType ? $targeted->loadType->name : '-' }}
                                    </td>
                                    <td class="text-center">
                                        {{-- <x-button-icon btn="btn-info" icon="bi-eye-fill" title="Ver" onclick="" /> --}}
                                        <x-button-icon btn="btn-warning" icon="bi-pencil-square" title="Editar"
                                            onclick="Editar({{ json_encode($targeted) }})" />
                                        <x-form-table id="form-delete-{{ $targeted->id_targeteds }}"
                                            action="{{ route('targeted.destroy', $targeted->id_targeteds) }}"
                                            method="POST" role="form">
                                            @method('DELETE')
                                            <x-button-icon btn="btn-danger" icon="bi-trash-fill" title="Eliminar"
                                                type="button" onclick="Eliminar({{ $targeted->id_targeteds }})" />
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
                    <x-pagination page="{{ $targeteds->currentPage() }}" lastPage="{{ $targeteds->lastPage() }}"
                        route="targeted" perPage="{{ $targeteds->perPage() }}" total="{{ $targeteds->total() }}" />
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
        });

        function Editar(targeted) {
            $('#form-edit').show();
            $('#form-create').hide();
            $('#form-edit').attr('action', '/targeteds/' + targeted.id_targeteds);
            $('#form-edit').find('#name').val(targeted.name);

            $('#form-edit').find('#id_inspection_types').select2({
                placeholder: "Seleccione un Tipo de Inspección",
                width: '100%'
            });

            console.log(targeted);

            let inspectionTypes = targeted.targeted_rels_inspections.map(rel => rel.id_inspection_types);
            // $('#form-edit').find('#id_inspection_types').val(inspectionTypes).trigger('change.select2');
            $('#form-edit').find('#id_inspection_types').val(inspectionTypes).trigger('change');
            // $('#id_inspection_types').val(inspectionTypes).trigger('change.select2');

            $('#form-edit').find('#id_load_types').val(targeted.id_load_types).trigger('change');


            $('#form-edit').find('#name').focus();
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
