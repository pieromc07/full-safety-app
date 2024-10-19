@props(['id', 'method', 'action', 'role'])

@php
    $id = $id ?? md5('form' . rand(1, 1000));
    $method = $method ?? 'GET';
    $action = $action ?? '#';
    $role = $role ?? 'form';
@endphp


<form id="{{ $id }}" action="{{ $action }}" method="{{ $method }}" role="{{ $role }}"
    class="d-inline" {{ $attributes }}>
    @if (in_array($method, ['PUT', 'PATCH', 'DELETE', 'POST']))
        @csrf
    @endif

    {{ $slot }}
</form>
