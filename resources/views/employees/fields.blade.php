@props(['readonly' => false, 'view' => null])
<div class="col-xs-12 col-sm-12 col-lg-6">
    <x-select id="id_document_types{{ $view }}" name="id_document_types{{ $view }}"
        label="Tipo de Documento" icon="bi-credit-card" placeholder="Seleccione un Tipo de Documento"
        disabled="{{ $readonly }}" req={{ true }} value="{{ $employee->id_document_types ?? '' }}">
        <x-slot name="options">
            @foreach ($documentTypes as $documentType)
                <option value="{{ $documentType->id_document_types }}">
                    {{ $documentType->name }}
                </option>
            @endforeach
        </x-slot>
    </x-select>
</div>

<div class="col-xs-12 col-sm-12 col-lg-6">
    <x-input-search-click type="text" id="document_number{{ $view }}"
        name="document_number{{ $view }}" placeholder="Número de Documento" icon="bi-credit-card-2-front"
        label="Número de Documento" readonly="{{ $readonly }}" autocomplete="off" req={{ true }}
        value="{{ $employee->document_number ?? '' }}" />
</div>

<div class="col-xs-12 col-sm-12 col-lg-6">
    <x-input type="text" id="fullname{{ $view }}" name="fullname{{ $view }}"
        placeholder="Nombre Completo" icon="bi-person" label="Nombre Completo" readonly="{{ $readonly }}"
        autocomplete="off" value="{{ $employee->fullname ?? '' }}" uppercase={{ true }} />
</div>

<div class="col-xs-12 col-sm-12 col-lg-6">
    <x-input type="text" id="address{{ $view }}" name="address{{ $view }}" placeholder="Dirección"
        icon="bi-geo-alt" label="Dirección" readonly="{{ $readonly }}" autocomplete="off" req={{ false }}
        value="{{ $employee->address ?? '' }}" uppercase={{ true }} />
</div>

<div class="col-xs-12 col-sm-12 col-lg-6">
    <x-select id="id_workstations{{ $view }}" name="id_workstations{{ $view }}"
        label="Puesto de Trabajo" icon="bi-person-workspace" placeholder="Seleccione un Puesto de Trabajo"
        disabled="{{ $readonly }}" value="{{ $employee->id_workstations }}">
        <x-slot name="options">
            @foreach ($workstations as $workstation)
                <option value="{{ $workstation->id_workstations }}">
                    {{ $workstation->name }}
                </option>
            @endforeach
        </x-slot>
    </x-select>
</div>

<div class="col-xs-12 col-sm-12 col-lg-6">
    <x-select id="id_branches{{ $view }}" name="id_branches{{ $view }}" label="Sucursal"
        icon="bi-geo-alt" placeholder="Seleccione una Sucursal" disabled="{{ $readonly }}"
        value="{{ $employee->id_branches }}">
        <x-slot name="options">
            @foreach ($branches as $branch)
                <option value="{{ $branch->id_branches }}"> {{ $branch->name }} </option>
            @endforeach
        </x-slot>
    </x-select>
</div>

<div class="col-xs-12 col-sm-12 col-lg-12 mt-4">
    <h3 class="text-start">Datos de Usuario</h3>
    <hr>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-lg-6 mb-4">
            <button type="button" class="btn btn-primary" id="generateUser" title="Generar Usuario">
                <i class="bi bi-person-plus-fill"></i> Generar Usuario
            </button>
        </div>
    </div>
</div>

<div class="col-xs-12 col-sm-12 col-lg-6">
    <x-input type="text" id="username{{ $view }}" name="username{{ $view }}"
        placeholder="Nombre de Usuario" icon="bi-person-fill" value="{{ $user->username }}" label="Nombre de Usuario"
        readonly="{{ $readonly }}" autocomplete="off" uppercase="{{ true }}"
        req="{{ true }}" />
