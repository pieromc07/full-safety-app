@props(['id', 'maxWidth'])

@php
    $id = $id ?? md5('modal' . rand(1, 1000));
    $maxWidth = [
        'sm' => 'modal-sm',
        'md' => 'modal-md',
        'lg' => 'modal-lg',
        'xl' => 'modal-xl',
        'full' => 'modal-full',
    ][$maxWidth ?? 'md'];
@endphp

<div id="{{ $id }}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="{{ $id }}Label"
    aria-hidden="true" {{ $attributes }}>

    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable {{ $maxWidth }}" role="document">

        <div class="modal-content">
            {{ $slot }}
        </div>

    </div>
</div>
