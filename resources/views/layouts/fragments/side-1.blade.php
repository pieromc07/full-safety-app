@php
    $isActive = fn (string ...$names) => request()->routeIs(...$names) ? 'active' : '';
    $groupOpen = fn (string ...$names) => request()->routeIs(...$names) ? 'here show' : '';
@endphp
<!--begin::Sidebar-->
<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="250px"
    data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
    <!--begin::Main-->
    <div class="d-flex flex-column justify-content-between h-100 hover-scroll-overlay-y my-2 d-flex flex-column"
        id="kt_app_sidebar_main" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto"
        data-kt-scroll-dependencies="#kt_app_header" data-kt-scroll-wrappers="#kt_app_main" data-kt-scroll-offset="5px">
        <!--begin::Sidebar menu-->
        <div id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false"
            class="flex-column-fluid menu menu-sub-indention menu-column menu-rounded menu-active-bg mb-7">

            {{-- Dashboard --}}
            <div class="menu-item">
                <a class="menu-link {{ $isActive('home') }}" href="{{ route('home') }}">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-element-11 fs-2">
                            <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span>
                        </i>
                    </span>
                    <span class="menu-title">Panel de Control</span>
                </a>
            </div>

            {{-- Mantenimiento --}}
            <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ $groupOpen('checkpoint','enterprisetype','inspectiontype','unit','unittype','loadtype','producttype','company') }}">
                <span class="menu-link">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-wrench fs-2">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                    </span>
                    <span class="menu-title">Mantenimiento</span>
                    <span class="menu-arrow"></span>
                </span>
                <div class="menu-sub menu-sub-accordion">
                    <div class="menu-item">
                        <a class="menu-link {{ $isActive('checkpoint') }}" href="{{ route('checkpoint') }}">
                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                            <span class="menu-title">Puntos de Control</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link {{ $isActive('enterprisetype') }}" href="{{ route('enterprisetype') }}">
                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                            <span class="menu-title">Tipo de Empresa</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link {{ $isActive('inspectiontype') }}" href="{{ route('inspectiontype') }}">
                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                            <span class="menu-title">Tipo de Inspección</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link {{ $isActive('unit') }}" href="{{ route('unit') }}">
                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                            <span class="menu-title">Unidades de Medida</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link {{ $isActive('unittype') }}" href="{{ route('unittype') }}">
                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                            <span class="menu-title">Tipo de Unidad</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link {{ $isActive('loadtype') }}" href="{{ route('loadtype') }}">
                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                            <span class="menu-title">Tipo de Carga</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link {{ $isActive('producttype') }}" href="{{ route('producttype') }}">
                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                            <span class="menu-title">Tipo de Producto</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link {{ $isActive('company') }}" href="{{ route('company') }}">
                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                            <span class="menu-title">Empresa del Sistema</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Maestro --}}
            <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ $groupOpen('enterprise','targeted','target','category','category1','evidences','employee') }}">
                <span class="menu-link">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-book-open fs-2">
                            <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span>
                        </i>
                    </span>
                    <span class="menu-title">Maestro</span>
                    <span class="menu-arrow"></span>
                </span>
                <div class="menu-sub menu-sub-accordion">
                    <div class="menu-item">
                        <a class="menu-link {{ $isActive('enterprise') }}" href="{{ route('enterprise') }}">
                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                            <span class="menu-title">Empresas</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link {{ $isActive('targeted') }}" href="{{ route('targeted') }}">
                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                            <span class="menu-title">Dirigidos</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link {{ $isActive('target') }}" href="{{ route('target') }}">
                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                            <span class="menu-title">Tipo de Dirigidos</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link {{ $isActive('category') }}" href="{{ route('category') }}">
                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                            <span class="menu-title">Categorías</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link {{ $isActive('category1') }}" href="{{ route('category1') }}">
                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                            <span class="menu-title">Subcategorías</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link {{ $isActive('evidences') }}" href="{{ route('evidences') }}">
                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                            <span class="menu-title">Evidencias</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link {{ $isActive('employee') }}" href="{{ route('employee') }}">
                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                            <span class="menu-title">Personal</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Productos --}}
            <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ $groupOpen('products','productenterprises') }}">
                <span class="menu-link">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-flask fs-2">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                    </span>
                    <span class="menu-title">Productos</span>
                    <span class="menu-arrow"></span>
                </span>
                <div class="menu-sub menu-sub-accordion">
                    <div class="menu-item">
                        <a class="menu-link {{ $isActive('products') }}" href="{{ route('products') }}">
                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                            <span class="menu-title">Registro</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link {{ $isActive('productenterprises') }}" href="{{ route('productenterprises') }}">
                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                            <span class="menu-title">Asignar a Empresas</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Inspecciones --}}
            <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ $groupOpen('inspections','inspections.*') }}">
                <span class="menu-link">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-search-list fs-2">
                            <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                        </i>
                    </span>
                    <span class="menu-title">Inspecciones</span>
                    <span class="menu-arrow"></span>
                </span>
                <div class="menu-sub menu-sub-accordion">
                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs('inspections') && (int) request('type') === 1 ? 'active' : '' }}" href="{{ route('inspections', ['type' => 1]) }}">
                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                            <span class="menu-title">Operativas</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs('inspections') && (int) request('type') === 2 ? 'active' : '' }}" href="{{ route('inspections', ['type' => 2]) }}">
                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                            <span class="menu-title">Documentarias</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Diálogo Diario --}}
            <div class="menu-item">
                <a class="menu-link {{ $isActive('dialogues') }}" href="{{ route('dialogues') }}">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-message-text-2 fs-2">
                            <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                        </i>
                    </span>
                    <span class="menu-title">Diálogo Diario</span>
                </a>
            </div>

            {{-- Pausa Activa --}}
            <div class="menu-item">
                <a class="menu-link {{ $isActive('actives') }}" href="{{ route('actives') }}">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-heart fs-2">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                    </span>
                    <span class="menu-title">Pausa Activa</span>
                </a>
            </div>

            {{-- Prueba de Alcohol --}}
            <div class="menu-item">
                <a class="menu-link {{ $isActive('tests') }}" href="{{ route('tests') }}">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-thermometer fs-2">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                    </span>
                    <span class="menu-title">Prueba de Alcohol</span>
                </a>
            </div>

            {{-- Control GPS --}}
            <div class="menu-item">
                <a class="menu-link {{ $isActive('controls') }}" href="{{ route('controls') }}">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-geolocation fs-2">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                    </span>
                    <span class="menu-title">Control GPS</span>
                </a>
            </div>

            {{-- Movimiento de Unidades --}}
            <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ $groupOpen('unitmovements','unitmovements.*') }}">
                <span class="menu-link">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-truck fs-2">
                            <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span>
                        </i>
                    </span>
                    <span class="menu-title">Mov. Unidades</span>
                    <span class="menu-arrow"></span>
                </span>
                <div class="menu-sub menu-sub-accordion">
                    <div class="menu-item">
                        <a class="menu-link {{ $isActive('unitmovements') }}" href="{{ route('unitmovements') }}">
                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                            <span class="menu-title">Lista</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link {{ $isActive('unitmovements.create') }}" href="{{ route('unitmovements.create') }}">
                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                            <span class="menu-title">Nuevo Registro</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Reportes --}}
            <div class="menu-item">
                <a class="menu-link {{ $isActive('report.daily') }}" href="{{ route('report.daily') }}">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-chart-simple-2 fs-2">
                            <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span>
                        </i>
                    </span>
                    <span class="menu-title">Reporte Diario</span>
                </a>
            </div>

            {{-- Separador --}}
            <div class="menu-item pt-3">
                <div class="menu-content">
                    <span class="menu-heading fw-bold text-uppercase fs-7 text-muted">Administración</span>
                </div>
            </div>

            {{-- Seguridad --}}
            <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ $groupOpen('users','roles','permissions') }}">
                <span class="menu-link">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-shield-tick fs-2">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                    </span>
                    <span class="menu-title">Seguridad</span>
                    <span class="menu-arrow"></span>
                </span>
                <div class="menu-sub menu-sub-accordion">
                    <div class="menu-item">
                        <a class="menu-link {{ $isActive('users') }}" href="{{ route('users') }}">
                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                            <span class="menu-title">Usuarios</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link {{ $isActive('roles') }}" href="{{ route('roles') }}">
                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                            <span class="menu-title">Roles</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link {{ $isActive('permissions') }}" href="{{ route('permissions') }}">
                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                            <span class="menu-title">Permisos</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>
        <!--end::Sidebar menu-->
    </div>
    <!--end::Main-->
</div>
<!--end::Sidebar-->
