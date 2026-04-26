@props(['id', 'page', 'lastPage', 'perPage', 'total', 'route'])

@php
    $id = $id ?? md5('pagination' . rand(1, 1000));
    $page = (int) ($page ?? 1);
    $lastPage = (int) ($lastPage ?? 1);
    $perPage = (int) ($perPage ?? 1);
    $total = (int) ($total ?? 0);
    $previous = max(1, $page - 1);
    $next = min($lastPage, $page + 1);
    $query = request()->query();
@endphp

@if ($lastPage > 1)
<div class="d-flex align-items-center gap-3">
    <span class="text-muted fs-7">{{ $total }} registros</span>
    <ul class="pagination pagination-circle mb-0">
        <li class="page-item previous @if ($page == 1) disabled @endif">
            <a class="page-link" href="{{ route($route, array_merge($query, ['page' => $previous])) }}"
                title="Anterior">
                <i class="ki-duotone ki-arrow-left fs-6"></i>
            </a>
        </li>
        @for ($i = 1; $i <= $lastPage; $i++)
            @if ($i == 1 || $i == $lastPage || abs($i - $page) <= 2)
                <li class="page-item @if ($page == $i) active @endif">
                    <a class="page-link" href="{{ route($route, array_merge($query, ['page' => $i])) }}">{{ $i }}</a>
                </li>
            @elseif ($i == 2 || $i == $lastPage - 1)
                <li class="page-item disabled"><span class="page-link">...</span></li>
            @endif
        @endfor
        <li class="page-item next @if ($page == $lastPage) disabled @endif">
            <a class="page-link" href="{{ route($route, array_merge($query, ['page' => $next])) }}"
                title="Siguiente">
                <i class="ki-duotone ki-arrow-right fs-6"></i>
            </a>
        </li>
    </ul>
</div>
@else
    @if ($total > 0)
        <span class="text-muted fs-7">{{ $total }} registros</span>
    @endif
@endif
