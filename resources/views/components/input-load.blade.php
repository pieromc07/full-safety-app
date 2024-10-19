@props(['id', 'placeholder', 'icon', 'name', 'value', 'label', 'req'])

@php
    $id = $id ?? md5('input-load' . rand(1, 1000));
    $placeholder = $placeholder ?? 'input load';
    $icon = $icon ?? 'bi-search';
    $name = $name ?? 'search';
    $value = $value ?? '';
    $label = $label ?? 'Input Load';
    $req = $req ?? true;
@endphp

@push('styles')
    <style type="text/css">
        #{{ $id }}-spinner {
            width: 1rem;
            height: 1rem;
            line-height: 1rem;
            position: absolute;
            left:  0.5rem;
        }
    </style>
@endpush

<div class="mb-3">
    <label class="form-label" for="{{ $id }}">
        {{ $label }} @if ($req) <span class="text-danger">*</span> @endif
    </label>
    <div class="input-group input-group-merge">
        <span  class="input-group-text">
            <i class="bi {{ $icon }}" id="{{ $id }}-icon"></i>
            <div class="spinner-border text-success d-none" id="{{ $id }}-spinner" role="status">
            </div>
        </span>
        <input class="form-control @error($name) is-invalid @enderror" placeholder="{{ $placeholder }}"
            id="{{ $id }}" name="{{ $name }}" value="{{ old($name, $value) }}"
            {{ $attributes }} />

        @error($name)
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>
