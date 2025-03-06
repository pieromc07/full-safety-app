@props(['id', 'placeholder', 'icon', 'name', 'label', 'req', 'disabled', 'value', 'formId'])

@php
    $id = $id ?? md5('input-text' . rand(1, 1000));
    $placeholder = $placeholder ?? 'Search';
    $icon = $icon ?? 'bi-search';
    $name = $name ?? 'search';
    $label = $label ?? $placeholder;
    $req = $req ?? true;
    $disabled = $disabled ?? false;
    $value = $value ?? '';
@endphp

<div class="mb-3">
    <label class="form-label" for="{{ $id }}">
        {{ $label }}
        @if ($req)
            <span class="text-danger">*</span>
        @endif
    </label>
    <select class="form-select  @error($name) is-invalid @enderror" id="{{ $id }}" {{ $attributes }}
        name="{{ $name }}" @if ($disabled) disabled @endif>
        <option value="">{{ $placeholder }}</option>
        {{ $options }}
    </select>
    @error($name)
        <div class="invalid-feedback" role="alert">
            {{ $message }}
        </div>
    @enderror
</div>

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function(e) {
            $('#{{ $id }}').select2();
            let element;
            if ('{{ $value }}' !== '' && '{{ $value }}' !== 'null') {
                element = '{{ $value }}';
            }
            if ('{{ old($name) }}' !== '' && '{{ old($name) }}' !== 'null') {
                element = '{{ old($name) }}';
            }
            if (!element) {
                $('#{{ $id }} option').each(function() {
                    if ($(this).data('local-selected') == 1) {
                        element = $(this).val();
                    }
                });
            }

            $('#{{ $id }}').val(element).trigger('change');

            $('#{{ $id }}').on('change', function() {
                $('#{{ $formId }}').submit();
            });
        });
    </script>
@endpush
