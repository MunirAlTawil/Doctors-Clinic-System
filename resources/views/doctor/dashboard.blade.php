<x-app-layout>
    <x-slot name="header">
        <h2 class="ui-page-heading">Doctor dashboard</h2>
    </x-slot>

    <div class="ui-page max-w-6xl">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="ui-card ui-card-hover p-5">
                <p class="text-sm font-medium text-cyan-800">Appointments</p>
                <p class="mt-2 font-display text-3xl font-bold text-slate-900">{{ $stats['appointments'] }}</p>
            </div>
            <div class="ui-card ui-card-hover p-5">
                <p class="text-sm font-medium text-cyan-800">Your total earnings</p>
                <p class="mt-2 font-display text-3xl font-bold text-emerald-700">{{ number_format((float)$stats['earnings'], 2) }}</p>
            </div>
            <div class="ui-card ui-card-hover p-5">
                <p class="text-sm font-medium text-cyan-800">Clinic fee retained</p>
                <p class="mt-2 font-display text-3xl font-bold text-rose-700">{{ number_format((float)$stats['profit_taken'], 2) }}</p>
            </div>
        </div>
    </div>
</x-app-layout>
