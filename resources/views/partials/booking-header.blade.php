<header class="ui-float-header">
    <div class="ui-float-header-inner">
        <a href="{{ url('/') }}" class="font-display text-lg font-bold tracking-tight text-slate-900 sm:text-xl">
            <span class="ui-logo-mark ui-logo-mark--image mr-0 shrink-0 align-middle text-sm sm:inline-flex">@include('partials.brand-mark-icon')</span>
            <span class="align-middle">{{ config('app.name') }}</span>
        </a>
        <div class="flex flex-1 flex-wrap items-center justify-end gap-1.5 text-xs sm:gap-2 sm:text-sm">
            <a
                href="{{ route('patient.dashboard') }}"
                class="ui-nav-pill transition {{ request()->routeIs('patient.dashboard') ? 'ui-nav-pill-active' : 'ui-nav-pill-inactive' }}"
            >My appointments</a>
            <a
                href="{{ route('bookings.create') }}"
                class="ui-nav-pill transition {{ request()->routeIs('bookings.*') ? 'ui-nav-pill-active' : 'ui-nav-pill-inactive' }}"
            >Book appointment</a>
            <a href="{{ url('/') }}" class="ui-nav-pill ui-nav-pill-inactive">Home</a>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button
                    type="submit"
                    class="ui-nav-pill rounded-xl border border-rose-200/90 bg-white/90 px-3 py-2 font-medium text-rose-700 shadow-sm transition hover:bg-rose-50"
                >Log out</button>
            </form>
        </div>
    </div>
</header>
