@extends('layouts.public')

@section('title', 'Contact — '.config('app.name'))

@section('content')
    <div class="ui-page max-w-6xl pb-20">
        <section class="ui-public-hero mb-10 lg:mb-12">
            <div class="ui-public-hero-inner">
                <p class="ui-public-hero-kicker">Reach us</p>
                <h1 class="ui-marketing-heading mt-4 max-w-4xl text-3xl leading-tight sm:text-4xl lg:text-[2.75rem] lg:leading-[1.1]">
                    <span class="bg-gradient-to-r from-slate-900 via-teal-900 to-cyan-800 bg-clip-text text-transparent">{{ $content?->title ?? 'Contact us' }}</span>
                </h1>
                <p class="mt-4 max-w-2xl text-base leading-relaxed text-slate-600 sm:text-lg">
                    {{ $content?->subtitle ?? 'We are here to help you. Reach out through any channel below or read the details in the message.' }}
                </p>
            </div>
        </section>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-12 lg:gap-10">
            <aside class="space-y-4 lg:col-span-4">
                <div class="ui-contact-pill">
                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-cyan-500 to-teal-600 text-white shadow-md">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    </span>
                    <div>
                        <p class="font-display text-sm font-semibold text-slate-900">Email</p>
                        <p class="mt-1 text-sm text-slate-600">Use the details in the main message for the fastest reply.</p>
                    </div>
                </div>
                <div class="ui-contact-pill">
                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-violet-500 to-cyan-600 text-white shadow-md">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    </span>
                    <div>
                        <p class="font-display text-sm font-semibold text-slate-900">Visit</p>
                        <p class="mt-1 text-sm text-slate-600">Clinic address and hours appear in your published content when provided.</p>
                    </div>
                </div>
                <div class="ui-contact-pill">
                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-teal-500 to-cyan-600 text-white shadow-md">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12h-1M4 12H3m15.364 6.364l-1.414-1.414M6.343 6.343L4.929 4.929m12.728 12.728l-1.414 1.414M12 21v-1M12 4v1" /></svg>
                    </span>
                    <div>
                        <p class="font-display text-sm font-semibold text-slate-900">Support</p>
                        <p class="mt-1 text-sm text-slate-600">For urgent medical matters, always follow local emergency guidance.</p>
                    </div>
                </div>
            </aside>

            <div class="lg:col-span-8">
                <article class="ui-article-frame overflow-hidden">
                    @if($content?->image_path)
                        <div class="relative">
                            <img
                                src="{{ asset('storage/'.$content->image_path) }}"
                                class="max-h-[22rem] w-full object-cover"
                                alt="{{ $content?->title ?? 'Contact' }}"
                            >
                            <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-slate-950/50 to-transparent"></div>
                        </div>
                    @endif
                    <div class="border-t border-white/50 bg-gradient-to-b from-white/95 to-cyan-50/20 p-6 sm:p-10 lg:p-12">
                        <div class="ui-article-body max-w-none whitespace-pre-wrap">{{ $content?->body ?? 'No contact details yet.' }}</div>
                    </div>
                </article>
            </div>
        </div>
    </div>
@endsection
