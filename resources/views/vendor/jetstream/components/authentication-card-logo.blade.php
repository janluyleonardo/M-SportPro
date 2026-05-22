@php
    $logoUrl = asset('images/logo/LOGO.png');
    if (auth()->check() && !auth()->user()->is_super_admin && auth()->user()->club && auth()->user()->club->logo) {
        $logoUrl = asset(auth()->user()->club->logo);
    }
@endphp
<a href="/">
    <img src="{{ $logoUrl }}" alt="icono-jackeline" width="200" height="200" style="filter:drop-shadow(1px 1px 2px black);">
</a>
