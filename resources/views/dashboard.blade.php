<x-app-layout>
    <x-slot name="header">
        <h2 class="ui-page-heading">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="ui-page">
        <div class="relative overflow-hidden rounded-3xl border border-white/60 bg-gradient-to-br from-white/90 via-cyan-50/40 to-white/80 p-8 shadow-card backdrop-blur-md ring-1 ring-cyan-500/10 sm:p-10">
            <div class="pointer-events-none absolute -right-24 -top-24 h-56 w-56 rounded-full bg-gradient-to-br from-cyan-300/35 to-violet-300/25 blur-3xl"></div>
            <div class="relative">
                <p class="text-sm font-medium uppercase tracking-widest text-cyan-700/80">Welcome back</p>
                <p class="mt-3 font-display text-2xl font-semibold text-slate-900">{{ __("You're logged in!") }}</p>
                <p class="mt-2 max-w-lg text-slate-600">Use the sidebar to open your role tools. Notifications appear in the header.</p>
            </div>
        </div>
    </div>
</x-app-layout>
