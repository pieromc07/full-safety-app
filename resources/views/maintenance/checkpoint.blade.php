@extends('layouts.app')

@section('title', 'Mantenimiento')
@section('page', 'Punto de Control')

@push('styles')
@endpush

@section('content')
    <div class="row">
        <div class="col-12 col-lg-6">
            <x-form class="card" id="form-create" action="{{ route('checkpoint') }}" method="POST" role="form">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <x-input id="name" name="name" label="Nombre" class="form-control" placeholder="Nombre"
                                required="required" autofocus="autofocus" icon="bi-building" />
                        </div>
                        <div class="col-12">
                            <x-textarea id="description" name="description" label="Descripción" class="form-control"
                                placeholder="Descripción" req="0" />
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <x-button id="btn-store" btn="btn-primary" title="Registrar" position="left" text="Registrar"
                        icon="bi-save" />
                    <x-link-text-icon id="btn-back" btn="btn-secondary" title="Cancelar" position="left" text="Cancelar"
                        icon="bi-x-circle" href="{{ route('checkpoint') }}" />
                </div>
            </x-form>

            <x-form class="card" id="form-edit" method="POST" role="form" style="display: none;">
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <x-input id="name" name="name" label="Nombre" class="form-control" placeholder="Nombre"
                                required="required" autofocus="autofocus" icon="bi-building" />
                        </div>
                        <div class="col-12">
                            <x-textarea id="description" name="description" label="Descripción" class="form-control"
                                placeholder="Descripción" req="0" />
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <x-button id="btn-update" btn="btn-primary" title="Actualizar" position="left" text="Actualizar"
                        icon="bi-save" type="submit" />

                    <x-link-text-icon id="btn-back" btn="btn-secondary" title="Cancelar" position="left" text="Cancelar"
                        icon="bi-x-circle" href="{{ route('checkpoint') }}" />
                </div>
            </x-form>

        </div>
        <div class="col-12 col-lg-6">
            <div class="table-responsive">
                <x-table id="table-checkpoints">
                    <x-slot name="header">
                        <th colspan="1" class="text-center">ID</th>
                        <th colspan="1" class="text-center">Nombre</th>
                        <th colspan="1" class="text-center">Descripción</th>
                        <th colspan="1" class="text-center">Acciones</th>
                    </x-slot>
                    <x-slot name='slot'>
                        @if ($checkpoints->isEmpty())
                            <tr class="text-center fs-5">
                                <td colspan="4">No hay registros</td>
                            </tr>
                        @else
                            @foreach ($checkpoints as $key => $checkpoint)
                                <tr class="text-center fs-5">
                                    <td class="text-center">
                                        {{ $checkpoint->id }}
                                    </td>
                                    <td class="text-center">
                                        {{ $checkpoint->name }}
                                    </td>
                                    <td class="text-center">
                                        {{ $checkpoint->description }}
                                    </td>
                                    <td class="text-center">
                                        {{-- <x-button-icon btn="btn-info" icon="bi-eye-fill" title="Ver" onclick="" /> --}}
                                        <x-button-icon btn="btn-warning" icon="bi-pencil-square" title="Editar"
                                            onclick="Editar({{ $checkpoint }})" />
                                        <x-form-table id="form-delete-{{ $checkpoint->id }}"
                                            action="{{ route('checkpoint.destroy', $checkpoint) }}" method="POST"
                                            role="form">
                                            @method('DELETE')
                                            <x-button-icon btn="btn-danger" icon="bi-trash-fill" title="Eliminar"
                                                onclick="Eliminar({{ $checkpoint->id }})" />
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
                    <x-pagination page="{{ $checkpoints->currentPage() }}" lastPage="{{ $checkpoints->lastPage() }}"
                        route="checkpoint" perPage="{{ $checkpoints->perPage() }}"
                        total="{{ $checkpoints->total() }}" />
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

        function Editar(checkpoint) {
            $('#form-edit').show();
            $('#form-create').hide();
            $('#form-edit').attr('action', 'checkpoints/' + checkpoint.id);
            $('#form-edit').find('#name').val(checkpoint.name);
            $('#form-edit').find('#description').val(checkpoint.description);
            $('#form-edit').find('#name').focus();
        }
    </script>
@endpush
