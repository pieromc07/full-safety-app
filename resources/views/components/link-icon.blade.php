@props(['id', 'btn', 'icon', 'title'])

@php
    $id = $id ?? md5('btn-icon' . rand(1, 1000));
    $btn = $btn ?? 'btn-primary';
    $icon = $icon ?? 'bi-plus-circle';
    $title = $title ?? '';
@endphp

<a class="btn {{ $btn }}" title="{{ $title }}" {{ $attributes }}>
    <i class="bi {{ $icon }} " style="font-size: 1.2em;"></i>
</a>
