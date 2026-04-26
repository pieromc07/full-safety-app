@extends('layouts.app')

@section('title', 'Mantenimiento')
@section('page', 'Tipo de Producto (Clase UN)')

@push('styles')
@endpush

@section('content')
    <div class="row">
        <div class="col-12 col-lg-5">
            <x-form class="card" id="form-create" action="{{ route('producttype.store') }}" method="POST" role="form">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <x-input id="code" name="code" label="Código" class="form-control" placeholder="Código"
                                required="required" autofocus="autofocus" icon="bi-hash" />
                        </div>
                        <div class="col-12">
                            <x-input id="name" name="name" label="Nombre" class="form-control" placeholder="Nombre"
                                required="required" icon="bi-tag" />
                        </div>
                        <div class="col-12">
                            <x-select id="parent_id" name="parent_id" label="Clase Padre" placeholder="Sin padre (clase principal)" req="0" icon="bi-diagram-3">
                                <x-slot name="options">
                                    @foreach ($parentTypes as $parent)
                                        <option value="{{ $parent->id_product_types }}">{{ $parent->code }} - {{ $parent->name }}</option>
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
                        icon="bi-x-circle" href="{{ route('producttype') }}" />
                </div>
            </x-form>

            <x-form class="card" id="form-edit" method="POST" role="form" style="display: none;">
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <x-input id="edit-code" name="code" label="Código" class="form-control" placeholder="Código"
                                required="required" autofocus="autofocus" icon="bi-hash" />
                        </div>
                        <div class="col-12">
                            <x-input id="edit-name" name="name" label="Nombre" class="form-control" placeholder="Nombre"
                                required="required" icon="bi-tag" />
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label" for="edit-parent_id">Clase Padre</label>
                                <select class="form-select" id="edit-parent_id" name="parent_id">
                                    <option value="">Sin padre (clase principal)</option>
                                    @foreach ($parentTypes as $parent)
                                        <option value="{{ $parent->id_product_types }}">{{ $parent->code }} - {{ $parent->name }}</option>
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
                        icon="bi-x-circle" href="{{ route('producttype') }}" />
                </div>
            </x-form>
        </div>
        <div class="col-12 col-lg-7">
            <div class="table-responsive">
                <x-table id="table-productTypes">
                    <x-slot name="header">
                        <th colspan="1" class="text-center">ID</th>
                        <th colspan="1" class="text-center">Código</th>
                        <th colspan="1" class="text-center">Nombre</th>
                        <th colspan="1" class="text-center">Clase Padre</th>
                        <th colspan="1" class="text-center">Acciones</th>
                    </x-slot>
                    <x-slot name='slot'>
                        @if ($productTypes->isEmpty())
                            <tr class="text-center fs-5">
                                <td colspan="5">No hay registros</td>
                            </tr>
                        @else
                            @foreach ($productTypes as $key => $productType)
                                <tr class="text-center fs-5">
                                    <td class="text-center">{{ $productType->id_product_types }}</td>
                                    <td class="text-center">{{ $productType->code }}</td>
                                    <td class="text-center">{{ $productType->name }}</td>
                                    <td class="text-center">
                                        @if ($productType->parent)
                                            {{ $productType->parent->code }} - {{ $productType->parent->name }}
                                        @else
                                            <span class="badge badge-light-primary">Principal</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <x-button-icon btn="btn-warning" icon="bi-pencil-square" title="Editar"
                                            onclick="Editar({{ $productType }})" />
                                        <x-form-table id="form-delete-{{ $productType->id_product_types }}"
                                            action="{{ route('producttype.destroy', $productType->id_product_types) }}"
                                            method="POST" role="form">
                                            @method('DELETE')
                                            <x-button-icon btn="btn-danger" icon="bi-trash-fill" title="Eliminar"
                                                type="button"
                                                onclick="Eliminar({{ $productType->id_product_types }})" />
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
                    <x-pagination page="{{ $productTypes->currentPage() }}" lastPage="{{ $productTypes->lastPage() }}"
                        route="producttype" perPage="{{ $productTypes->perPage() }}"
                        total="{{ $productTypes->total() }}" />
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
            $('#edit-parent_id').select2();
        });

        function Editar(productType) {
            $('#form-edit').show();
            $('#form-create').hide();
            $('#form-edit').attr('action', '/producttypes/' + productType.id_product_types);
            $('#form-edit').find('#edit-code').val(productType.code);
            $('#form-edit').find('#edit-name').val(productType.name);
            $('#edit-parent_id').val(productType.parent_id).trigger('change');
            $('#form-edit').find('#edit-code').focus();
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
