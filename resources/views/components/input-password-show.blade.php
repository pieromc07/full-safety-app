@props(['id', 'placeholder', 'icon', 'name', 'value', 'label', 'req', 'readonly', 'uppercase'])

@php
    $id = $id ?? md5('input-text' . rand(1, 1000));
    $placeholder = $placeholder ?? 'input';
    $icon = $icon ?? 'bi-search';
    $name = $name ?? 'search';
    $value = $value ?? '';
    $label = $label ?? $placeholder;
    $req = $req ?? true;
    $readonly = $readonly ?? false;
    $uppercase = $uppercase ?? false;
@endphp

<div class="mb-3">
    <label class="form-label" for="{{ $id }}">
        {{ $label }}
        @if ($req)
            <span class="text-danger">*</span>
        @endif
    </label>
    <div class="input-group input-group-merge">
        <span class="input-group-text">
            <i class="bi {{ $icon }}" id="{{ $id }}-icon"></i>
        </span>
        <input class="form-control @error($name) is-invalid @enderror" placeholder="{{ $placeholder }}"
            id="{{ $id }}" aria-label="{{ $placeholder }}" name="{{ $name }}"
            value="{{ old($name, $value) }}" {{ $attributes }} aria-describedby="{{ $name }}"
            @if ($readonly) readonly @endif
            @if ($uppercase) oninput="this.value = this.value.toUpperCase()" @endif>
        <button class="btn btn-secondary btn-sm" type="button" id={{ $id . '-search' }}>
            <i id="{{ $id . '-icon-search' }}" class="fas fa-eye-slash"></i>
        </button>
        @error($name)
            <div class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </div>
        @enderror
    </div>
</div>

@push('scripts')
    <script type="text/javascript">
        $(document).ready(() => {
            $('#{{ $id }}-search').on('click', () => {
                let input = $('#{{ $id }}');
                let icon = $('#{{ $id }}-icon-search');
                if (input.attr('type') === 'password') {
                    input.attr('type', 'text');
                    icon.removeClass('fas fa-eye-slash');
                    icon.addClass('fas fa-eye');
                } else {
                    input.attr('type', 'password');
                    icon.removeClass('fas fa-eye');
                    icon.addClass('fas fa-eye-slash');
                }
            });
        });
    </script>
@endpush
