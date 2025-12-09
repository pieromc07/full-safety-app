@component('mail::message')
# Reporte de Levantamiento

**Fecha:** {{ $date }}

**Descripción:**
{{ $description }}

---

@if($imgOne)
## Evidencia 1
<img src="{{ $imgOne }}" style="max-width:350px;">
@endif

@if($imgTwo)
## Evidencia 2
<img src="{{ $imgTwo }}" style="max-width:350px;">
@endif

@endcomponent
