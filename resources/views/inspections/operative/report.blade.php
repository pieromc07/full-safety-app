@extends('layouts.app')

@section('title', 'Inspecciones')
@section('page', 'Operativa')

@push('styles')
    {{-- estilos para las imagenes --}}
    <style>
        #evidence_one,
        #evidence_two {
            width: 300px;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        Inspección {{ $inspection->inspectionType->name }} - {{ $inspection->folio }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-12 col-sm-12 col-md-3 col-lg-2">
                            <x-input id="date" name="date" label="Fecha" class="form-control" placeholder="Fecha"
                                req={{ true }} autofocus="autofocus" icon="bi-calendar"
                                value="{{ $inspection->date }}" type="date" readonly={{ true }} />
                        </div>
                        <div class="col-12 col-sm-12 col-md-3 col-lg-2 mb-3">
                            <x-input id="hour" name="hour" label="Hora" class="form-control" placeholder="Hora"
                                req={{ true }} autofocus="autofocus" icon="bi-clock"
                                value="{{ $inspection->hour }}" type="time" readonly={{ true }} />
                        </div>
                        <div class="col-12 col-sm-12 col-md-4 col-lg-2">
                            <x-select id="id_checkpoints" name="id_checkpoints" label="Punto de Control"
                                class="form-control" req={{ true }} autofocus="autofocus" icon="bi-geo-alt"
                                value="{{ $inspection->id_checkpoints }}" placeholder="Seleccione un Punto de Control"
                                disabled={{ true }}>
                                <x-slot name="options">
                                    @foreach ($checkpoints as $checkpoint)
                                        <option value="{{ $checkpoint->id_checkpoints }}"
                                            {{ $inspection->id_checkpoints == $checkpoint->id_checkpoints ? 'selected' : '' }}>
                                            {{ $checkpoint->name }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                        </div>
                        <div class="col-12 col-sm-12 col-md-4 col-lg-3">
                            <x-select id="id_supplier_enterprises" name="id_supplier_enterprises" label="Empresa Proveedora"
                                class="form-control" req={{ true }} autofocus="autofocus" icon="bi-building"
                                value="{{ $inspection->id_supplier_enterprises }}"
                                placeholder="Seleccione una Empresa Proveedora" disabled={{ true }}>
                                <x-slot name="options">
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id_enterprises }}"
                                            {{ $inspection->id_supplier_enterprises == $supplier->id_enterprises ? 'selected' : '' }}>
                                            {{ $supplier->name }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                        </div>
                        <div class="col-12 col-sm-12 col-md-4 col-lg-3">
                            <x-select id="id_transport_enterprises" name="id_transport_enterprises"
                                label="Empresa Transportista" class="form-control" req={{ true }}
                                autofocus="autofocus" icon="bi-building"
                                value="{{ $inspection->id_transport_enterprises }}"
                                placeholder="Seleccione una Empresa Transportista" disabled={{ true }}>
                                <x-slot name="options">
                                    @foreach ($transports as $transport)
                                        <option value="{{ $transport->id_enterprises }}"
                                            {{ $inspection->id_transport_enterprises == $transport->id_enterprises ? 'selected' : '' }}>
                                            {{ $transport->name }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                        </div>
                        <div class="col-12 col-sm-12 col-md-4 col-lg-3">
                            <x-select id="id_targeteds" name="id_targeteds" label="Dirigido" class="form-control"
                                req={{ true }} autofocus="autofocus" icon="bi-person"
                                value="{{ $inspection->id_targeteds }}" placeholder="Seleccione un Dirigido"
                                disabled={{ true }}>
                                <x-slot name="options">
                                    @foreach ($targeteds as $targeted)
                                        <option value="{{ $targeted->id_targeteds }}"
                                            {{ $inspection->id_targeteds == $targeted->id_targeteds ? 'selected' : '' }}>
                                            {{ $targeted->name }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>

                        </div>
                        <div class="col-3 col-sm-12 col-md-3 col-lg-1">
                            <x-input id="convoy" name="convoy" label="Convoy" class="form-control" placeholder="Convoy"
                                req={{ true }} autofocus="autofocus" icon="bi-truck"
                                value="{{ $inspection->convoy->convoy }}" readonly={{ true }} />
                        </div>
                        <div class="col-3 col-sm-12 col-md-3 col-lg-2">
                            <x-input id="convoy_status" name="convoy_status" label="Estado" class="form-control"
                                placeholder="Estado del Convoy" req={{ true }} autofocus="autofocus"
                                icon="bi-truck" value="{{ $inspection->convoy->convoy_status }}"
                                readonly={{ true }} />
                        </div>
                        <div class="col-3 col-sm-12 col-md-3 col-lg-1">
                            <x-input id="quantity_light_units" name="quantity_light_units" label="Livianas"
                                class="form-control" placeholder="Cantidad de Unidades Livianas" req={{ true }}
                                autofocus="autofocus" icon="bi-truck"
                                value="{{ $inspection->convoy->quantity_light_units }}" readonly={{ true }} />
                        </div>
                        <div class="col-3 col-sm-12 col-md-3 col-lg-1">
                            <x-input id="quantity_heavy_units" name="quantity_heavy_units" label="Pesadas"
                                class="form-control" placeholder="Cantidad de Unidades Pesadas" req={{ true }}
                                autofocus="autofocus" icon="bi-truck"
                                value="{{ $inspection->convoy->quantity_heavy_units }}" readonly={{ true }} />
                        </div>
                        <div class="col-12 col-sm-12 col-md-3 col-lg-2">
                            <x-select id="id_products" name="id_products" label="Producto" class="form-control"
                                req={{ true }} autofocus="autofocus" icon="bi-box"
                                value="{{ $inspection->convoy->id_products }}" placeholder="Seleccione un Producto"
                                disabled={{ true }}>
                                <x-slot name="options">
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id_products }}"
                                            {{ $inspection->convoy->id_products == $product->id_products ? 'selected' : '' }}>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                        </div>
                        <div class="col-12 col-sm-12 col-md-3 col-lg-2">
                            <x-select id="id_products_two" name="id_products_two" label="Producto 2"
                                class="form-control" req={{ true }} autofocus="autofocus" icon="bi-box"
                                value="{{ $inspection->convoy->id_products_two }}" placeholder="Seleccione un Producto 2"
                                disabled={{ true }}>
                                <x-slot name="options">
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id_products }}"
                                            {{ $inspection->convoy->id_products_two == $product->id_products ? 'selected' : '' }}>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <x-table id="table-evidences">
                                    <x-slot name="header">
                                        <tr>
                                            <th>N°</th>
                                            <th>Evidencia</th>
                                            <th>Condicion</th>
                                            <th>Categoria</th>
                                            <th>Subcategoria</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </x-slot>
                                    <x-slot name="slot">
                                        @if ($inspection->evidences->isEmpty())
                                            <tr>
                                                <td colspan="3" class="text-center">No hay registros</td>
                                            </tr>
                                        @else
                                            @foreach ($inspection->evidences as $key => $evidence)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $evidence->evidence->name }}</td>
                                                    <td>
                                                        @if ($evidence->state == 1)
                                                            <span class="badge badge-success">Conforme</span>
                                                        @elseif ($evidence->state == 2)
                                                            <span class="badge badge-warning">No Conforme</span>
                                                        @else
                                                            <span class="badge badge-danger">Oportunidad de Mejora</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $evidence->evidence->category->name }}</td>
                                                    <td>{{ $evidence->evidence->subcategory->name }}</td>
                                                    <td>
                                                        <x-button-icon type="button" btn="btn-primary"
                                                            icon="bi-eye-fill" title="Ver"
                                                            onclick="Ver({{ $evidence }})" />

                                                        <x-button-icon type="button" btn="btn-primary"
                                                            icon="bi-file-text-fill" title="levantamiento"
                                                            onclick="abrirLevantamiento({{ $evidence }})">
                                                        </x-button-icon>

                                                    </td>

                                                </tr>
                                            @endforeach
                                        @endif
                                    </x-slot>
                                </x-table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="card-footer">
                        <x-link-text-icon id="btn-back" btn="btn-secondary" title="Cerrar" position="left"
                            text="Cerrar" icon="bi-x-circle"
                            href="{{ route('inspections') }}?type={{ $inspection->id_inspection_types }}" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <x-modal id="modal-evidence" title="Evidencia" maxWidth="lg">
            <div class="row">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Evidencias</h3>
                    </div>
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-12">
                                        <h5>Evidencia 1</h5>
                                    </div>
                                    <img src="" class="img-fluid" id="evidence_one">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-12">
                                        <h5>Evidencia 2</h5>
                                    </div>
                                    <img src="" class="img-fluid" id="evidence_two">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </x-modal>
    </div>
    <x-modal id="modal-levantamiento" title="Levantamiento de Inspección" maxWidth="lg">
        <div class="row" style="max-height: 80vh; overflow-y: auto;">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Levantamiento de Inspección</h3>
                </div>

                <div class="card-body">
                    <form id="form-levantamiento" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id_inspection_evidence" id="levantamiento_evidence_id">

                        <div class="row">
                            <div class="col-12 mb-4">
                                <h5 class="mb-3">Evidencias Fotográficas</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Evidencia 1 <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" name="evidence_one"
                                            id="evidence_one_upload" accept="image/*"
                                            onchange="previewImage(event, 'preview_one')" required>
                                        <div class="mt-2">
                                            <img id="preview_one" src="" alt="Vista previa"
                                                style="max-width: 100%; height: 200px; object-fit: cover; display: none; border-radius: 8px;">
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Evidencia 2</label>
                                        <input type="file" class="form-control" name="evidence_two"
                                            id="evidence_two_upload" accept="image/*"
                                            onchange="previewImage(event, 'preview_two')">
                                        <div class="mt-2">
                                            <img id="preview_two" src="" alt="Vista previa"
                                                style="max-width: 100%; height: 200px; object-fit: cover; display: none; border-radius: 8px;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">Descripción / Observaciones <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control" name="description" id="levantamiento_description" rows="5"
                                    placeholder="Describa el levantamiento realizado, acciones correctivas tomadas, etc." required></textarea>
                                <div class="form-text">Describa detalladamente las acciones realizadas</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Fecha de Levantamiento <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="date" id="levantamiento_date"
                                    value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i> Cancelar
                    </button>
                    <button type="submit" form="form-levantamiento" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Guardar Levantamiento
                    </button>
                </div>
            </div>
        </div>
    </x-modal>

    <x-modal id="modal-levantamiento" title="Levantamiento de Inspección" maxWidth="lg">
        <div class="row">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Levantamiento de Inspección</h3>
                </div>
                <div class="card-body">
                    <form id="form-levantamiento" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id_inspection_evidence" id="levantamiento_evidence_id">


                    </form>
                </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i> Cancelar
                    </button>
                    <button type="submit" form="form-levantamiento" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Guardar Levantamiento
                    </button>
                </div>
            </div>
        </div>
    </x-modal>
