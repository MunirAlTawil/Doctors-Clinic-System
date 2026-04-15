@extends('layouts.public')

@section('title', 'About — '.config('app.name'))

@section('content')
    <div class="ui-page max-w-6xl pb-20">
        <section class="ui-public-hero mb-10 lg:mb-12">
            <div class="ui-public-hero-inner">
                <p class="ui-public-hero-kicker">Our story</p>
                <h1 class="ui-marketing-heading mt-4 max-w-4xl text-3xl leading-tight sm:text-4xl lg:text-[2.75rem] lg:leading-[1.1]">
                    <span class="bg-gradient-to-r from-slate-900 via-cyan-900 to-teal-800 bg-clip-text text-transparent">{{ $content?->title ?? 'About us' }}</span>
                </h1>
                <p class="mt-4 max-w-2xl text-base leading-relaxed text-slate-600 sm:text-lg">
                    {{ $content?->subtitle ?? 'Smart clinic platform focused on calm scheduling and clear communication between patients, doctors, and administrators.' }}
                </p>
            </div>
        </section>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-12 lg:gap-10">
            <aside class="lg:col-span-4">
                <div class="sticky top-28 space-y-4">
                    <div class="rounded-2xl border border-white/60 bg-gradient-to-br from-cyan-50/80 via-white to-violet-50/40 p-6 shadow-card backdrop-blur-md ring-1 ring-cyan-500/10">
                        <p class="font-display text-sm font-semibold uppercase tracking-widest text-cyan-800/80">Why {{ config('app.name') }}</p>
                        <ul class="mt-5 space-y-4">
                            <li class="flex gap-3">
                                <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white text-cyan-600 shadow-sm ring-1 ring-cyan-100">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                </span>
                                <span class="text-sm leading-snug text-slate-700">Structured workflows for every role in the clinic.</span>
                            </li>
                            <li class="flex gap-3">
                                <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white text-cyan-600 shadow-sm ring-1 ring-cyan-100">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </span>
                                <span class="text-sm leading-snug text-slate-700">Hourly booking with safeguards against double-booking.</span>
                            </li>
                            <li class="flex gap-3">
                                <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white text-cyan-600 shadow-sm ring-1 ring-cyan-100">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                                </span>
                                <span class="text-sm leading-snug text-slate-700">Transparent rates and visit history patients can trust.</span>
                            </li>
                        </ul>
                    </div>
                    <div class="rounded-2xl border border-dashed border-cyan-200/60 bg-white/40 p-5 text-center backdrop-blur-sm">
                        <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Need an appointment?</p>
                        <a href="{{ route('register') }}" class="mt-3 inline-flex w-full items-center justify-center rounded-xl bg-gradient-to-r from-cyan-600 to-teal-600 px-4 py-3 text-sm font-semibold text-white shadow-md transition hover:from-cyan-500 hover:to-teal-500">Create account</a>
                    </div>
                </div>
            </aside>

            <div class="lg:col-span-8">
                <article class="ui-article-frame">
                    @if($content?->image_path)
                        <div class="relative overflow-hidden">
                            <img
                                src="{{ asset('storage/'.$content->image_path) }}"
                                class="max-h-[28rem] w-full object-cover"
                                alt="{{ $content?->title ?? 'About' }}"
                            >
                            <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-slate-950/40 to-transparent"></div>
                        </div>
                    @endif
                    <div class="p-6 sm:p-10 lg:p-12">
                        <div class="ui-article-body max-w-none whitespace-pre-wrap">{{ $content?->body ?? 'No content yet.' }}</div>
                    </div>
                </article>
            </div>
        </div>
    </div>
@endsection
