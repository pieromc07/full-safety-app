@extends('layouts.app')
@section('title', 'Inicio')
@section('page', 'Panel de Control')

@section('content')
    {{-- Tarjetas de estadísticas --}}
    <div class="row g-5 g-xl-8 mb-5">
        <div class="col-xl-3 col-md-6">
            <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-xl-100"
                style="background-color: #1E1E2D;">
                <div class="card-header pt-5">
                    <div class="card-title d-flex flex-column">
                        <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $stats['inspections'] }}</span>
                        <span class="text-gray-400 pt-1 fw-semibold fs-6">Inspecciones</span>
                    </div>
                </div>
                <div class="card-body d-flex align-items-end pt-0">
                    <a href="{{ route('inspections') }}" class="btn btn-sm btn-light">Ver todas</a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-flush h-xl-100" style="background-color: #F1416C;">
                <div class="card-header pt-5">
                    <div class="card-title d-flex flex-column">
                        <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $stats['dialogues'] }}</span>
                        <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Dialogos Diarios</span>
                    </div>
                </div>
                <div class="card-body d-flex align-items-end pt-0">
                    <a href="{{ route('dialogues') }}" class="btn btn-sm btn-light">Ver todos</a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-flush h-xl-100" style="background-color: #7239EA;">
                <div class="card-header pt-5">
                    <div class="card-title d-flex flex-column">
                        <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $stats['pauses'] }}</span>
                        <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Pausas Activas</span>
                    </div>
                </div>
                <div class="card-body d-flex align-items-end pt-0">
                    <a href="{{ route('actives') }}" class="btn btn-sm btn-light">Ver todas</a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-flush h-xl-100" style="background-color: #50CD89;">
                <div class="card-header pt-5">
                    <div class="card-title d-flex flex-column">
                        <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $stats['tests'] }}</span>
                        <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Pruebas de Alcohol</span>
                    </div>
                </div>
                <div class="card-body d-flex align-items-end pt-0">
                    <a href="{{ route('tests') }}" class="btn btn-sm btn-light">Ver todas</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Segunda fila --}}
    <div class="row g-5 g-xl-8 mb-5">
        <div class="col-xl-3 col-md-6">
            <div class="card card-flush h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex align-items-center mb-2">
                        <div class="symbol symbol-50px me-3">
                            <span class="symbol-label bg-light-primary">
                                <i class="ki-duotone ki-compass fs-2x text-primary"><span class="path1"></span><span class="path2"></span></i>
                            </span>
                        </div>
                        <div>
                            <span class="fs-2hx fw-bold text-dark">{{ $stats['gps'] }}</span>
                            <span class="text-gray-400 fw-semibold d-block fs-6">Controles GPS</span>
                        </div>
                    </div>
                    <a href="{{ route('controls') }}" class="btn btn-sm btn-light-primary">Ver controles</a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-flush h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex align-items-center mb-2">
                        <div class="symbol symbol-50px me-3">
                            <span class="symbol-label bg-light-info">
                                <i class="ki-duotone ki-building fs-2x text-info"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            </span>
                        </div>
                        <div>
                            <span class="fs-2hx fw-bold text-dark">{{ $stats['enterprises'] }}</span>
                            <span class="text-gray-400 fw-semibold d-block fs-6">Empresas</span>
                        </div>
                    </div>
                    <a href="{{ route('enterprise') }}" class="btn btn-sm btn-light-info">Ver empresas</a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-flush h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex align-items-center mb-2">
                        <div class="symbol symbol-50px me-3">
                            <span class="symbol-label bg-light-warning">
                                <i class="ki-duotone ki-people fs-2x text-warning"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                            </span>
                        </div>
                        <div>
                            <span class="fs-2hx fw-bold text-dark">{{ $stats['employees'] }}</span>
                            <span class="text-gray-400 fw-semibold d-block fs-6">Personal</span>
                        </div>
                    </div>
                    <a href="{{ route('employee') }}" class="btn btn-sm btn-light-warning">Ver personal</a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-flush h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex align-items-center mb-2">
                        <div class="symbol symbol-50px me-3">
                            <span class="symbol-label bg-light-danger">
                                <i class="ki-duotone ki-shield-tick fs-2x text-danger"><span class="path1"></span><span class="path2"></span></i>
                            </span>
                        </div>
                        <div>
                            <span class="fs-2hx fw-bold text-dark">{{ $stats['users'] }}</span>
                            <span class="text-gray-400 fw-semibold d-block fs-6">Usuarios</span>
                        </div>
                    </div>
                    <a href="{{ route('users') }}" class="btn btn-sm btn-light-danger">Ver usuarios</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Últimas inspecciones --}}
    <div class="row g-5 g-xl-8">
        <div class="col-12">
            <div class="card card-flush">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-dark">Últimas Inspecciones</span>
                        <span class="text-muted mt-1 fw-semibold fs-7">Registros más recientes</span>
                    </h3>
                    <div class="card-toolbar">
                        <a href="{{ route('inspections') }}" class="btn btn-sm btn-light-primary">Ver todas</a>
                    </div>
                </div>
                <div class="card-body py-3">
                    <div class="table-responsive">
                        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                            <thead>
                                <tr class="fw-bold text-muted">
                                    <th class="min-w-50px">Folio</th>
                                    <th class="min-w-120px">Fecha</th>
                                    <th class="min-w-120px">Tipo</th>
                                    <th class="min-w-150px">Transportista</th>
                                    <th class="min-w-120px">Punto de Control</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentInspections as $insp)
                                    <tr>
                                        <td>
                                            <span class="text-dark fw-bold d-block fs-6">{{ $insp->folio }}</span>
                                        </td>
                                        <td>
                                            <span class="text-dark fw-semibold d-block fs-7">{{ $insp->date }}</span>
                                            <span class="text-muted fw-semibold d-block fs-7">{{ $insp->hour }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-light-{{ $insp->inspectionType?->name === 'Operativa' ? 'primary' : 'info' }} fs-7">
                                                {{ $insp->inspectionType?->name ?? '-' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-dark fw-semibold d-block fs-7">{{ $insp->enterpriseTransport?->name ?? '-' }}</span>
                                        </td>
                                        <td>
                                            <span class="text-dark fw-semibold d-block fs-7">{{ $insp->checkpoint?->name ?? '-' }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-5">
                                            No hay inspecciones registradas aún
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
