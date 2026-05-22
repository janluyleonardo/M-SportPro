@php
    $logoUrl = asset('images/logo/LOGO.png');
    if (auth()->check() && !auth()->user()->is_super_admin && auth()->user()->club && auth()->user()->club->logo) {
        $logoUrl = asset(auth()->user()->club->logo);
    }
@endphp
<div>
    <img src="{{ $logoUrl }}" alt="icono-yackeline FS" width="60" height="60" style="filter:drop-shadow(1px 1px 2px black);">
</div>
