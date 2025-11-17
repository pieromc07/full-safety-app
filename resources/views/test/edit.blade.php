@extends('layouts.app')

@section('title', 'Prueba de Alcohol')
@section('page', 'Prueba de Alcohol')

@push('styles')
    {{-- estilos para las imagenes --}}
    <style>
        #photo_one,
        #photo_two {
            width: 300px;
        }
    </style>
@endpush

@section('content')
    {{ Form::open([
        'route' => ['tests.update', $test->id_alcohol_tests],
        'method' => 'PUT',
        'files' => true,
        'id' => 'form-tests',
    ]) }}
    @csrf
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-4 col-lg-3">
                            <x-input id="date" name="date" label="Fecha" class="form-control" placeholder="Fecha"
                                required="required" autofocus="autofocus" icon="bi-calendar" value="{{ $test->date }}"
                                type="date" />
                        </div>
                        <div class="col-12 col-sm-12 col-md-4 col-lg-3 mb-3">
                            <x-input id="hour" name="hour" label="Hora" class="form-control" placeholder="Hora"
                                required="required" autofocus="autofocus" icon="bi-clock" value="{{ $test->hour }}"
                                type="time" />
                        </div>
                        <div class="col-12 col-sm-12 col-md-4 col-lg-3">
                            <x-select id="id_checkpoints" name="id_checkpoints" label="Punto de Control"
                                class="form-control" req={{ true }} autofocus="autofocus" icon="bi-geo-alt"
                                value="{{ $test->id_checkpoints }}" placeholder="Seleccione un Punto de Control">
                                <x-slot name="options">
                                    @foreach ($checkpoints as $checkpoint)
                                        <option value="{{ $checkpoint->id_checkpoints }}"
                                            {{ $test->id_checkpoints == $checkpoint->id_checkpoints ? 'selected' : '' }}>
                                            {{ $checkpoint->name }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                        </div>
                        <div class="col-12 col-sm-12 col-md-4 col-lg-3">
                            <x-select id="id_supplier_enterprises" name="id_supplier_enterprises" label="Empresa Proveedora"
                                class="form-control" req={{ true }} autofocus="autofocus" icon="bi-building"
                                value="{{ $test->id_supplier_enterprises }}"
                                placeholder="Seleccione una Empresa Proveedora">
                                <x-slot name="options">
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id_enterprises }}"
                                            {{ $test->id_supplier_enterprises == $supplier->id_enterprises ? 'selected' : '' }}>
                                            {{ $supplier->name }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                        </div>
                        <div class="col-12 col-sm-12 col-md-4 col-lg-3">
                            <x-select id="id_transport_enterprises" name="id_transport_enterprises"
                                label="Empresa Transportista" class="form-control" req={{ true }}
                                autofocus="autofocus" icon="bi-building" value="{{ $test->id_transport_enterprises }}"
                                placeholder="Seleccione una Empresa Transportista">
                                <x-slot name="options">
                                    @foreach ($transports as $transport)
                                        <option value="{{ $transport->id_enterprises }}"
                                            {{ $test->id_transport_enterprises == $transport->id_enterprises ? 'selected' : '' }}>
                                            {{ $transport->name }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                        </div>
                        <!-- Campos personales y resultado principales ya no se usan; usar detalles -->
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Detalles</h3>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Empleado</th>
                                                    <th>Resultado %</th>
                                                    <th>Estado</th>
                                                    <th>Eliminar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($test->details as $index => $detail)
                                                    <tr id="detail-row-{{ $detail->id_alcohol_test_details }}"
                                                        data-id="{{ $detail->id_alcohol_test_details }}">
                                                        <td>{{ $detail->employee->name ?? 'N/A' }}</td>
                                                        <td>
                                                            <input type="hidden"
                                                                name="details[{{ $index }}][id_alcohol_test_details]"
                                                                value="{{ $detail->id_alcohol_test_details }}">
                                                            <input type="number" step="0.1"
                                                                name="details[{{ $index }}][result]"
                                                                value="{{ $detail->result }}" class="form-control">
                                                        </td>
                                                        <td>
                                                            <select name="details[{{ $index }}][state]"
                                                                class="form-control">
                                                                <option value="0"
                                                                    {{ $detail->state == 0 ? 'selected' : '' }}>Normal
                                                                </option>
                                                                <option value="1"
                                                                    {{ $detail->state == 1 ? 'selected' : '' }}>Positivo
                                                                </option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <button type="button"
                                                                class="btn btn-sm btn-danger btn-delete-detail"
                                                                data-id="{{ $detail->id_alcohol_test_details }}">Eliminar</button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <x-button id="btn-store" btn="btn-primary" title="Actualizar" position="left" text="Actualizar"
                        icon="bi-save" type="submit" />
                    <x-link-text-icon id="btn-back" btn="btn-secondary" title="Cerrar" position="left" text="Cerrar"
                        icon="bi-x-circle" href="{{ route('tests') }}" />
                </div>
            </div>
        </div>
    </div>
    {{ Form::close() }}
@endsection

@push('scripts')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            // Manejo del select de empresas (usa jQuery existente)
            if (window.$) {
                $('#id_supplier_enterprises').on('change', function() {
                    const id_supplier_enterprises = $(this).val();
                    $.ajax({
                        url: "{{ url('enterprises') }}/" + id_supplier_enterprises,
                        id_supplier_enterprises,
                        type: 'GET',
                        success: function(data) {
                            $('#id_transport_enterprises').empty();
                            $('#id_transport_enterprises').append(
                                '<option value="">Seleccione una Empresa Transportista</option>'
                            );
                            $.each(data, function(index, enterprise) {
                                $('#id_transport_enterprises').append(
                                    '<option value="' +
                                    enterprise.id_enterprises + '">' + enterprise
                                    .name +
                                    '</option>');
                            });
                        }
                    });
                });
            }

            const tokenMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfToken = tokenMeta ? tokenMeta.getAttribute('content') : '';

            async function deleteDetail(id) {
                // Usar SweetAlert2 para confirmación
                const {
                    value: confirmDelete
                } = await Swal.fire({
                    title: '¿Eliminar detalle?',
                    text: 'Esta acción no se puede deshacer.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                });

                if (!confirmDelete) return;

                try {
                    const resp = await fetch(`{{ url('/tests/details') }}/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    });

                    if (!resp.ok) {
                        const body = await resp.json().catch(() => ({}));
                        throw new Error(body.message || 'Error al eliminar');
                    }

                    const row = document.getElementById('detail-row-' + id);
                    if (row) row.remove();
                    const data = await resp.json().catch(() => ({}));

                    // Mostrar toast usando Toastify y añadir el estado 'revisa'
                    if (window.Toastify) {
                        Toastify({
                            text: (data.message || 'Detalle eliminado') + ' — Estado: revisa',
                            duration: 3000,
                            close: true,
                            gravity: 'top',
                            position: 'center',
                            backgroundColor: '#f0ad4e'
                        }).showToast();
                    } else {
                        // fallback
                        Swal.fire('Eliminado', (data.message || 'Detalle eliminado') + ' — Estado: revisa',
                            'success');
                    }
                } catch (e) {
                    const msg = e.message || 'No se pudo eliminar';
                    if (window.Toastify) {
                        Toastify({
                            text: msg + ' — Estado: revisa',
                            duration: 4000,
                            close: true,
                            gravity: 'top',
                            position: 'center',
                            backgroundColor: '#d9534f'
                        }).showToast();
                    } else {
                        Swal.fire('Error', msg + ' — Estado: revisa', 'error');
                    }
                }
            }

            document.querySelectorAll('.btn-delete-detail').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    deleteDetail(id);
                });
            });
        });
    </script>
@endpush
