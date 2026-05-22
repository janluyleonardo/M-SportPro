@php
    $logoUrl = asset('images/logo/LOGO.png');
    if (auth()->check() && !auth()->user()->is_super_admin && auth()->user()->club && auth()->user()->club->logo) {
        $logoUrl = asset(auth()->user()->club->logo);
    }
@endphp
<div class="container">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <img class="mx-auto" src="{{ $logoUrl }}" alt="icono-yackeline FS" width="160" height="160" style="filter:drop-shadow(1px 1px 2px black);">
        </div>
    </div>
</div>
