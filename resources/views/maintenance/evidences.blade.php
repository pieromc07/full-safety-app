@extends('layouts.app')

@section('title', 'Registro')
@section('page', 'Evidencia')

@push('styles')
@endpush

@section('content')
    <div class="row">
        <div class="col-12 col-lg-4">
            <x-form class="card" id="form-create" action="{{ route('evidence.store') }}" method="POST" role="form">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <x-input id="name" name="name" label="Nombre" class="form-control" placeholder="Nombre"
                                required="required" autofocus="autofocus" icon="bi-person" />
                        </div>
                        <div class="col-12">
                            <x-select id="subcategory_id" name="subcategory_id" icon="bi-building" label="Categoría"
                                placeholder="Seleccione una Categoría">
                                <x-slot name="options">
                                    @foreach ($subcategories as $category)
                                        <option value="{{ $category->id }}">
                                            {{ $category->name }}
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
                        icon="bi-x-circle" href="{{ route('evidence') }}" />
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
                            <x-select id="subcategory_id" name="subcategory_id" icon="bi-building" label="Categoría"
                                placeholder="Seleccione una Categoría">
                                <x-slot name="options">
                                    @foreach ($subcategories as $category)
                                        <option value="{{ $category->id }}">
                                            {{ $category->name }}
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
                        icon="bi-x-circle" href="{{ route('evidence') }}" />
                </div>
            </x-form>

        </div>
        <div class="col-12 col-lg-8">
            <div class="table-responsive">
                <x-table id="table-subcategories">
                    <x-slot name="header">
                        <th colspan="1" class="text-center">ID</th>
                        <th colspan="1" class="text-center">Nombre</th>
                        <th colspan="1" class="text-center">Categoría</th>
                        <th colspan="1" class="text-center">Subcategoría</th>
                        <th colspan="1" class="text-center">Acciones</th>
                    </x-slot>
                    <x-slot name='slot'>
                        @if ($evidences->isEmpty())
                            <tr class="text-center fs-5">
                                <td colspan="5">
                                    No hay registros
                                </td>
                            </tr>
                        @else
                            @foreach ($evidences as $key => $evidence)
                                <tr class="text-center fs-5">
                                    <td class="text-center">
                                        {{ $evidence->id }}
                                    </td>
                                    <td class="text-center">
                                        {{ $evidence->name }}
                                    </td>
                                    <td class="text-center">
                                        {{ $evidence->category->name }}
                                    </td>
                                    <td class="text-center">
                                        {{ $evidence->subcategory->name }}
                                    </td>
                                    <td class="text-center">
                                        {{-- <x-button-icon btn="btn-info" icon="bi-eye-fill" title="Ver" onclick="" /> --}}
                                        <x-button-icon btn="btn-warning" icon="bi-pencil-square" title="Editar"
                                            onclick="Editar({{ $evidence }})" />
                                        <x-form-table id="form-delete-{{ $evidence->id }}"
                                            action="{{ route('evidence.destroy', $evidence) }}" method="POST"
                                            role="form">
                                            @method('DELETE')
                                            <x-button-icon btn="btn-danger" icon="bi-trash-fill" title="Eliminar"
                                                onclick="Eliminar({{ $evidence->id }})" />
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
                    <x-pagination page="{{ $evidences->currentPage() }}" lastPage="{{ $evidences->lastPage() }}"
                        route="evidence" perPage="{{ $evidences->perPage() }}" total="{{ $evidences->total() }}" />
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

        function Editar(evidence) {
            $('#form-edit').show();
            $('#form-create').hide();
            $('#form-edit').attr('action', 'evidence/' + evidence.id);
            $('#form-edit').find('#name').val(evidence.name);
            $('#form-edit').find('#subcategory_id').val(evidence.subcategory_id);
            $('#form-edit').find('#name').focus();
        }
    </script>
@endpush
