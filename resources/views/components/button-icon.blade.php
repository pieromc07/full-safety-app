@props(['id', 'btn', 'icon', 'title', 'disabled' => false, 'text' => ''])

@php
    $id = $id ?? md5('btn-icon' . rand(1, 1000));
    $btn = $btn ?? 'btn-primary';
    $icon = $icon ?? 'bi-plus-circle';
    $title = $title ?? '';
    $text = $text ?? '';
@endphp

<button class="btn {{ $btn }} btn-sm" title="{{ $title }}" {{ $attributes }} id="{{ $id }}" {{ $disabled ? 'disabled' : '' }}>
    <i class="bi {{ $icon }}" style="font-size: 1.2em;"></i>
    {{ $text }}
</button>
