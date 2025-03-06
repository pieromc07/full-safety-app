@props([
    'readonly' => false,
    'view' => null,
])
<div class="col-xs-12 col-sm-12 col-lg-6">
    <x-input type="text" id="username{{ $view }}" name="username{{ $view }}"
        placeholder="Nombre de Usuario" icon="bi-person-fill" value="{{ $user->username }}" label="Nombre de Usuario"
        readonly="{{ $readonly }}" autocomplete="off" uppercase="{{ true }}" req="{{ true }}" />
</div>
<div class="col-xs-12 col-sm-12 col-lg-6">
    <x-input-password-show type="password" id="password{{ $view }}" name="password{{ $view }}"
        placeholder="Contraseña del Usuario" icon="bi-lock-fill" value="" label="Contraseña"
        readonly="{{ $readonly }}" autocomplete="off" req="{{ $view == 'show' ? false : true }}" />
    <span id="passwordHelpBlock" style="display: none;" class="form-text"></span>
</div>
<div class="col-xs-12 col-sm-12 col-lg-6">
    <x-input-password-show type="password" id="password_confirmation{{ $view }}"
        name="password_confirmation{{ $view }}" placeholder="Confirmar Contraseña" icon="bi-lock-fill"
        value="" label="Confirmar Contraseña" readonly="{{ $readonly }}" autocomplete="off"
        req="{{ $view == 'show' ? false : true }}" />
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
        $(document).ready(function() {
            $("#password").blur(validatePassword);
            $("#password").keyup(validatePassword);
            $("#password_confirmation").blur(validatePasswordConfirmation);
            $("#password_confirmation").keyup(validatePasswordConfirmation);
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

        function alertMessage(message, background, color = "000000", position = "center", gravity = "top", duration =
            3000) {
            Toastify({
                text: `
                    ${message}
                `,
                duration,
                gravity,
                position,
                style: {
                    background: `#${background}`,
                    color: `#${color}`,
                },
                stopOnFocus: true,
                close: true,
            }).showToast();
        }
    </script>
@endpush
