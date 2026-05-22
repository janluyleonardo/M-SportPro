@php
    $logoUrl = asset('images/logo/LOGO.png');
    if (auth()->check() && !auth()->user()->is_super_admin && auth()->user()->club && auth()->user()->club->logo) {
        $logoUrl = asset(auth()->user()->club->logo);
    }
@endphp
<img src="{{ $logoUrl }}" {{ $attributes->merge(['class' => 'h-12 w-auto']) }} alt="Club Logo">
