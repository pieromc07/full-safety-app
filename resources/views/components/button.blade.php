@props(['id', 'btn', 'icon', 'text', 'position'])

@php
    $id = $id ?? md5('btn-text-icon' . rand(1, 1000));
    $btn = $btn ?? 'btn-primary';
    $icon = $icon ?? 'bi-plus-circle';
    $text = $text ?? 'Aqui va el texto';
    $position = $position ?? 'left';
@endphp

<button class="btn icon {{ $btn }}" id="{{ $id }}" {{ $attributes }}>
    @if ($position == 'left')
        <i class="bi {{ $icon }}"></i>
    @endif
    {{ $text }}
    @if ($position == 'right')
        <i class="bi {{ $icon }}"></i>
    @endif
</button>