</div>
<div class="col-xs-12 col-sm-12 col-lg-6">
    <x-input-password-show type="password" id="password{{ $view }}" name="password{{ $view }}"
        placeholder="Contraseña del Usuario" icon="bi-lock-fill" value="{{ $user->password }}" label="Contraseña"
        readonly="{{ $readonly }}" autocomplete="off" req="{{ $view == 'show' ? false : true }}" />
    <span id="passwordHelpBlock" style="display: none;" class="form-text"></span>
</div>
<div class="col-xs-12 col-sm-12 col-lg-6">
    <x-input-password-show type="password" id="password_confirmation{{ $view }}"
        name="password_confirmation{{ $view }}" placeholder="Confirmar Contraseña" icon="bi-lock-fill"
        value="{{ $user->password }}" label="Confirmar Contraseña" readonly="{{ $readonly }}"
        autocomplete="off" req="{{ $view == 'show' ? false : true }}" />
    <span id="passwordConfirmationHelpBlock" style="display: none;" class="form-text"></span>
</div>
@if ($roles->count() > 0)
    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-12 col-lg-12 mt-4">
            <h3 class="text-start">Roles</h3>
        </div>
        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="row mt-3 mb-3 p-3 justify-content-center">
                <div class="table-responsive col-xs-12 col-sm-12 col-lg-10">
                    <table class="table table-bordered table-hover boder-rounded">
                        <thead class="bg-light-dark">
                            <tr>
                                <th class="text-uppercase text-center" style="color: #000">
                                    Rol
                                </th>
                                <th class="text-uppercase text-center">
                                    <i class="fa fa-puzzle-piece" title="Seleccionar" style="color: #4267B2"></i>
                                </th>
                                <th class="text-uppercase text-center">
                                    Descripción
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @error('id_roles{{ $view }}')
                                <tr>
                                    <td colspan="3" class="text-center">
                                        <span class="text-danger">{{ $message }}</span>
                                    </td>
                                </tr>
                            @enderror
                            @foreach ($roles as $role)
                                <tr>
                                    <td class="text-uppercase text-center">
                                        {{ $role->name }}
                                    </td>
                                    <td class="text-center">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox"
                                                id="id_roles{{ $view }}{{ $role->id }}"
                                                name="id_roles{{ $view }}[]" value="{{ $role->id }}"
                                                {{ $user->roles->contains('name', $role->name) ? 'checked' : '' }}
                                                {{ $readonly ? 'disabled' : '' }}>
                                            <label class="custom-control-label"
                                                for="id_roles{{ $view }}{{ $role->id }}"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        {{ $role->description }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endif

@push('scripts')
    <script type="text/javascript">
        $(document).ready((e) => {
            $("#password").blur(validatePassword);
            $("#password").keyup(validatePassword);
            $("#password_confirmation").blur(validatePasswordConfirmation);
            $("#password_confirmation").keyup(validatePasswordConfirmation);

            $('#document_number').keypress((e) => {
                let id_document_types = $('#id_document_types').val();
                let value = $('#document_number').val() == '' ? '' : $('#document_number').val();
                if (id_document_types != 1 && id_document_types != 2) {
                    alertMessage("Seleccione un Tipo de Documento", "warning");
                    e.preventDefault();
                }
                if (e.which < 48 || e.which > 57) {
                    alertMessage("Ingrese solo números", "warning");
                    e.preventDefault();
                }
            });

            $('#document_number-search').click((e) => {
                let tipoDocumento = $('#id_document_types').val();
                let numeroDocumento = $('#document_number').val();
                let URL =
                    `{{ url('/reniec') }}?id_document_types=${tipoDocumento}&document_number=${numeroDocumento}`;
                if (tipoDocumento == 1) {
                    if (numeroDocumento.length == 8) {
                        fetch(URL)
                            .then(response => response.json())
                            .then(response => {
                                if (response.success) {
                                    const {
                                        nombre
                                    } = response.data;
                                    $('#fullname').val(nombre);
                                }
                            })
                            .catch(err => {
                                alertMessage(err.message, "error");
                            });
                    } else {
                        alertMessage("El DNI debe tener 8 digitos", "warning");
                    }
                } else if (tipoDocumento == 2) {
                    if (numeroDocumento.length == 11) {
                        fetch(URL)
                            .then(response => response.json())
                            .then(response => {
                                if (response.success) {
                                    const {
                                        nombre_o_razon_social,
                                        direccion
                                    } = response.data;
                                    $('#fullname').val(nombre_o_razon_social);
                                    $('#address').val(direccion);
                                }
                            })
                            .catch(err => {
                                alertMessage(err.message, "error");
                            });
                    } else {
                        alertMessage("El RUC debe tener 11 digitos", "warning");
                    }
                } else {
                    alertMessage("Seleccione un Tipo de Documento DNI / RUC", "warning");
                }
            })

            $('#generateUser').click((e) => {
                let document_number = $('#document_number').val();
                let fullname = $('#fullname').val();

                if (document_number == '') {
                    alertMessage("Ingrese un Número de Documento", "warning");
                    return;
                }

                if (fullname == '') {
                    alertMessage("Ingrese un Nombre Completo", "warning");
                    return;
                }

                let username = document_number;
                let password = document_number;
                $('#username').val(username);
                $('#password').val(password);
                $('#password_confirmation').val(password);
            });

        });

        function validatePassword() {
            let confirmPassword = $("#password_confirmation").val();
            let password = $(this).val();
            if (confirmPassword != '' && password != confirmPassword) {
                $("#passwordHelpBlock").text("Las contraseñas no coinciden").css("display", "block");
                $('#passwordHelpBlock').addClass("invalid-feedback");
                $("#password").addClass("is-invalid");
                $("#password_confirmation").addClass("is-invalid");
                $("#password_confirmation").trigger("blur");
                return;
            }

            if (password.length < 8) {
                $("#passwordHelpBlock").text("La contraseña debe tener al menos 8 caracteres").css(
                    "display",
                    "block");
                $('#passwordHelpBlock').addClass("invalid-feedback");
                $("#password").addClass("is-invalid");
                return;
            }

            $('#passwordHelpBlock').removeClass("invalid-feedback");
            $("#password").removeClass("is-invalid");
            $("#password_confirmation").trigger("blur");


            $('#passwordHelpBlock').addClass("valid-feedback");
            $("#passwordHelpBlock").text("La contraseña es válida").css("display", "block");
            $("#password").addClass("is-valid");
        }

        function validatePasswordConfirmation() {
            let confirmPassword = $("#password_confirmation").val();
            let password = $("#password").val();
            if (password != '' && confirmPassword != password) {
                $("#passwordConfirmationHelpBlock").text("Las contraseñas no coinciden").css("display", "block");
                $('#passwordConfirmationHelpBlock').addClass("invalid-feedback");
                $("#password_confirmation").addClass("is-invalid");
                $("#password").trigger("blur");
                return;
            }

            if (confirmPassword.length < 8) {
                $("#passwordConfirmationHelpBlock").text("La contraseña debe tener al menos 8 caracteres").css(
                    "display", "block");
                $('#passwordConfirmationHelpBlock').addClass("invalid-feedback");
                $("#password_confirmation").addClass("is-invalid");
                return;
            }

            $('#passwordConfirmationHelpBlock').removeClass("invalid-feedback");
            $("#password_confirmation").removeClass("is-invalid");
            if (confirmPassword == password) {
                $("#passwordConfirmationHelpBlock").text("La contraseña si coincide").css("display", "block");
                $('#passwordConfirmationHelpBlock').addClass("valid-feedback");
            }
            $("#password_confirmation").addClass("is-valid");
            $("#password").trigger("blur");

        }
    </script>
@endpush
