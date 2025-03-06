@props(['id', 'name', 'value', 'label', 'req', 'readonly', 'uppercase', 'checked', 'placement', 'placementTitle'])

@php
    $id = $id ?? md5('input-text' . rand(1, 1000));
    $name = $name ?? 'search';
    $value = $value ?? '';
    $label = $label ?? 'input';
    $req = $req ?? 1;
    $readonly = $readonly ?? false;
    $uppercase = $uppercase ?? false;
    $checked = $checked ?? false;
    $placement = $placement ?? false;
    $placementTitle = $placementTitle ?? '';
@endphp
<div class="mt-3">
    <div class="form-check form-check-custom form-check-solid">
        <input class="form-check-input" type="checkbox" id="{{ $id }}" name="{{ $name }}"
            value="{{ $value }}" @if ($checked) checked @endif
            @if ($readonly) disabled @endif>
        <label class="form-check-label" for="{{ $id }}">
            {{ $label }}
            @if ($req == 1)
                <span class="text-danger">*</span>
            @endif
        </label>
        {{-- Tooltip --}}
        @if ($placement)
            <div class="form-text ms-2">
                <a href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $placementTitle }}">
                    <i class="bi bi-question-circle"></i>
                </a>
            </div>
        @endif
    </div>
</div>
