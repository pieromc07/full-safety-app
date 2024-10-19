@props(['type', 'message'])

@php
    $type = $type ?? 'success';
    $message = $message ?? 'Operación realizada con éxito';
@endphp

@push('scripts')
    <script type="text/javascript">
        $(document).ready(() => {
            let background = '#28a745';
            if ("{{ $type }}" == 'danger') {
                background = '#dc3545';
            } else if ("{{ $type }}" == 'warning') {
                background = '#ffc107';
            } else if ("{{ $type }}" == 'info') {
                background = '#17a2b8';
            } else if ("{{ $type }}" == 'dark') {
                background = '#343a40';
            } else if ("{{ $type }}" == 'light') {
                background = '#f8f9fa';
            } else if ("{{ $type }}" == 'secondary') {
                background = '#6c757d';
            } else if ("{{ $type }}" == 'primary') {
                background = '#007bff';
            }
            
            Toastify({
                text: "{{ $message }}",
                duration: 3000,
                close: true,
                gravity: "top",
                position: "center",
                backgroundColor: background,
            }).showToast()
        })
    </script>
@endpush
