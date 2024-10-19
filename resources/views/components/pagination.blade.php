@props(['id', 'page', 'lastPage', 'perPage', 'total', 'route'])


@php
    $id = $id ?? md5('pagination' . rand(1, 1000));
    $page = $page ?? 1;
    $lastPage = $lastPage ?? 1;
    $perPage = $perPage ?? 1;
    $total = $total ?? 1;
    $previous = $page - 1;
    $next = $page + 1;
@endphp

<ul class="pagination pagination-circle">
    <li class="page-item previous @if ($page == 1) disabled @endif">
        <a class="page-link" href="{{ route($route, ['page' => $previous]) }}">
            <i class="previous"></i>
        </a>
    </li>
    @for ($i = 1; $i <= $lastPage; $i++)
        <li class="page-item @if ($page == $i) active @endif">
            <a class="page-link" href="{{ route($route, ['page' => $i]) }}">{{ $i }}</a>
        </li>
    @endfor
    <li class="page-item next @if ($page == $lastPage) disabled @endif">
        <a class="page-link" href="{{ route($route, ['page' => $next]) }}">
            <i class="next"></i>
        </a>
    </li>
</ul>
