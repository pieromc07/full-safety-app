@props([
    'id',
    'placeholder',
    'icon',
    'name',
    'value',
    'label',
    'req',
    'readonly',
    'uppercase',
    'tooltip',
    'tooltipText',
])

@php
    $id = $id ?? md5('input-text' . rand(1, 1000));
    $placeholder = $placeholder ?? 'input';
    $icon = $icon ?? 'bi-search';
    $name = $name ?? 'search';
    $value = $value ?? '';
    $label = $label ?? $placeholder;
    $req = $req ?? 1;
    $readonly = $readonly ?? false;
    $uppercase = $uppercase ?? false;
    $tooltip = $tooltip ?? false;
    $tooltipText = $tooltipText ?? '';
@endphp

<div class="mb-3">
        <label for="" class="form-label">
            {{ $label }}
            @if ($req == 1)
                <span class="text-danger">*</span>
            @endif

            @if ($tooltip)
                <div class="form-text ms-2 d-inline">
                    <a href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $tooltipText }}">
                        <i class="bi bi-question-circle"></i>
                    </a>
                </div>
            @endif
        </label>
        <textarea class="form-control form-control-solid @error($name) is-invalid @enderror" id="{{ $id }}"
            name="{{ $name }}" placeholder="{{ $placeholder }}" {{ $attributes }} aria-label="{{ $placeholder }}"
            aria-describedby="{{ $name }}" @if ($readonly) readonly @endif name="{{ $name }}"
            @if ($uppercase) oninput="this.value = this.value.toUpperCase()" @endif data-kt-autosize="true">{{ old($name, $value) }}</textarea>
        @error($name)
            <div class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </div>
        @enderror
</div>
