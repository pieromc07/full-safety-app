@extends('layouts.app')

@section('title', 'Registro')
@section('page', 'Personal')

@push('styles')
@endpush

@section('content')
    <div class="row">
        <div class="col-12 col-lg-6">
            <x-form class="card" id="form-create" action="{{ route('employee.store') }}" method="POST" role="form"
                enctype="multipart/form-data">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <x-input id="document" name="document" label="N° de Documento" class="form-control"
                                placeholder="N° de documento" required="required" autofocus="autofocus" icon="bi-person" />
                        </div>
                        <div class="col-12">
                            <x-input id="name" name="name" label="Nombre" class="form-control" placeholder="Nombre"
                                required="required" autofocus="autofocus" icon="bi-person" />
                        </div>
                        <div class="col-12">
                            <x-input id="lastname" name="lastname" label="Apellidos" class="form-control"
                                placeholder="Apellidos" required="required" autofocus="autofocus" icon="bi-person" />
                        </div>
                        <div class="col-12">
                            <x-select id="id_transport_enterprises" name="id_transport_enterprises" icon="bi-building"
                                label="Empresa Transportista" placeholder="Seleccione empresa">
                                <x-slot name="options">
                                    @foreach ($enterpriseTransports as $transport)
                                        <option value="{{ $transport->id_enterprises }}">
                                            {{ $transport->name }}
                                        </option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                        </div>
                        <div class="col-12">
                            <x-select id="id_targeteds" name="id_targeteds" icon="bi-person-badge"
                                label="Rol" placeholder="Seleccione un rol" req="0">
                                <x-slot name="options">
                                    @foreach ($targetedRoles as $role)
                                        <option value="{{ $role->id_targeteds }}">
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                        </div>
                        <div class="col-12 wrap-job-title" style="display:none;">
                            <x-input id="job_title" name="job_title" label="Cargo (especificar)"
                                placeholder="Ej: Operador de cisterna" icon="bi-briefcase" req="0" />
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <x-button id="btn-store" btn="btn-primary" title="Registrar" position="left" text="Registrar"
                        icon="bi-save" />
                    <x-link-text-icon id="btn-back" btn="btn-secondary" title="Cancelar" position="left" text="Cancelar"
                        icon="bi-x-circle" href="{{ route('employee') }}" />
                </div>
            </x-form>

            <x-form class="card" id="form-edit" method="POST" role="form" style="display: none;"
                enctype="multipart/form-data">
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <x-input id="document" name="document" label="N° de Documento" class="form-control"
                                placeholder="N° de documento" required="required" autofocus="autofocus" icon="bi-person" />
                        </div>
                        <div class="col-12">
                            <x-input id="name" name="name" label="Nombre" class="form-control" placeholder="Nombre"
                                required="required" autofocus="autofocus" icon="bi-person" />
                        </div>
                        <div class="col-12">
                            <x-input id="lastname" name="lastname" label="Apellidos" class="form-control"
                                placeholder="Apellidos" required="required" autofocus="autofocus" icon="bi-person" />
                        </div>
                        <div class="col-12">
                            <x-select id="id_transport_enterprises" name="id_transport_enterprises" icon="bi-building"
                                label="Empresa Transportista" placeholder="Seleccione empresa">
                                <x-slot name="options">
                                    @foreach ($enterpriseTransports as $transport)
                                        <option value="{{ $transport->id_enterprises }}">
                                            {{ $transport->name }}
                                        </option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                        </div>
                        <div class="col-12">
                            <x-select id="id_targeteds" name="id_targeteds" icon="bi-person-badge"
                                label="Rol" placeholder="Seleccione un rol" req="0">
                                <x-slot name="options">
                                    @foreach ($targetedRoles as $role)
                                        <option value="{{ $role->id_targeteds }}">
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                        </div>
                        <div class="col-12 wrap-job-title" style="display:none;">
                            <x-input id="job_title" name="job_title" label="Cargo (especificar)"
                                placeholder="Ej: Operador de cisterna" icon="bi-briefcase" req="0" />
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <x-button id="btn-update" btn="btn-primary" title="Actualizar" position="left" text="Actualizar"
                        icon="bi-save" type="submit" />

                    <x-link-text-icon id="btn-back" btn="btn-secondary" title="Cancelar" position="left"
                        text="Cancelar" icon="bi-x-circle" href="{{ route('employee') }}" />
                </div>
            </x-form>

        </div>
        <div class="col-12 col-lg-6">
            <div class="table-responsive">
                <x-table id="table-employees">
                    <x-slot name="header">
                        <th colspan="1" class="text-center">ID</th>
                        <th colspan="1" class="text-center">Documento</th>
                        <th colspan="1" class="text-center">Nombre</th>
                        <th colspan="1" class="text-center">Rol</th>
                        <th colspan="1" class="text-center">Cargo</th>
                        <th colspan="1" class="text-center">Acciones</th>
                    </x-slot>
                    <x-slot name='slot'>
                        @if ($employees->isEmpty())
                            <tr class="text-center fs-5">
                                <td colspan="6">No hay registros</td>
                            </tr>
                        @else
                            @foreach ($employees as $key => $employee)
                                <tr class="text-center fs-5">
                                    <td class="text-center">
                                        {{ $employee->id_employees }}
                                    </td>
                                    <td class="text-center">
                                        {{ $employee->document }}
                                    </td>
                                    <td class="text-center">
                                        {{ $employee->fullname }}
                                    </td>
                                    <td class="text-center">
                                        {{ optional($employee->targeted)->name ?? '-' }}
                                    </td>
                                    <td class="text-center">
                                        {{ $employee->job_title ?? '-' }}
                                    </td>
                                    <td class="text-center">
                                        <x-button-icon btn="btn-warning" icon="bi-pencil-square" title="Editar"
                                            onclick="Editar({{ $employee }})" />
                                        <x-form-table id="form-delete-{{ $employee->id_employees }}"
                                            action="{{ route('employee.destroy', $employee->id_employees) }}" method="POST"
                                            role="form">
                                            @method('DELETE')
                                            <x-button-icon btn="btn-danger" icon="bi-trash-fill" title="Eliminar" type="button"
                                                onclick="Eliminar({{ $employee->id_employees}})" />
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
                    <x-pagination page="{{ $employees->currentPage() }}" lastPage="{{ $employees->lastPage() }}"
                        route="employee" perPage="{{ $employees->perPage() }}" total="{{ $employees->total() }}" />
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script type="text/javascript">
        const OTRO_ROLE_ID = @json($otroRoleId);

        // Toggle del campo job_title: visible solo cuando el rol elegido es "Otro".
        function syncJobTitleVisibility($form) {
            const selected = String($form.find('#id_targeteds').val() ?? '');
            const $wrap = $form.find('.wrap-job-title');
            if (OTRO_ROLE_ID && selected === String(OTRO_ROLE_ID)) {
                $wrap.show();
            } else {
                $wrap.hide();
                $form.find('#job_title').val('');
            }
        }

        $(document).ready(() => {
            $('#btn-store').click(() => {
                $('#form-create').submit();
            });
            // Vinculamos el toggle a ambos forms.
            $('#form-create, #form-edit').each(function () {
                const $form = $(this);
                $form.find('#id_targeteds').on('change', () => syncJobTitleVisibility($form));
                syncJobTitleVisibility($form);
            });
        });

        function Editar(employee) {
            const $form = $('#form-edit');
            $form.show();
            $('#form-create').hide();
            $form.attr('action', '/employees/' + employee.id_employees);
            $form.find('#document').val(employee.document);
            $form.find('#name').val(employee.name);
            $form.find('#lastname').val(employee.lastname);
            $form.find('#id_transport_enterprises').val(employee.id_transport_enterprises).trigger('change');
            $form.find('#id_targeteds').val(employee.id_targeteds).trigger('change');
            $form.find('#job_title').val(employee.job_title ?? '');
            syncJobTitleVisibility($form);
            $form.find('#name').focus();
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
