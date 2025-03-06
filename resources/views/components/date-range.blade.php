@props(['id', 'label', 'req', 'disabled', 'name', 'placeholder', 'formId'])

@php
    $id = $id ?? md5('input-date-range' . rand(1, 1000));
    $label = $label ?? 'Date Range';
    $req = $req ?? false;
    $disabled = $disabled ?? false;
    $name = $name ?? 'date_range';
    $placeholder = $placeholder ?? 'Select Date Range';
@endphp


<div class="mb-4">
    <label class="form-label">
        {{ $label }}
        @if ($req)
            <span class="text-danger">*</span>
        @endif
    </label>
    <input class="form-control form-control-solid" id="{{ $id }}" name="{{ $name }}" type="text"
        value="{{ request($name) }}" placeholder="{{ $placeholder }}" {{ $disabled ? 'disabled' : '' }}>

</div>


@push('scripts')
    <script type="text/javascript">
        $(document).ready(() => {
            let range = $('#{{ $id }}').val();
            let startDate = moment().startOf('day');
            let endDate = moment().endOf('day');
            if (range) {
                let dates = range.split(' - ');
                startDate = dates[0];
                endDate = dates[1];
            }
            $('#{{ $id }}').daterangepicker({
                opens: 'left',
                timePicker: true,
                timePicker24Hour: true,
                timePickerIncrement: 30,
                timePickerSeconds: true,
                autoUpdateInput: true,
                startDate,
                endDate,
                locale: {
                    format: 'YYYY-MM-DD HH:mm:ss',
                    cancelLabel: 'Limpiar',
                    applyLabel: 'Seleccionar',
                    customRangeLabel: 'Custom Range',
                    daysOfWeek: ['L', 'M', 'X', 'J', 'V', 'S', 'D'],
                    monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto',
                        'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
                    ],
                    firstDay: 1,
                }
            });

            $('#{{ $id }}').on('apply.daterangepicker', function(ev, picker) {
                $('#{{ $formId }}').submit();
            });
        });
    </script>
@endpush
