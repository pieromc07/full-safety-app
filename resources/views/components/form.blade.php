@props(['id', 'method', 'action', 'role'])

@php
    $id = $id ?? md5('form' . rand(1, 1000));
    $method = $method ?? 'GET';
    $action = $action ?? '#';
    $role = $role ?? 'form';
@endphp


<form id="{{ $id }}" action="{{ $action }}" method="{{ $method }}" role="{{ $role }}"
    {{ $attributes }} class="row">
    @if ($method != 'GET' || $method != 'get')
        @csrf
    @endif
    {{ $slot }}
</form>