@endsection

@push('scripts')
    <script type="text/javascript">
        function Ver(evidence) {
            const pathBase = '{{ asset('') }}';
            $('#evidence_one').attr('src', pathBase + evidence.evidence_one);
            $('#evidence_two').attr('src', pathBase + evidence.evidence_two);
            $('#modal-evidence').modal('show');

        }
    </script>
@endpush
@push('scripts')
    <script type="text/javascript">
        function abrirLevantamiento(evidence) {
            $('#levantamiento_evidence_id').val(evidence.id_inspection_evidences);

            $('#modal-levantamiento').modal('hide');

            $('#modal-levantamiento').modal({
                backdrop: true,
                keyboard: true
            });

            $('#modal-levantamiento').modal('show');


        }


        function previewImage(event, previewId) {
            const file = event.target.files[0];
            const preview = document.getElementById(previewId);

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                preview.src = '';
                preview.style.display = 'none';
            }
        }

        $('#form-levantamiento').on('submit', function(e) {
            e.preventDefault();

            const evidence_one = $('#evidence_one_upload').val();

            if (!evidence_one) {
                alert('Debe cargar al menos la Evidencia 1');
                return;
            }

            const formData = new FormData(this);

            $.ajax({
                url: '{{ route('inspections.store') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    Swal.fire({
                        title: 'Enviando...',
                        text: 'Por favor espere',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });
                },

                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: response.message,
                    }).then(() => {
                        $('#modal-levantamiento').modal('hide');
                        location.reload();
                    });
                },

                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'Ocurrió un error'
                    });
                },
            });
        });
    </script>
@endpush
