@extends('layouts.public')

@section('title', 'Doctors — '.config('app.name'))

@section('content')
    @php
        $totalListed = method_exists($doctors, 'total') ? $doctors->total() : $doctors->count();
    @endphp

    <div class="ui-page max-w-7xl pb-20">
        <section class="ui-public-hero">
            <div class="ui-public-hero-inner">
                <p class="ui-public-hero-kicker">Directory</p>
                <h1 class="ui-marketing-heading mt-4 max-w-3xl text-3xl leading-tight sm:text-4xl lg:text-[2.75rem] lg:leading-[1.1]">
                    <span class="bg-gradient-to-r from-slate-900 via-cyan-900 to-teal-800 bg-clip-text text-transparent">Find the right doctor</span>
                </h1>
                <p class="mt-4 max-w-2xl text-base leading-relaxed text-slate-600 sm:text-lg">
                    Search by name or narrow down by specialty. Every profile reflects verified, approved clinicians on the platform.
                </p>
                <div class="mt-8 flex flex-wrap items-center gap-4">
                    <div class="flex items-center gap-3 rounded-2xl border border-white/80 bg-white/60 px-4 py-3 shadow-sm backdrop-blur-sm">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-cyan-500 to-teal-600 text-white shadow-md">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </span>
                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">Listed</p>
                            <p class="font-display text-xl font-bold text-slate-900">{{ $totalListed }}</p>
                        </div>
                    </div>
                    <p class="max-w-xs text-sm text-slate-500">
                        Hourly rates shown are set by each doctor and may vary by visit type.
                    </p>
                </div>
            </div>
        </section>

        <div class="ui-public-filter">
            <div class="mb-5 flex flex-col gap-1 border-b border-slate-100/90 pb-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="font-display text-lg font-semibold text-slate-900">Refine results</h2>
                    <p class="text-sm text-slate-500">Combine search with specialty filters.</p>
                </div>
            </div>
            <form method="GET" class="grid grid-cols-1 gap-4 md:grid-cols-12 md:items-end">
                <div class="md:col-span-5">
                    <label class="mb-1.5 flex items-center gap-2 text-sm font-medium text-slate-700">
                        <svg class="h-4 w-4 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        Search doctor
                    </label>
                    <input type="text" name="q" value="{{ request('q') }}" class="ui-input" placeholder="Name or keyword…">
                </div>
                <div class="md:col-span-5">
                    <label class="mb-1.5 flex items-center gap-2 text-sm font-medium text-slate-700">
                        <svg class="h-4 w-4 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                        Specialty
                    </label>
                    <select name="specialty_id" class="ui-input">
                        <option value="">All specialties</option>
                        @foreach($specialties as $specialty)
                            <option value="{{ $specialty->id }}" @selected((string)request('specialty_id') === (string)$specialty->id)>{{ $specialty->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-3 md:col-span-2">
                    <button type="submit" class="ui-btn-primary w-full shrink-0">Apply</button>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse($doctors as $doctor)
                @php
                    $image = $doctor->doctorProfile?->profile_image;
                    $imageUrl = $image ? (str_starts_with($image, 'http') ? $image : asset('storage/'.$image)) : null;
                    $specs = $doctor->doctorSpecialties->pluck('name')->filter();
                @endphp
                <article class="group ui-doc-card">
                    <div class="ui-doc-card-top" aria-hidden="true"></div>
                    <div class="flex flex-1 flex-col p-6">
                        <div class="flex gap-4">
                            @if($imageUrl)
                                <div class="relative shrink-0">
                                    <img
                                        src="{{ $imageUrl }}"
                                        alt="{{ $doctor->name }}"
                                        class="h-20 w-20 rounded-2xl border-2 border-white object-cover shadow-lg ring-2 ring-cyan-100/80 transition duration-500 group-hover:ring-cyan-300/60"
                                    >
                                    <span class="absolute -bottom-1 -right-1 flex h-6 w-6 items-center justify-center rounded-full bg-gradient-to-br from-emerald-400 to-teal-600 text-[10px] font-bold text-white shadow-md ring-2 ring-white" title="Verified">✓</span>
                                </div>
                            @else
                                <div class="relative flex h-20 w-20 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-cyan-100 via-teal-50 to-violet-100 text-2xl font-bold text-cyan-900 shadow-inner ring-2 ring-white">
                                    {{ strtoupper(substr($doctor->name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="min-w-0 flex-1">
                                <h3 class="font-display text-lg font-semibold leading-snug text-slate-900">{{ $doctor->name }}</h3>
                                <div class="mt-2 flex flex-wrap gap-1.5">
                                    @forelse($specs->take(3) as $s)
                                        <span class="inline-flex rounded-lg border border-cyan-100 bg-cyan-50/80 px-2 py-0.5 text-[11px] font-medium text-cyan-900">{{ $s }}</span>
                                    @empty
                                        <span class="text-xs text-slate-400">Specialty pending</span>
                                    @endforelse
                                    @if($specs->count() > 3)
                                        <span class="text-[11px] font-medium text-slate-400">+{{ $specs->count() - 3 }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex items-end justify-between border-t border-slate-100/90 pt-5">
                            <div>
                                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Hourly rate</p>
                                <p class="font-display text-2xl font-bold tabular-nums text-emerald-700">{{ number_format((float)($doctor->doctorProfile?->hourly_rate ?? 0), 2) }}</p>
                            </div>
                            <div class="rounded-xl bg-slate-50/90 px-3 py-2 text-right ring-1 ring-slate-200/80">
                                <p class="text-[10px] font-medium uppercase tracking-wide text-slate-400">Platform</p>
                                <p class="text-xs font-semibold text-slate-600">Approved</p>
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full">
                    <div class="rounded-2xl border border-dashed border-cyan-200/70 bg-gradient-to-br from-white/80 to-cyan-50/40 px-8 py-16 text-center backdrop-blur-sm">
                        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <p class="font-display text-lg font-semibold text-slate-800">No doctors match</p>
                        <p class="mx-auto mt-2 max-w-md text-sm text-slate-500">Try clearing the specialty filter or using a different search term.</p>
                    </div>
                </div>
            @endforelse
        </div>

        @if($doctors->hasPages())
            <div class="mt-12 border-t border-slate-200/60 pt-10">
                <div class="ui-pagination-wrap">
                    {{ $doctors->links() }}
                </div>
            </div>
        @endif
    </div>
@endsection
