@extends('layouts.public')

@section('title', config('app.name'))

@section('content')
    @php
        $specCount = isset($specialties) ? $specialties->count() : 0;
        $docCount = isset($doctors) ? $doctors->count() : 0;
    @endphp

    <section class="ui-hero-skew relative pb-12 pt-10 sm:pb-16 sm:pt-14 lg:pb-24 lg:pt-20">
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-12 lg:grid-cols-12 lg:items-center lg:gap-10">
                <div class="animate-fade-up [animation-fill-mode:forwards] lg:col-span-7">
                    <span class="ui-stat-pill">Clinical intelligence · Human pace</span>
                    <h1 class="ui-marketing-heading mt-6 text-3xl leading-[1.12] sm:text-4xl lg:text-5xl lg:leading-[1.08]">
                        <span class="bg-gradient-to-r from-cyan-700 via-teal-600 to-cyan-800 bg-clip-text text-transparent">Book care</span>
                        <span class="text-slate-800"> with clarity — slots you can trust</span>
                    </h1>
                    <p class="mt-6 max-w-xl text-base leading-relaxed text-slate-600 sm:text-lg">
                        A full clinic platform with specialties, hourly booking, financial insight, and streamlined doctor approval — built to feel calm, precise, and quietly futuristic.
                    </p>
                    <div class="mt-10 flex flex-wrap gap-3">
                        <a href="{{ route('register') }}" class="ui-btn-primary min-h-0 px-8 py-3.5">Get started</a>
                        @auth
                            <a href="{{ route('bookings.create') }}" class="ui-btn-secondary min-h-0 px-8 py-3.5">Book appointment</a>
                        @else
                            <a href="{{ route('login') }}" class="ui-btn-secondary min-h-0 px-8 py-3.5">Book appointment</a>
                        @endauth
                    </div>

                    <div class="mt-12 grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <div class="ui-metric-tile">
                            <p class="relative text-[11px] font-semibold uppercase tracking-wider text-slate-500">Specialties</p>
                            <p class="relative mt-1 font-display text-3xl font-bold text-slate-900">{{ $specCount }}</p>
                            <p class="relative mt-1 text-xs text-slate-500">Areas of care</p>
                        </div>
                        <div class="ui-metric-tile">
                            <p class="relative text-[11px] font-semibold uppercase tracking-wider text-slate-500">Doctors</p>
                            <p class="relative mt-1 font-display text-3xl font-bold text-slate-900">{{ $docCount }}</p>
                            <p class="relative mt-1 text-xs text-slate-500">Verified profiles</p>
                        </div>
                        <div class="ui-metric-tile">
                            <p class="relative text-[11px] font-semibold uppercase tracking-wider text-slate-500">Scheduling</p>
                            <p class="relative mt-1 font-display text-3xl font-bold text-cyan-800">24/7</p>
                            <p class="relative mt-1 text-xs text-slate-500">Online booking</p>
                        </div>
                    </div>
                </div>
                <div class="relative lg:col-span-5">
                    <div
                        class="relative overflow-hidden rounded-3xl border border-white/60 bg-gradient-to-br from-white/95 to-cyan-50/70 p-6 shadow-[0_20px_60px_rgba(6,78,110,0.12)] ring-1 ring-cyan-500/15 sm:p-8"
                    >
                        <div
                            class="pointer-events-none absolute -right-16 -top-16 h-44 w-44 rounded-full bg-gradient-to-br from-cyan-400/45 to-violet-400/35 blur-2xl"
                        ></div>
                        <div
                            class="pointer-events-none absolute -bottom-10 -left-10 h-32 w-32 rounded-full bg-teal-300/25 blur-2xl"
                        ></div>
                        <p class="relative font-display text-sm font-semibold uppercase tracking-widest text-cyan-800/90">Live rhythm</p>
                        <ul class="relative mt-6 space-y-4 text-sm text-slate-600">
                            <li class="flex gap-3">
                                <span class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-cyan-500 to-teal-600 text-xs font-bold text-white shadow-md">✓</span>
                                <span>Hourly slots with conflict-safe scheduling</span>
                            </li>
                            <li class="flex gap-3">
                                <span class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-cyan-500 to-teal-600 text-xs font-bold text-white shadow-md">✓</span>
                                <span>Role-aware dashboards for every stakeholder</span>
                            </li>
                            <li class="flex gap-3">
                                <span class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-cyan-500 to-teal-600 text-xs font-bold text-white shadow-md">✓</span>
                                <span>Transparent rates and visit history</span>
                            </li>
                        </ul>
                        <div class="relative mt-8 rounded-2xl border border-cyan-100/80 bg-white/60 px-4 py-3 text-xs text-slate-600 backdrop-blur-sm">
                            <span class="font-semibold text-cyan-900">No double-booking</span> — slots are validated against doctor availability in real time.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="ui-section-skew">
        <div class="ui-section-skew-inner mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-10 flex flex-col gap-3 sm:mb-14">
                <span class="ui-section-label">Specialties</span>
                <h2 class="ui-marketing-heading text-2xl sm:text-3xl">Medical specialties</h2>
                <p class="max-w-2xl text-slate-600">Explore the areas of care available at our clinic.</p>
            </div>
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 sm:gap-6 lg:grid-cols-4">
                @forelse($specialties as $specialty)
                    <div class="group ui-bento-card p-0">
                        @if($specialty->image_path)
                            <div class="relative mb-0 overflow-hidden rounded-t-2xl">
                                <img
                                    src="{{ asset('storage/'.$specialty->image_path) }}"
                                    alt="{{ $specialty->name }}"
                                    class="h-36 w-full object-cover transition duration-500 group-hover:scale-105"
                                >
                                <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-slate-900/50 to-transparent opacity-80"></div>
                                <p class="absolute bottom-3 left-4 right-4 font-display font-semibold text-white drop-shadow">{{ $specialty->name }}</p>
                            </div>
                            <div class="p-5 pt-4">
                                <p class="text-sm leading-relaxed text-slate-600">{{ $specialty->description ?: 'Certified medical specialty at the clinic.' }}</p>
                            </div>
                        @else
                            <div class="p-5">
                                <h3 class="font-display font-semibold text-slate-900">{{ $specialty->name }}</h3>
                                <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $specialty->description ?: 'Certified medical specialty at the clinic.' }}</p>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="col-span-full rounded-2xl border border-dashed border-cyan-200/60 bg-white/50 p-10 text-center text-slate-500 backdrop-blur-sm">
                        No specialties yet.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <section class="relative pb-16 sm:pb-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-10 flex flex-col gap-3 sm:mb-14">
                <span class="ui-section-label">Our team</span>
                <h2 class="ui-marketing-heading text-2xl sm:text-3xl">Approved doctors</h2>
                <p class="max-w-2xl text-slate-600">Verified professionals ready to support your health journey.</p>
            </div>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                @forelse($doctors as $doctor)
                    <div
                        class="group relative flex flex-col overflow-hidden rounded-2xl border border-white/60 bg-white/80 p-6 shadow-card ring-1 ring-slate-900/[0.04] backdrop-blur-md transition duration-400 hover:-translate-y-1 hover:border-cyan-200/80 hover:shadow-card-hover"
                    >
                        <div
                            class="absolute -right-8 -top-8 h-28 w-28 rounded-full bg-gradient-to-br from-cyan-200/90 to-violet-200/50 opacity-50 blur-2xl transition duration-500 group-hover:opacity-100"
                        ></div>
                        <div class="relative flex-1">
                            <div class="mb-3 flex items-center gap-2">
                                <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-emerald-800">Verified</span>
                            </div>
                            <h3 class="font-display text-lg font-semibold text-slate-900">{{ $doctor->name }}</h3>
                            <p class="mt-2 text-sm text-slate-600">{{ $doctor->doctorSpecialties->pluck('name')->join(' - ') ?: 'No specialties listed' }}</p>
                            <div class="mt-5 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100/90 pt-4 text-sm">
                                <div>
                                    <span class="text-slate-500">Rate (€)</span>
                                    <span class="ms-2 font-semibold tabular-nums text-emerald-700">@eur($doctor->doctorProfile?->hourly_rate ?? 0)</span>
                                </div>
                            </div>
                            <p class="mt-2 text-sm text-slate-600">
                                <span class="text-slate-500">Education:</span> {{ $doctor->doctorProfile?->education_stage ?: 'Not specified' }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full rounded-2xl border border-dashed border-cyan-200/60 bg-white/50 p-10 text-center text-slate-500 backdrop-blur-sm">
                        No approved doctors yet.
                    </div>
                @endforelse
            </div>
            <div class="mt-10 flex justify-center">
                <a href="{{ route('public.doctors') }}" class="ui-btn-secondary min-h-0 px-8 py-3">Browse all doctors</a>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 pb-20 sm:px-6 lg:px-8">
        <div class="ui-cta-strip relative z-10">
            <div class="relative z-10 mx-auto max-w-2xl">
                <h2 class="font-display text-2xl font-bold text-white sm:text-3xl">Ready to book your next visit?</h2>
                <p class="mt-3 text-sm leading-relaxed text-cyan-50/95 sm:text-base">
                    Create an account to access the patient dashboard, or sign in if you already have one.
                </p>
                <div class="mt-8 flex flex-wrap justify-center gap-3">
                    @auth
                        <a href="{{ route('bookings.create') }}" class="inline-flex min-h-[2.75rem] items-center justify-center rounded-xl bg-white px-6 py-2.5 text-sm font-semibold text-cyan-900 shadow-lg transition hover:bg-cyan-50">Book now</a>
                    @else
                        <a href="{{ route('register') }}" class="inline-flex min-h-[2.75rem] items-center justify-center rounded-xl bg-white px-6 py-2.5 text-sm font-semibold text-cyan-900 shadow-lg transition hover:bg-cyan-50">Create account</a>
                        <a href="{{ route('login') }}" class="inline-flex min-h-[2.75rem] items-center justify-center rounded-xl border border-white/40 bg-white/10 px-6 py-2.5 text-sm font-semibold text-white backdrop-blur-sm transition hover:bg-white/20">Sign in</a>
                    @endauth
                </div>
            </div>
        </div>
    </section>
@endsection
