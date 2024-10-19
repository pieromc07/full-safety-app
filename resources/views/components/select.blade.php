@props(['id', 'placeholder', 'icon', 'name', 'label', 'req', 'disabled', 'value', 'filter' => false])

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
    @if (!$filter)
      <label class="form-label" for="{{ $id }}">
          {{ $label }}
          @if ($req)
              <span class="text-danger">*</span>
          @endif
      </label>
    @endif
    <select class="form-select  @error($name) is-invalid @enderror" id="{{ $id }}" {{ $attributes }}
        name="{{ $name }}" @if ($disabled) disabled @endif>
        @if (!$filter)
          <option value="">{{ $placeholder }}</option>
        @endif
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
            if('{{ $value }}' !== '' && '{{ $value }}' !== 'null') {
              element = '{{ $value }}';
            }

            if('{{ old($name) }}' !== '' && '{{ old($name) }}' !== 'null') {
              element = '{{ old($name) }}';
            }


            $('#{{ $id }}').val(element).trigger('change');
        });
    </script>
@endpush
