@props([
    'readonly' => false,
    'view' => null,
    'permissions' => null,
    'checkGroup' => null,
    'rolePermissions' => null,
])

<div class="col-xs-12 col-sm-12 col-lg-6">
    <x-input type="text" id="description{{ $view }}" name="description{{ $view }}"
        placeholder="Descripción del Permiso" icon="bi-shield-fill-check" value="{{ $role->description }}"
        label="Descripción" readonly="{{ $readonly }}" autocomplete="off" />

</div>
<div class="col-xs-12 col-sm-12 col-lg-6">
    <x-input type="text" id="name{{ $view }}" name="name{{ $view }}" placeholder="Nombre del Permiso"
        icon="bi-shield-lock-fill" value="{{ $role->name }}" label="Nombre" readonly="{{ $readonly }}"
        autocomplete="off" />
</div>
<div class="col-xs-12 col-sm-12 col-lg-6 d-none">
    <x-input type="text" id="guard_name{{ $view }}" name="guard_name{{ $view }}"
        placeholder="Guard del Permiso" icon="bi-shield-lock-fill" value="{{ $role->guard_name }}" label="Guard"
        readonly="{{ $readonly }}" autocomplete="off" req="{{ false }}" />
</div>
@if ($permissions)
    <div class="col-xs-12 col-sm-12 col-lg-12 mt-4">
        <h3 class="text-start">Permisos</h3>
    </div>
    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="form-group">
            <div class="custom-control custom-radio">
                <input class="custom-control-input" type="radio" id="all-access" name="special" value="all-access"
                    onclick="checkAllS(1)">
                <label for="all-access" class="custom-control-label">Acceso Total</label>
            </div>
            <div class="custom-control custom-radio">
                <input class="custom-control-input" type="radio" id="no-access" name="special" value="no-access"
                    onclick="checkAllS(0)">
                <label for="no-access" class="custom-control-label">Ningún Acceso</label>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="row mt-3 mb-3 p-3 justify-content-center">
            <div class="table-responsive col-xs-12 col-sm-12 col-lg-10">
                <table class="table table-bordered table-hover boder-rounded">
                    <thead class="bg-light-dark">
                        <tr>
                            <th class="text-uppercase text-center" style="color: #000">
                                Modulo
                            </th>
                            <th class="text-uppercase text-center">
                                <i class="fa fa-info-circle" title="Seleccionar todos" style="color: #4267B2"></i>
                            </th>
                            <th class="text-uppercase text-center">
                                <i class="fa fa-eye" title="Ver" style="color: #F39C12"></i>
                            </th>
                            <th class="text-uppercase text-center">
                                <i class="fa fa-plus-square" title="Crear" style="color: #55A92C"></i>
                            </th>
                            <th class="text-uppercase text-center">
                                <i class="fa fa-cog" title="Editar" style="color: #00ACD6"></i>
                            </th>
                            <th class="text-uppercase text-center">
                                <i class="fa fa-trash" title="Eliminar" style="color: #D73925"></i>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!$rolePermissions && !$checkGroup)
                            @foreach ($permissions as $key => $values)
                                <tr>
                                    <td class="text-uppercase text-center">
                                        {{ $values[0]->subname }}
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" id="{{ $key }}-all" title="Seleccionar todos"
                                            onclick="checkAll('{{ $key }}')">
                                    </td>
                                    @foreach ($values as $permission)
                                        <td class="text-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                                id="{{ $permission->name }}" title="{{ $permission->description }}">
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        @else
                            @foreach ($permissions as $key => $values)
                                <tr>
                                    <td class="text-uppercase text-center">
                                      {{ $values[0]->subname }}
                                    </td>
                                    <td class="text-center">
                                        @if ($checkGroup->keys()->contains($key))
                                            @foreach ($checkGroup as $group => $check)
                                                @if ($group == $key)
                                                    <input type="checkbox" id="{{ $key }}-all"
                                                        title="Seleccionar todos"
                                                        onclick="checkAll('{{ $key }}')"
                                                        @if ($check->count() == 4) checked @endif>
                                                @endif
                                            @endforeach
                                        @else
                                            <input type="checkbox" id="{{ $key }}-all"
                                                title="Seleccionar todos" onclick="checkAll('{{ $key }}')">
                                        @endif
                                    </td>
                                    @foreach ($values as $permission)
                                        @if (count($rolePermissions) == 0)
                                            <td class="text-center">
                                                <input type="checkbox" name="permissions[]"
                                                    value="{{ $permission->id }}" id="{{ $permission->name }}"
                                                    title="{{ $permission->description }}">
                                            </td>
                                        @else
                                            @for ($i = 0; $i < count($rolePermissions); $i++)
                                                @if ($rolePermissions[$i] == $permission->id)
                                                    <td class="text-center">
                                                        <input type="checkbox" name="permissions[]"
                                                            value="{{ $permission->id }}"
                                                            id="{{ $permission->name }}"
                                                            title="{{ $permission->description }}" checked>
                                                    </td>
                                                @break
                                            @endif
                                            @if ($i == count($rolePermissions) - 1)
                                                <td class="text-center">
                                                    <input type="checkbox" name="permissions[]"
                                                        value="{{ $permission->id }}"
                                                        id="{{ $permission->name }}"
                                                        title="{{ $permission->description }}">
                                                </td>
                                            @endif
                                        @endfor
                                    @endif
                                @endforeach
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script type="text/javascript">


    function checkAll(modulo) {
      console.log(modulo);
        var checkboxes = document.querySelectorAll(`input[id^="${modulo}."]`);
        var checkboxAll = document.getElementById(`${modulo}-all`).checked;
        checkboxes.forEach((checkbox) => {
            checkbox.checked = checkboxAll;
        });
    }

    function checkAllS(checkboxAll) {
        if (checkboxAll == 1) {
            // seleccionar todos los checkbox que tengan el id (x-all)
            var checkboxes = document.querySelectorAll(`input[id$="-all"]`);
            // recorrer todos los checkbox y seleccionarlos y generar el evento onclick
            checkboxes.forEach((checkbox) => {
                checkbox.checked = true;
                checkbox.onclick();
            });
        } else {
            // seleccionar todos los checkbox que tengan el id (x-all)
            var checkboxes = document.querySelectorAll(`input[id$="-all"]`);
            // recorrer todos los checkbox y seleccionarlos y generar el evento onclick
            checkboxes.forEach((checkbox) => {
                checkbox.checked = false;
                checkbox.onclick();
            });
        }
    }
</script>
@endpush
