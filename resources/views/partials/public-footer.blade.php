<footer class="relative z-10 mt-auto border-t border-white/50 bg-gradient-to-b from-white/60 to-slate-50/90 backdrop-blur-xl">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-10 md:grid-cols-2 lg:grid-cols-4">
            <div class="lg:col-span-2">
                <div class="flex items-center gap-3">
                    <span class="ui-logo-mark ui-logo-mark--image text-sm">@include('partials.brand-mark-icon')</span>
                    <span class="font-display text-lg font-bold text-slate-900">{{ config('app.name') }}</span>
                </div>
                <p class="mt-4 max-w-md text-sm leading-relaxed text-slate-600">
                    Care-first scheduling for modern practices — clear slots, transparent rates, and workflows that keep patients, doctors, and admins aligned.
                </p>
            </div>
            <div>
                <p class="font-display text-xs font-semibold uppercase tracking-widest text-slate-500">Explore</p>
                <ul class="mt-4 space-y-2.5 text-sm">
                    <li><a href="{{ route('public.doctors') }}" class="text-slate-700 transition hover:text-cyan-700">Doctors</a></li>
                    <li><a href="{{ route('public.specialties') }}" class="text-slate-700 transition hover:text-cyan-700">Specialties</a></li>
                    <li><a href="{{ route('public.about') }}" class="text-slate-700 transition hover:text-cyan-700">About</a></li>
                    <li><a href="{{ route('public.contact') }}" class="text-slate-700 transition hover:text-cyan-700">Contact</a></li>
                </ul>
            </div>
            <div>
                <p class="font-display text-xs font-semibold uppercase tracking-widest text-slate-500">Account</p>
                <ul class="mt-4 space-y-2.5 text-sm">
                    @auth
                        <li><a href="{{ route('dashboard') }}" class="text-slate-700 transition hover:text-cyan-700">Dashboard</a></li>
                    @else
                        <li><a href="{{ route('login') }}" class="text-slate-700 transition hover:text-cyan-700">Login</a></li>
                        <li><a href="{{ route('register') }}" class="text-slate-700 transition hover:text-cyan-700">Register</a></li>
                    @endauth
                    <li><a href="{{ url('/') }}" class="text-slate-700 transition hover:text-cyan-700">Home</a></li>
                </ul>
            </div>
        </div>
        <div class="mt-10 flex flex-wrap items-center justify-between gap-4 border-t border-slate-200/80 pt-8 text-xs text-slate-500">
            <span>© {{ date('Y') }} {{ config('app.name') }}</span>
            <span class="uppercase tracking-widest text-cyan-800/60">Precision · Calm · Clear</span>
        </div>
    </div>
</footer>
