<header class="ui-float-header">
    <div class="ui-float-header-inner">
        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 font-display text-lg font-bold tracking-tight text-slate-900 sm:gap-3 sm:text-xl">
            <span class="ui-logo-mark ui-logo-mark--image mr-0 shrink-0 align-middle text-sm sm:inline-flex">@include('partials.brand-mark-icon')</span>
            <span class="align-middle">{{ config('app.name') }}</span>
        </a>
        <nav class="flex flex-1 flex-wrap items-center justify-end gap-1.5 text-xs sm:gap-2 sm:text-sm">
            @php
                $publicBase = 'ui-nav-pill';
                $publicActive = 'ui-nav-pill-active';
                $publicInactive = 'ui-nav-pill-inactive';
            @endphp
            <a
                href="{{ route('public.doctors') }}"
                class="{{ $publicBase }} {{ request()->routeIs('public.doctors') ? $publicActive : $publicInactive }}"
            >Doctors</a>
            <a
                href="{{ route('public.specialties') }}"
                class="{{ $publicBase }} {{ request()->routeIs('public.specialties') ? $publicActive : $publicInactive }}"
            >Specialties</a>
            <a
                href="{{ route('public.about') }}"
                class="{{ $publicBase }} {{ request()->routeIs('public.about') ? $publicActive : $publicInactive }}"
            >About</a>
            <a
                href="{{ route('public.contact') }}"
                class="{{ $publicBase }} {{ request()->routeIs('public.contact') ? $publicActive : $publicInactive }}"
            >Contact</a>
            @auth
                <a
                    href="{{ route('dashboard') }}"
                    class="ui-nav-pill rounded-xl bg-gradient-to-r from-cyan-600 to-teal-600 px-4 py-2 font-semibold text-white shadow-md shadow-cyan-900/25 ring-1 ring-white/20 transition hover:from-cyan-500 hover:to-teal-500"
                >Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="{{ $publicBase }} {{ $publicInactive }}">Login</a>
                <a
                    href="{{ route('register') }}"
                    class="ui-nav-pill rounded-xl bg-gradient-to-r from-cyan-600 to-teal-600 px-4 py-2 font-semibold text-white shadow-md shadow-cyan-900/25 ring-1 ring-white/20 transition hover:from-cyan-500 hover:to-teal-500"
                >Register</a>
            @endauth
        </nav>
    </div>
</header>
