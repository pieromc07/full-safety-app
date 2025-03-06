@props(['readonly' => false, 'view' => null])

<div class="col-xs-12 col-sm-12 col-lg-6">
    <x-input type="text" id="name{{ $view }}" name="name{{ $view }}"
        placeholder="Nombre del Puesto de Trabajo" icon="bi-person-workspace" value="{{ $workstation->name }}"
        label="Nombre del Puesto" readonly="{{ $readonly }}" autocomplete="off" uppercase={{ true }} />
</div>
