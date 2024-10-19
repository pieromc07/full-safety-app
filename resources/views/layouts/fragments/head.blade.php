<head>
    <base href="" />
    <title>
        {{ config('app.name') }} | @yield('page')
    </title>
    <meta charset="utf-8" />
    <meta name="description"
        content="Sistema de gestión de Ventas, Compras, Inventario, Clientes, Proveedores, Usuarios, Roles, Permisos, Facturacion Electronica y Sunat || Management system for Sales, Purchases, Inventory, Customers, Suppliers, Users, Roles, Permits, Electronic Billing and Sunat" />
    <meta name="keywords"
        content="Sistema, Gestión, Administrativa, Ventas, Compras, Inventario, Clientes, Proveedores, Usuarios, Roles, Permisos, Facturacion Electronica, Sunat, System, Management, Administrative, Sales, Purchasing, Inventory, Clients, Suppliers, Users, Roles, Permissions, Electronic Billing, Sunat" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

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
    <link href="{{ asset('assets/plugins/custom/toastify/toastify.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- ./Core CSS -->

    <!-- Plugins CSS -->
    <!-- ./Plugins CSS -->

    <!-- Custom CSS -->
    @stack('styles')
    <!-- ./Custom CSS -->

    <!-- ./Styles -->

</head>
