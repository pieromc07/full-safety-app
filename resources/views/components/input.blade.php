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
    <label class="form-label" for="{{ $id }}">
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

    <div class="input-group input-group-merge">
        <span id="" class="input-group-text">
            <i class="bi {{ $icon }}" id="{{ $id }}-icon"></i>
        </span>
        <input class="form-control @error($name) is-invalid @enderror" placeholder="{{ $placeholder }}"
            id="{{ $id }}" aria-label="{{ $placeholder }}" name="{{ $name }}"
            value="{{ old($name, $value) }}" {{ $attributes }} aria-describedby="{{ $name }}"
            @if ($readonly) readonly @endif
            @if ($uppercase) oninput="this.value = this.value.toUpperCase()" @endif>
        @error($name)
            <div class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </div>
        @enderror
    </div>
</div>
