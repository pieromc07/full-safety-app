@props(['readonly' => false, 'view' => null])

<div class="col-xs-12 col-sm-12 col-lg-6">
    <x-input type="text" id="description{{ $view }}" name="description{{ $view }}"
        placeholder="Descripción del Permiso" icon="bi-shield-fill-check" value="{{ $permission->description }}"
        label="Descripción" readonly="{{ $readonly }}" autocomplete="off" />
</div>
<div class="col-xs-12 col-sm-12 col-lg-6">
    <x-input type="text" id="name{{ $view }}" name="name{{ $view }}" placeholder="Nombre del Permiso"
        icon="bi-shield-lock-fill" value="{{ $permission->name }}" label="Nombre" readonly="{{ $readonly }}"
        autocomplete="off" />
</div>
<div class="col-xs-12 col-sm-12 col-lg-6 d-none">
    <x-input type="text" id="guard_name{{ $view }}" name="guard_name{{ $view }}"
        placeholder="Guard del Permiso" icon="bi-shield-lock-fill" value="{{ $permission->guard_name }}" label="Guard"
        readonly="{{ $readonly }}" autocomplete="off" req="{{ false }}" />
</div>
<div class="col-xs-12 col-sm-12 col-lg-6">
    <x-input type="text" id="group{{ $view }}" name="group{{ $view }}"
        placeholder="Grupo del Permiso" icon="bi-grid-3x3-gap" value="{{ $permission->group }}" label="Grupo"
        readonly="{{ $readonly }}" autocomplete="off" />
</div>
