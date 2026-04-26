@props(['type', 'message'])

@php
    $type = $type ?? 'success';
    $message = $message ?? 'Operación realizada con éxito';
    $toastrType = match($type) {
        'danger' => 'error',
        'success' => 'success',
        'warning' => 'warning',
        default => 'info',
    };
    $toastrTitle = match($type) {
        'danger' => 'Error',
        'success' => 'Correcto',
        'warning' => 'Advertencia',
        default => 'Información',
    };
@endphp

@push('scripts')
    <script type="text/javascript">
        $(document).ready(() => {
            toastr.{{ $toastrType }}("{{ $message }}", "{{ $toastrTitle }}");
        });
    </script>
@endpush
