@extends('layouts.public')

@section('title', 'Specialties — '.config('app.name'))

@section('content')
    @php
        $totalListed = method_exists($specialties, 'total') ? $specialties->total() : $specialties->count();
    @endphp

    <div class="ui-page max-w-7xl pb-20">
        <section class="ui-public-hero">
            <div class="ui-public-hero-inner">
                <p class="ui-public-hero-kicker">Clinical focus</p>
                <h1 class="ui-marketing-heading mt-4 max-w-3xl text-3xl leading-tight sm:text-4xl lg:text-[2.75rem] lg:leading-[1.1]">
                    <span class="bg-gradient-to-r from-slate-900 via-teal-900 to-cyan-800 bg-clip-text text-transparent">Medical specialties</span>
                </h1>
                <p class="mt-4 max-w-2xl text-base leading-relaxed text-slate-600 sm:text-lg">
                    Explore departments and care pathways. Each card highlights how we structure services around patient needs.
                </p>
                <div class="mt-8 flex flex-wrap gap-4">
                    <div class="flex items-center gap-3 rounded-2xl border border-white/80 bg-white/60 px-4 py-3 shadow-sm backdrop-blur-sm">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-violet-500 to-cyan-600 text-white shadow-md">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                        </span>
                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">Catalog</p>
                            <p class="font-display text-xl font-bold text-slate-900">{{ $totalListed }} areas</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="ui-public-filter">
            <div class="mb-5 flex flex-col gap-1 border-b border-slate-100/90 pb-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="font-display text-lg font-semibold text-slate-900">Search the list</h2>
                    <p class="text-sm text-slate-500">Filter specialties by keyword.</p>
                </div>
            </div>
            <form method="GET" class="flex flex-col gap-4 sm:flex-row sm:items-end">
                <div class="min-w-0 flex-1">
                    <label class="mb-1.5 flex items-center gap-2 text-sm font-medium text-slate-700">
                        <svg class="h-4 w-4 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        Keyword
                    </label>
                    <input type="text" name="q" value="{{ request('q') }}" class="ui-input" placeholder="e.g. cardiology, pediatrics…">
                </div>
                <button type="submit" class="ui-btn-primary w-full sm:w-auto">Search</button>
            </form>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($specialties as $index => $specialty)
                @php
                    $img = $specialty->image_path ? asset('storage/'.$specialty->image_path) : null;
                    $ordinal = $specialties instanceof \Illuminate\Contracts\Pagination\Paginator
                        ? (int) $specialties->firstItem() + (int) $index
                        : $index + 1;
                @endphp
                <article class="group ui-spec-card">
                    @if($img)
                        <div class="relative aspect-[4/3] overflow-hidden">
                            <img
                                src="{{ $img }}"
                                alt="{{ $specialty->name }}"
                                class="h-full w-full object-cover transition duration-700 ease-out group-hover:scale-105"
                            >
                            <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-slate-950/85 via-slate-900/25 to-transparent"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-5">
                                <p class="font-display text-xl font-bold text-white drop-shadow-sm">{{ $specialty->name }}</p>
                            </div>
                            <span class="absolute right-4 top-4 rounded-full bg-white/20 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-white ring-1 ring-white/30 backdrop-blur-md">
                                #{{ str_pad((string) $ordinal, 2, '0', STR_PAD_LEFT) }}
                            </span>
                        </div>
                        <div class="flex flex-1 flex-col p-5 pt-4">
                            <p class="line-clamp-3 text-sm leading-relaxed text-slate-600">{{ $specialty->description ?: 'Details for this specialty will appear here.' }}</p>
                            <div class="mt-4 flex items-center gap-2 text-xs font-semibold text-cyan-700">
                                <span class="h-px flex-1 bg-gradient-to-r from-cyan-200 to-transparent"></span>
                                <span>Care pathway</span>
                            </div>
                        </div>
                    @else
                        <div class="relative flex aspect-[4/3] flex-col items-center justify-center bg-gradient-to-br from-cyan-100/90 via-white to-violet-100/70 p-6">
                            <div class="pointer-events-none absolute inset-0 opacity-30" style="background-image: radial-gradient(circle at 2px 2px, rgba(6,182,212,0.35) 1px, transparent 0); background-size: 20px 20px;"></div>
                            <span class="relative mb-3 flex h-16 w-16 items-center justify-center rounded-2xl bg-white/90 text-2xl font-bold text-cyan-800 shadow-lg ring-2 ring-cyan-100">
                                {{ strtoupper(substr($specialty->name, 0, 1)) }}
                            </span>
                            <h3 class="relative text-center font-display text-lg font-semibold text-slate-900">{{ $specialty->name }}</h3>
                        </div>
                        <div class="flex flex-1 flex-col p-5">
                            <p class="line-clamp-4 text-sm leading-relaxed text-slate-600">{{ $specialty->description ?: 'Specialty details coming soon.' }}</p>
                            <div class="mt-4 flex items-center gap-2 text-xs font-semibold text-cyan-700">
                                <span class="h-px flex-1 bg-gradient-to-r from-cyan-200 to-transparent"></span>
                                <span>Overview</span>
                            </div>
                        </div>
                    @endif
                </article>
            @empty
                <div class="col-span-full">
                    <div class="rounded-2xl border border-dashed border-cyan-200/70 bg-gradient-to-br from-white/80 to-violet-50/40 px-8 py-16 text-center backdrop-blur-sm">
                        <p class="font-display text-lg font-semibold text-slate-800">No specialties found</p>
                        <p class="mx-auto mt-2 max-w-md text-sm text-slate-500">Adjust your search keyword and try again.</p>
                    </div>
                </div>
            @endforelse
        </div>

        @if($specialties->hasPages())
            <div class="mt-12 border-t border-slate-200/60 pt-10">
                <div class="ui-pagination-wrap">
                    {{ $specialties->links() }}
                </div>
            </div>
        @endif
    </div>
@endsection
