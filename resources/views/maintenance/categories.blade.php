@extends('layouts.app')

@section('title', 'Registro')
@section('page', 'Categoria')

@push('styles')
@endpush

@section('content')
    <div class="row">
        <div class="col-12 col-lg-4">
            <x-form class="card" id="form-create" action="{{ route('category.store') }}" method="POST" role="form">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <x-input id="name" name="name" label="Nombre" class="form-control" placeholder="Nombre"
                                required="required" autofocus="autofocus" icon="bi-person" />
                        </div>
                        <input type="hidden" name="parent_id">
                        <div class="col-12">
                            <x-select id="id_targeteds" name="id_targeteds" icon="bi-building" label="Dirigido"
                                placeholder="Seleccione un Dirigido">
                                <x-slot name="options">
                                    @foreach ($targeteds as $targeted)
                                        <option value="{{ $targeted->id_targeteds }}">
                                            {{ $targeted->name }}
                                        </option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                        </div>
                        <div class="col-12">
                            <x-select id="id_inspection_types" name="id_inspection_types" icon="bi-building"
                                label="Tipo de Inspección" placeholder="Seleccione un Tipo de Inspección">
                                <x-slot name="options">
                                    @foreach ($inspectionTypes as $inspectionType)
                                        <option value="{{ $inspectionType->id_inspection_types }}">
                                            {{ $inspectionType->name }}
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
                        icon="bi-x-circle" href="{{ route('category') }}" />
                </div>
            </x-form>

            <x-form class="card" id="form-edit" method="POST" role="form" style="display: none;">
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <x-input id="name" name="name" label="Nombre" class="form-control" placeholder="Nombre"
                                required="required" autofocus="autofocus" icon="bi-person" />
                        </div>
                        <input type="hidden" name="parent_id">
                        <div class="col-12">
                            <x-select id="id_targeteds" name="id_targeteds" icon="bi-building" label="Dirigido"
                                placeholder="Seleccione un Dirigido">
                                <x-slot name="options">
                                    @foreach ($targeteds as $targeted)
                                        <option value="{{ $targeted->id_targeteds }}">
                                            {{ $targeted->name }}
                                        </option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                        </div>
                        <div class="col-12">
                            <x-select id="id_inspection_types" name="id_inspection_types" icon="bi-building"
                                label="Tipo de Inspección" placeholder="Seleccione un Tipo de Inspección">
                                <x-slot name="options">
                                    @foreach ($inspectionTypes as $inspectionType)
                                        <option value="{{ $inspectionType->id_inspection_types }}">
                                            {{ $inspectionType->name }}
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

                    <x-link-text-icon id="btn-back" btn="btn-secondary" title="Cancelar" position="left" text="Cancelar"
                        icon="bi-x-circle" href="{{ route('category') }}" />
                </div>
            </x-form>

        </div>
        <div class="col-12 col-lg-8">
            <div class="table-responsive">
                <x-table id="table-categories">
                    <x-slot name="header">
                        <th colspan="1" class="text-center">ID</th>
                        <th colspan="1" class="text-center">Nombre</th>
                        <th colspan="1" class="text-center">Dirigido</th>
                        <th colspan="1" class="text-center">Tipo de Inspección</th>
                        <th colspan="1" class="text-center">Acciones</th>
                    </x-slot>
                    <x-slot name='slot'>
                        @if ($categories->isEmpty())
                            <tr class="text-center fs-5">
                                <td colspan="5">
                                    No hay registros
                                </td>
                            </tr>
                        @else
                            @foreach ($categories as $key => $category)
                                <tr class="text-center fs-5">
                                    <td class="text-center">
                                        {{ $category->id_categories }}
                                    </td>
                                    <td class="text-center">
                                        {{ $category->name }}
                                    </td>
                                    <td class="text-center">
                                        {{ $category->targeted->name }}
                                    </td>
                                    <td class="text-center">
                                        {{ $category->inspectionType->name }}
                                    </td>
                                    <td class="text-center">
                                        {{-- <x-button-icon btn="btn-info" icon="bi-eye-fill" title="Ver" onclick="" /> --}}
                                        <x-button-icon btn="btn-warning" icon="bi-pencil-square" title="Editar"
                                            onclick="Editar({{ $category }})" />
                                        <x-form-table id="form-delete-{{ $category->id_categories }}"
                                            action="{{ route('category.destroy', $category->id_categories) }}"
                                            method="POST" role="form">
                                            @method('DELETE')
                                            <x-button-icon btn="btn-danger" icon="bi-trash-fill" title="Eliminar"
                                                type="button" onclick="Eliminar({{ $category->id_categories }})" />
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
                    <x-pagination page="{{ $categories->currentPage() }}" lastPage="{{ $categories->lastPage() }}"
                        route="category" perPage="{{ $categories->perPage() }}" total="{{ $categories->total() }}" />
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

        function Editar(category) {
            $('#form-edit').show();
            $('#form-create').hide();
            $('#form-edit').attr('action', '/categories/' + category.id_categories);
            $('#form-edit').find('#name').val(category.name);
            $('#form-edit').find('#id_targeteds').val(category.id_targeteds);
            $('#form-edit').find('#id_inspection_types').val(category.id_inspection_types);
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
