@extends('layouts.app')

@section('title', 'Seguridad')
@section('page', 'Ver Usuario')

@push('styles')
@endpush

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                @include('security.users.fields', ['readonly' => true, 'view' => 'show'])
            </div>
        </div>
        <div class="card-footer">
            <x-link-text-icon id="btn-back" btn="btn-secondary" title="Volver" position="left" text="Volver"
                icon="bi-arrow-left" href="{{ route('users') }}" />
        </div>
    </div>
@endsection
