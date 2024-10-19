<html lang="es">
<!--begin::Head-->

<head>
    <base href="" />
    <title>
        {{ config('app.name') }} | @yield('title')
    </title>
    <meta charset="utf-8" />
    <meta name="description"
        content="Sistema de gestión de Ventas, Compras, Inventario, Clientes, Proveedores, Usuarios, Roles, Permisos, Facturacion Electronica y Sunat || Management system for Sales, Purchases, Inventory, Customers, Suppliers, Users, Roles, Permits, Electronic Billing and Sunat" />
    <meta name="keywords"
        content="Sistema, Gestión, Administrativa, Ventas, Compras, Inventario, Clientes, Proveedores, Usuarios, Roles, Permisos, Facturacion Electronica, Sunat, System, Management, Administrative, Sales, Purchasing, Inventory, Clients, Suppliers, Users, Roles, Permissions, Electronic Billing, Sunat" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Icons -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon" />
    <!-- ./Icons -->

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <!-- ./Fonts -->

    <!-- Styles -->

    <!-- Core CSS -->
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!-- ./Core CSS -->

    <!-- Plugins CSS -->
    <!-- ./Plugins CSS -->

    <!-- Custom CSS -->
    @stack('styles')
    <!-- ./Custom CSS -->

    <!-- ./Styles -->
    <script>
        // Frame-busting to prevent site from being loaded within a frame without permission (click-jacking) if (window.top != window.self) { window.top.location.replace(window.self.location.href); }
    </script>
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_body" class="app-blank">
    <!--begin::Theme mode setup on page load-->
    <script>
        var defaultThemeMode = "light";
        var themeMode;
        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if (localStorage.getItem("data-bs-theme") !== null) {
                    themeMode = localStorage.getItem("data-bs-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }
            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }
            document.documentElement.setAttribute("data-bs-theme", themeMode);
        }
    </script>
    <!--end::Theme mode setup on page load-->
    <!--begin::Root-->
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <!--begin::Authentication - Sign-in -->
        <div class="d-flex flex-column flex-lg-row flex-column-fluid">
            <!--begin::Aside-->
            <div class="d-flex flex-column flex-lg-row-auto bg-primary w-xl-600px positon-xl-relative">
                <!--begin::Wrapper-->
                <div class="d-flex flex-column position-xl-fixed top-0 bottom-0 w-xl-600px scroll-y">
                    <!--begin::Header-->
                    <div class="d-flex flex-row-fluid flex-column text-center p-5 p-lg-10 pt-lg-20">
                        <!--begin::Logo-->
                        <a href="../dist/index.html" class="py-2 py-lg-20">
                            <img alt="Logo" src="{{ asset('assets/media/logos/logo.png') }}"
                                class="h-100px h-lg-150px" />
                        </a>
                        <!--end::Logo-->
                        <!--begin::Title-->
                        <h1 class="d-none d-lg-block fw-bold text-white fs-2qx pb-5 pb-md-10">Welcome to Saul HTML Free
                        </h1>
                        <!--end::Title-->
                        <!--begin::Description-->
                        <p class="d-none d-lg-block fw-semibold fs-2 text-white">Plan your blog post by choosing a topic
                            creating
                            <br />an outline and checking facts
                        </p>
                        <!--end::Description-->
                    </div>
                    <!--end::Header-->
                    <!--begin::Illustration-->
                    <div class="d-none d-lg-block d-flex flex-row-auto bgi-no-repeat bgi-position-x-center bgi-size-contain bgi-position-y-bottom min-h-100px min-h-lg-350px"
                        style="background-image: url(assets/media/illustrations/sketchy-1/17.png)"></div>
                    <!--end::Illustration-->
                </div>
                <!--end::Wrapper-->
            </div>
            <!--begin::Aside-->
            <!--begin::Body-->
            <div class="d-flex flex-column flex-lg-row-fluid py-10">
                <!--begin::Content-->
                <div class="d-flex flex-center flex-column flex-column-fluid">
                    <!--begin::Wrapper-->
                    <div class="w-lg-500px p-10 p-lg-15 mx-auto">
                        @yield('content')
                    </div>
                    <!--end::Wrapper-->
                </div>
                <!--end::Content-->
                <!--begin::Footer-->
                <div class="d-flex flex-center flex-wrap fs-6 p-5 pb-0">
                    <!--begin::Links-->
                    <div class="d-flex flex-center fw-semibold fs-6">
                        <a href="#" class="text-muted text-hover-primary px-2" target="_blank">
                            {{ __('About') }}
                        </a>
                        <a href="#" class="text-muted text-hover-primary px-2" target="_blank">
                            {{ __('Support') }}
                        </a>
                        <a href="#" class="text-muted text-hover-primary px-2" target="_blank">
                            {{ __('Purchase') }}
                        </a>
                    </div>
                    <!--end::Links-->
                </div>
                <!--end::Footer-->
            </div>
            <!--end::Body-->
        </div>
        <!--end::Authentication - Sign-in-->
    </div>
    <!--end::Root-->
    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/custom/authentication/sign-in/general.js') }}"></script>
    <!--end::Global Javascript Bundle-->
    @stack('scripts')
</body>
<!--end::Body-->

</html>
