@props(['id', 'maxWidth'])

@php
    $id = $id ?? md5('form-modal' . rand(1, 1000));
    $maxWidth = $maxWidth ?? 'md';
@endphp

<x-modal id="{{ $id }}" maxWidth="{{ $maxWidth }}" >
    <x-slot name="slot">
        <form class="modal-content" id="{{ $id }}-form" {{ $attributes }}>
            <div class="modal-header">
                <h4 class="modal-title" id="{{ $id }}Label">
                    {{ $title ?? 'Formulario' }}
                </h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="bi bi-x"></i>
                </button>
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
            <div class="modal-footer">
                {{ $footer ?? '' }}
            </div>
        </form>
    </x-slot>

</x-modal>
