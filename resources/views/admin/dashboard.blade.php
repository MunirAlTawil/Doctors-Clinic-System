<x-app-layout>
    <x-slot name="header">
        <h2 class="ui-page-heading">Admin dashboard</h2>
    </x-slot>

    <div class="ui-page">
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 md:gap-6 xl:grid-cols-3">
            @foreach($stats as $label => $value)
                <div class="group relative overflow-hidden rounded-2xl border border-white/60 bg-white/85 p-6 shadow-card ring-1 ring-slate-900/[0.04] backdrop-blur-sm transition duration-400 hover:-translate-y-1 hover:border-cyan-200/80 hover:shadow-card-hover">
                    <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-cyan-500 via-teal-400 to-violet-500 opacity-90 transition duration-300 group-hover:opacity-100"></div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-cyan-700/90">{{ str_replace('_', ' ', $label) }}</p>
                    <p class="font-display mt-3 text-3xl font-bold tracking-tight text-slate-900">{{ is_numeric($value) ? number_format((int) round((float) $value), 0, '.', '') : $value }}</p>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
