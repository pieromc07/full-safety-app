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
                        <div class="col-12">
                            <x-select id="targeted_id" name="targeted_id" icon="bi-building" label="Dirigido"
                                placeholder="Seleccione un Dirigido">
                                <x-slot name="options">
                                    @foreach ($targeteds as $targeted)
                                        <option value="{{ $targeted->id }}">
                                            {{ $targeted->name }}
                                        </option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                        </div>
                        <div class="col-12">
                            <x-select id="inspection_type_id" name="inspection_type_id" icon="bi-building"
                                label="Tipo de Inspección" placeholder="Seleccione un Tipo de Inspección">
                                <x-slot name="options">
                                    @foreach ($inspectionTypes as $inspectionType)
                                        <option value="{{ $inspectionType->id }}">
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
                        <div class="col-12">
                            <x-select id="targeted_id" name="targeted_id" icon="bi-building" label="Dirigido"
                                placeholder="Seleccione un Dirigido">
                                <x-slot name="options">
                                    @foreach ($targeteds as $targeted)
                                        <option value="{{ $targeted->id }}">
                                            {{ $targeted->name }}
                                        </option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                        </div>
                        <div class="col-12">
                            <x-select id="inspection_type_id" name="inspection_type_id" icon="bi-building"
                                label="Tipo de Inspección" placeholder="Seleccione un Tipo de Inspección">
                                <x-slot name="options">
                                    @foreach ($inspectionTypes as $inspectionType)
                                        <option value="{{ $inspectionType->id }}">
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
                                        {{ $category->id }}
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
                                        <x-form-table id="form-delete-{{ $category->id }}"
                                            action="{{ route('category.destroy', $category) }}" method="POST"
                                            role="form">
                                            @method('DELETE')
                                            <x-button-icon btn="btn-danger" icon="bi-trash-fill" title="Eliminar"
                                                onclick="Eliminar({{ $category->id }})" />
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
            $('#form-edit').attr('action', '/categories/' + category.id);
            $('#form-edit').find('#name').val(category.name);
            $('#form-edit').find('#targeted_id').val(category.targeted_id);
            $('#form-edit').find('#inspection_type_id').val(category.inspection_type_id);
            $('#form-edit').find('#name').focus();
        }
    </script>
@endpush
