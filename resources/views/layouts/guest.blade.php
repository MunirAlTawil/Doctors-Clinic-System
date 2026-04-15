<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @php
            $headPageTitle = '';
            if (isset($title)) {
                if ($title instanceof \Illuminate\View\ComponentSlot) {
                    $headPageTitle = $title->isNotEmpty() ? trim(strip_tags($title->toHtml())) : '';
                } else {
                    $headPageTitle = trim(strip_tags((string) $title));
                }
            }
            $documentTitle = $headPageTitle !== '' ? $headPageTitle.' — '.config('app.name') : config('app.name');
        @endphp
        <title>{{ $documentTitle }}</title>

        @include('partials.head-icons')

        <link rel="preconnect" href="https://fonts.bunny.net">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="relative min-h-screen overflow-x-hidden font-sans antialiased text-slate-800">
        <div class="pointer-events-none fixed inset-0 -z-10" aria-hidden="true">
            <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-[#0c1a2e] to-slate-900"></div>
            <div
                class="absolute -left-1/3 top-0 h-[85%] w-[70%] rotate-[18deg] bg-gradient-to-br from-cyan-500/20 via-teal-600/10 to-transparent blur-3xl"
            ></div>
            <div
                class="absolute -right-1/4 bottom-0 h-[75%] w-[60%] -rotate-[10deg] bg-gradient-to-tl from-violet-600/15 via-cyan-500/8 to-transparent blur-3xl"
            ></div>
            <div
                class="absolute inset-0 opacity-[0.04]"
                style="background-image: repeating-linear-gradient(-35deg, transparent, transparent 16px, rgba(34, 211, 238, 0.6) 16px, rgba(34, 211, 238, 0.6) 17px);"
            ></div>
            <div class="absolute left-1/4 top-1/3 h-[min(50vh,22rem)] w-[min(70vw,32rem)] animate-mesh-breathe rounded-full bg-cyan-400/12 blur-3xl"></div>
        </div>

        {{-- Centered split: branding + form side by side, no huge empty middle --}}
        <div class="relative flex min-h-screen flex-col items-center justify-center px-4 py-10 sm:px-6 lg:px-8">
            <div
                class="pointer-events-none absolute inset-y-8 left-1/2 hidden w-[min(92vw,56rem)] -translate-x-1/2 rounded-[2rem] bg-gradient-to-br from-cyan-500/[0.07] via-transparent to-violet-500/[0.05] lg:block"
                aria-hidden="true"
            ></div>

            <div
                class="relative z-10 flex w-full max-w-[1040px] flex-col items-stretch gap-10 lg:flex-row lg:items-center lg:justify-center lg:gap-10 xl:gap-14"
            >
                <div class="w-full max-w-md shrink-0 text-center lg:max-w-[22rem] lg:text-left xl:max-w-sm">
                    <a href="/" class="inline-flex items-center justify-center gap-3 text-white lg:justify-start">
                        <span class="ui-logo-mark ui-logo-mark--image text-lg">@include('partials.brand-mark-icon')</span>
                        <span class="font-display text-2xl font-bold tracking-tight">{{ config('app.name') }}</span>
                    </a>
                    <p class="mt-5 text-sm leading-relaxed text-cyan-100/85">
                        Healthcare workflows for admin, doctor, and patient — precise scheduling with a calm interface.
                    </p>
                    <div class="mt-8 flex flex-wrap items-center justify-center gap-2 lg:justify-start">
                        <a href="{{ url('/') }}" class="ui-nav-pill border-cyan-400/30 bg-white/10 text-cyan-50 ring-1 ring-white/10 backdrop-blur-sm transition hover:bg-white/15">Home</a>
                        <a href="{{ route('public.doctors') }}" class="ui-nav-pill border-cyan-400/30 bg-white/10 text-cyan-50 ring-1 ring-white/10 backdrop-blur-sm transition hover:bg-white/15">Doctors</a>
                        <a href="{{ route('public.specialties') }}" class="ui-nav-pill border-cyan-400/30 bg-white/10 text-cyan-50 ring-1 ring-white/10 backdrop-blur-sm transition hover:bg-white/15">Specialties</a>
                        <a href="{{ route('public.about') }}" class="ui-nav-pill border-cyan-400/30 bg-white/10 text-cyan-50 ring-1 ring-white/10 backdrop-blur-sm transition hover:bg-white/15">About</a>
                        <a href="{{ route('public.contact') }}" class="ui-nav-pill border-cyan-400/30 bg-white/10 text-cyan-50 ring-1 ring-white/10 backdrop-blur-sm transition hover:bg-white/15">Contact</a>
                    </div>
                </div>

                <div class="w-full min-w-0 max-w-md shrink-0 sm:max-w-lg lg:max-w-[26rem] xl:max-w-md">
                    <div class="animate-fade-up [animation-fill-mode:forwards]">
                        <div class="ui-auth-panel px-6 py-10 sm:px-10">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
