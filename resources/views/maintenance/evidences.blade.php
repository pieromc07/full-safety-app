@extends('layouts.app')

@section('title', 'Registro')
@section('page', 'Evidencia')

@push('styles')
@endpush

@section('content')
    <div class="row">
        <div class="col-12 col-lg-4">
            <x-form class="card" id="form-create" action="{{ route('evidences.store') }}" method="POST" role="form">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <x-input id="name" name="name" label="Nombre" class="form-control" placeholder="Nombre"
                                required="required" autofocus="autofocus" icon="bi-person" />
                        </div>
                        <div class="col-12">
                            <x-select id="id_subcategories" name="id_subcategories" icon="bi-building" label="Categoría"
                                placeholder="Seleccione una Categoría">
                                <x-slot name="options">
                                    @foreach ($subcategories as $category)
                                        <option value="{{ $category->id_categories }}">
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
                        icon="bi-x-circle" href="{{ route('evidences') }}" />
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
                            <x-select id="id_subcategories" name="id_subcategories" icon="bi-building" label="Categoría"
                                placeholder="Seleccione una Categoría">
                                <x-slot name="options">
                                    @foreach ($subcategories as $category)
                                        <option value="{{ $category->id_categories }}">
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
                        icon="bi-x-circle" href="{{ route('evidences') }}" />
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
                                        {{ $evidence->id_evidences }}
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
                                        <x-form-table id="form-delete-{{ $evidence->id_evidences }}"
                                            action="{{ route('evidences.destroy', $evidence->id_evidences) }}"
                                            method="POST" role="form">
                                            @method('DELETE')
                                            <x-button-icon btn="btn-danger" icon="bi-trash-fill" title="Eliminar"
                                                type="button" onclick="Eliminar({{ $evidence->id_evidences }})" />
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
                        route="evidences" perPage="{{ $evidences->perPage() }}" total="{{ $evidences->total() }}" />
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
            $('#form-edit').attr('action', '/evidences/' + evidence.id_evidences);
            $('#form-edit').find('#name').val(evidence.name);
            $('#form-edit').find('#id_subcategories').val(evidence.id_subcategories);
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
