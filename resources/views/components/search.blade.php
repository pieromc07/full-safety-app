@props(['id', 'placeholder', 'action', 'method', 'role'])

@php
    $id = $id ?? md5('search' . rand(1, 1000));
    $placeholder = $placeholder ?? 'Buscar...';
    $action = $action ?? '#';
    $method = $method ?? 'GET';
    $role = $role ?? 'search';
    $label = $placeholder ?? 'Buscar';
@endphp


<x-form-table id="{{ $id }}-form" action="{{ $action }}" method="{{ $method }}"
    role="{{ $role }}">
    <div class="form-floating mb-7">
        <input type="text" class="form-control" id="{{ $id }}" placeholder="{{ $placeholder }}" name="search"
            value="{{ request('search') }}" aria-label="Search" aria-describedby="search-addon"
            @if (request('search'))  @endif autocomplete="off">
        <label for="{{ $id }}" class="form-label text-gray-600">
            <i class="bi bi-search"></i>
            {{ $label }}
        </label>
    </div>
</x-form-table>


@push('scripts')
    <script type="text/javascript">
        $(document).ready(() => {
            let search = $('#{{ $id }}').val();
            $('#{{ $id }}').on('keypress', function(e) {
                if (e.key === 'Enter' && $(this).is(':focus') && $(this).val() !== '') {
                    $('#{{ $id }}-form').submit();
                } else {
                    if (e.key === 'Enter' && $(this).is(':focus') && $(this).val() === '' && search ===
                        '') {
                        e.preventDefault();
                    } 
                }
            });


            if (search) {
                $('#{{ $id }}').val(search);
                $('#{{ $id }}').focus();
                let length = $('#{{ $id }}').val().length;
                $('#{{ $id }}')[0].setSelectionRange(length, length);
            }
        });
    </script>
@endpush
