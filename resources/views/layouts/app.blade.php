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
    <body class="ui-app-body font-sans antialiased text-slate-800">
        {{-- Fixed sidebar (desktop); main uses lg:ms-72 so content never sits under it. Logical ms supports RTL. --}}
        <aside
            class="ui-sidebar fixed inset-y-0 start-0 z-40 hidden h-[100dvh] w-72 flex-col text-cyan-50 lg:flex"
            aria-label="{{ __('Main navigation') }}"
        >
            <div class="ui-sidebar-accent" aria-hidden="true"></div>
            <div class="ui-sidebar-inner p-5">
                <div class="mb-8 shrink-0">
                    <a href="{{ url('/') }}" class="group flex items-center gap-3 font-display text-xl font-bold tracking-tight text-white transition hover:text-cyan-200">
                        <span class="ui-logo-mark ui-logo-mark--image shrink-0">@include('partials.brand-mark-icon')</span>
                        <span>{{ config('app.name') }}</span>
                    </a>
                    <p class="mt-3 text-sm font-medium text-cyan-100/95">{{ auth()->user()->name }}</p>
                    <p class="mt-2 inline-flex rounded-full bg-white/10 px-2.5 py-0.5 text-[11px] font-semibold uppercase tracking-wider text-cyan-200/90 ring-1 ring-white/10">{{ auth()->user()->role }}</p>
                </div>

                <nav class="min-h-0 flex-1 space-y-1 overflow-y-auto overscroll-y-contain pr-0.5 text-[0.9375rem] [scrollbar-width:thin]">
                    @php
                        $baseNav = 'ui-nav-item pl-4';
                        $activeNav = 'ui-nav-item-active';
                        $inactiveNav = 'ui-nav-item-inactive';
                    @endphp

                    <a
                        href="{{ route('dashboard') }}"
                        class="{{ $baseNav }} {{ request()->routeIs('dashboard') ? $activeNav : $inactiveNav }}"
                    >
                        Dashboard
                    </a>

                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.specialties.index') }}" class="{{ $baseNav }} {{ request()->routeIs('admin.specialties.*') ? $activeNav : $inactiveNav }}">Specialties</a>
                        <a href="{{ route('admin.doctor-requests.index') }}" class="{{ $baseNav }} {{ request()->routeIs('admin.doctor-requests.*') ? $activeNav : $inactiveNav }}">Doctor requests</a>
                        <a href="{{ route('admin.users.index') }}" class="{{ $baseNav }} {{ request()->routeIs('admin.users.*') ? $activeNav : $inactiveNav }}">Users</a>
                        <a href="{{ route('admin.reports.index') }}" class="{{ $baseNav }} {{ request()->routeIs('admin.reports.*') ? $activeNav : $inactiveNav }}">Reports</a>
                        <a href="{{ route('admin.appointments.index') }}" class="{{ $baseNav }} {{ request()->routeIs('admin.appointments.*') ? $activeNav : $inactiveNav }}">Appointments</a>
                        <a href="{{ route('admin.pages.index') }}" class="{{ $baseNav }} {{ request()->routeIs('admin.pages.*') ? $activeNav : $inactiveNav }}">Page content</a>
                        <a href="{{ route('admin.settings.index') }}" class="{{ $baseNav }} {{ request()->routeIs('admin.settings.*') ? $activeNav : $inactiveNav }}">Settings</a>
                    @endif

                    @if(auth()->user()->isDoctor())
                        <a href="{{ route('doctor.profile.edit') }}" class="{{ $baseNav }} {{ request()->routeIs('doctor.profile.*') ? $activeNav : $inactiveNav }}">Doctor profile</a>
                        <a href="{{ route('doctor.availability.index') }}" class="{{ $baseNav }} {{ request()->routeIs('doctor.availability.*') ? $activeNav : $inactiveNav }}">Availability</a>
                        <a href="{{ route('doctor.reports.index') }}" class="{{ $baseNav }} {{ request()->routeIs('doctor.reports.*') ? $activeNav : $inactiveNav }}">Reports</a>
                        <a href="{{ route('doctor.appointments.index') }}" class="{{ $baseNav }} {{ request()->routeIs('doctor.appointments.*') ? $activeNav : $inactiveNav }}">Schedule</a>
                    @endif

                    @if(auth()->user()->isPatient())
                        <a href="{{ route('patient.dashboard') }}" class="{{ $baseNav }} {{ request()->routeIs('patient.dashboard') ? $activeNav : $inactiveNav }}">My appointments</a>
                        <a href="{{ route('bookings.create') }}" class="{{ $baseNav }} {{ request()->routeIs('bookings.*') ? $activeNav : $inactiveNav }}">Book appointment</a>
                    @endif

                </nav>

                <div class="mt-4 shrink-0 border-t border-white/10 pt-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="ui-sidebar-logout">Log out</button>
                    </form>
                </div>
            </div>
        </aside>

            <div class="ui-main-shell min-h-screen min-w-0 overflow-x-hidden lg:ms-72">
                <div class="ui-main-mesh" aria-hidden="true">
                    <div class="ui-diagonal-faint"></div>
                    <div class="ui-mesh-blob-a"></div>
                    <div class="ui-mesh-blob-b"></div>
                    <div class="ui-mesh-blob-c"></div>
                </div>
                <header class="sticky top-0 z-30 border-b border-white/50 bg-white/60 shadow-[0_8px_32px_rgba(2,12,27,0.06)] backdrop-blur-2xl">
                    <div class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-cyan-300/50 to-transparent"></div>
                    <div class="relative flex flex-wrap items-center justify-between gap-3 px-4 py-3.5 sm:px-6 lg:px-8">
                        <div class="min-w-0">
                            @isset($header)
                                {{ $header }}
                            @else
                                <h2 class="font-display text-lg font-semibold tracking-tight text-slate-900">Dashboard</h2>
                            @endisset
                        </div>
                        <div class="flex flex-wrap items-center justify-end gap-2 sm:gap-3">
                            <a
                                href="{{ url('/') }}"
                                class="ui-btn-secondary min-h-0 rounded-xl px-3.5 py-2 text-sm"
                            >
                                Home
                            </a>
                            <div class="hidden max-w-[14rem] truncate text-sm text-slate-500 sm:block" title="{{ auth()->user()->email }}">{{ auth()->user()->email }}</div>
                            @php
                                $unreadCount = auth()->user()->unreadNotifications()->count();
                                $latestNotifications = auth()->user()->notifications()->latest()->limit(3)->get();
                            @endphp
                            <details class="relative z-[100]">
                                <summary class="list-none cursor-pointer relative flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-slate-200/90 bg-white/90 text-cyan-700 shadow-sm transition hover:border-cyan-200 hover:bg-cyan-50/90 hover:shadow-md focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-cyan-500 [&::-webkit-details-marker]:hidden">
                                    <span class="sr-only">Notifications</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M15 17h5l-1.4-1.4A2 2 0 0 1 18 14.2V11a6 6 0 1 0-12 0v3.2a2 2 0 0 1-.6 1.4L4 17h5" />
                                        <path d="M9 17a3 3 0 0 0 6 0" />
                                    </svg>
                                    @if($unreadCount > 0)
                                        <span class="absolute -top-1 end-0 inline-flex min-h-[1.125rem] min-w-[1.125rem] items-center justify-center rounded-full bg-rose-500 px-1 text-[10px] font-bold leading-none text-white shadow ring-2 ring-white">
                                            {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                                        </span>
                                    @endif
                                </summary>
                                <div class="notification-dropdown-panel absolute end-0 top-full z-[100] mt-2 flex w-[min(100vw-1.5rem,22rem)] max-h-[min(32rem,calc(100dvh-5.5rem))] flex-col overflow-hidden rounded-2xl border border-white/60 bg-white/95 shadow-2xl shadow-slate-900/15 ring-1 ring-cyan-500/10 backdrop-blur-xl transition duration-300">
                                    <div class="shrink-0 border-b border-slate-100/80 bg-gradient-to-r from-cyan-50/95 via-white/95 to-violet-50/40 px-4 py-3">
                                        <div class="flex items-center justify-between gap-2">
                                            <p class="text-sm font-semibold text-slate-800">Notifications</p>
                                            @if($unreadCount > 0)
                                                <span class="rounded-full bg-rose-100 px-2 py-0.5 text-[11px] font-semibold text-rose-700">{{ $unreadCount }} unread</span>
                                            @else
                                                <span class="text-[11px] text-slate-400">Nothing new</span>
                                            @endif
                                        </div>
                                        <p class="mt-0.5 text-[11px] text-slate-500">Latest {{ $latestNotifications->count() }} notifications</p>
                                    </div>
                                    <div class="min-h-0 flex-1 divide-y divide-slate-100 overflow-y-auto overscroll-contain">
                                        @forelse($latestNotifications as $notification)
                                            @php $isUnread = !$notification->read_at; @endphp
                                            <div class="relative px-4 py-3 transition {{ $isUnread ? 'bg-cyan-50/70 before:absolute before:inset-y-3 before:start-0 before:w-1 before:rounded-full before:bg-gradient-to-b before:from-cyan-500 before:to-teal-500' : 'bg-white hover:bg-slate-50/80' }}">
                                                <div class="flex items-start justify-between gap-2">
                                                    <div class="min-w-0 flex-1 space-y-1">
                                                        <div class="flex items-center gap-1.5">
                                                            @if($isUnread)
                                                                <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-cyan-500" title="Unread"></span>
                                                            @endif
                                                            <p class="text-sm font-semibold text-slate-800 line-clamp-1">{{ $notification->data['title'] ?? 'Notification' }}</p>
                                                        </div>
                                                        <p class="text-xs leading-relaxed text-slate-600 line-clamp-2">{{ $notification->data['message'] ?? '' }}</p>
                                                        <p class="text-[11px] text-slate-400">{{ $notification->created_at->diffForHumans() }}</p>
                                                    </div>
                                                    @if($isUnread)
                                                        <form method="POST" action="{{ route('notifications.read', $notification->id) }}" class="shrink-0">
                                                            @csrf
                                                            <button type="submit" class="rounded-lg border border-cyan-200 bg-white px-2 py-1 text-[10px] font-medium text-cyan-800 shadow-sm transition hover:bg-cyan-50" title="Mark as read">Read</button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        @empty
                                            <div class="flex flex-col items-center justify-center gap-2 px-6 py-12 text-center">
                                                <span class="flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M15 17h5l-1.4-1.4A2 2 0 0 1 18 14.2V11a6 6 0 1 0-12 0v3.2a2 2 0 0 1-.6 1.4L4 17h5" />
                                                        <path d="M9 17a3 3 0 0 0 6 0" />
                                                    </svg>
                                                </span>
                                                <p class="text-sm font-medium text-slate-600">No notifications yet</p>
                                                <p class="max-w-[14rem] text-xs text-slate-500">Updates and alerts for your account will appear here.</p>
                                            </div>
                                        @endforelse
                                    </div>
                                    <div class="shrink-0 border-t border-slate-100 bg-slate-50/90 px-4 py-3">
                                        <div class="flex flex-wrap items-center justify-between gap-2">
                                            <a href="{{ route('notifications.index') }}" class="text-xs font-semibold text-cyan-700 hover:text-cyan-900 hover:underline underline-offset-2">View all notifications</a>
                                            @if($unreadCount > 0)
                                                <form method="POST" action="{{ route('notifications.read-all') }}">
                                                    @csrf
                                                    <button type="submit" class="text-xs font-medium text-slate-600 underline-offset-2 hover:text-slate-800 hover:underline">Mark all read</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </details>
                            <details class="relative lg:hidden">
                                <summary class="cursor-pointer rounded-xl border border-slate-200/90 bg-white/90 px-3.5 py-2 text-sm font-medium text-cyan-900 shadow-sm transition hover:border-cyan-200 hover:bg-cyan-50/80">Menu</summary>
                                <div class="absolute end-0 z-50 mt-2 w-56 space-y-0.5 rounded-2xl border border-white/60 bg-white/95 p-2 shadow-xl shadow-slate-900/10 ring-1 ring-cyan-500/10 backdrop-blur-xl">
                                    <a href="{{ url('/') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-800 hover:bg-cyan-50">Home</a>
                                    <a href="{{ route('dashboard') }}" class="block rounded-lg px-3 py-2 text-sm hover:bg-cyan-50">Dashboard</a>
                                    @if(auth()->user()->isAdmin())
                                        <a href="{{ route('admin.specialties.index') }}" class="block rounded-lg px-3 py-2 text-sm hover:bg-cyan-50">Specialties</a>
                                        <a href="{{ route('admin.doctor-requests.index') }}" class="block rounded-lg px-3 py-2 text-sm hover:bg-cyan-50">Doctor requests</a>
                                        <a href="{{ route('admin.users.index') }}" class="block rounded-lg px-3 py-2 text-sm hover:bg-cyan-50">Users</a>
                                        <a href="{{ route('admin.reports.index') }}" class="block rounded-lg px-3 py-2 text-sm hover:bg-cyan-50">Reports</a>
                                        <a href="{{ route('admin.appointments.index') }}" class="block rounded-lg px-3 py-2 text-sm hover:bg-cyan-50">Appointments</a>
                                    @endif
                                    @if(auth()->user()->isDoctor())
                                        <a href="{{ route('doctor.profile.edit') }}" class="block rounded-lg px-3 py-2 text-sm hover:bg-cyan-50">Doctor profile</a>
                                        <a href="{{ route('doctor.availability.index') }}" class="block rounded-lg px-3 py-2 text-sm hover:bg-cyan-50">Availability</a>
                                        <a href="{{ route('doctor.appointments.index') }}" class="block rounded-lg px-3 py-2 text-sm hover:bg-cyan-50">Schedule</a>
                                        <a href="{{ route('doctor.reports.index') }}" class="block rounded-lg px-3 py-2 text-sm hover:bg-cyan-50">Reports</a>
                                    @endif
                                    @if(auth()->user()->isPatient())
                                        <a href="{{ route('patient.dashboard') }}" class="block rounded-lg px-3 py-2 text-sm hover:bg-cyan-50">My appointments</a>
                                        <a href="{{ route('bookings.create') }}" class="block rounded-lg px-3 py-2 text-sm hover:bg-cyan-50">Book appointment</a>
                                    @endif
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full rounded-lg px-3 py-2 text-left text-sm text-rose-700 hover:bg-rose-50">Log out</button>
                                    </form>
                                </div>
                            </details>
                        </div>
                    </div>
                </header>

                @if(auth()->user()->isDoctor())
                    @php
                        auth()->user()->loadMissing('doctorProfile');
                        $__dp = auth()->user()->doctorProfile;
                    @endphp
                    @if($__dp && $__dp->status === 'pending')
                        @include('partials.doctor-registration-status-banner', ['variant' => 'pending'])
                    @elseif($__dp && $__dp->status === 'rejected')
                        @include('partials.doctor-registration-status-banner', [
                            'variant' => 'rejected',
                            'rejectionReason' => $__dp->rejection_reason,
                        ])
                    @endif
                @endif

                @if (session('success'))
                    <div class="px-4 pt-4 sm:px-6 lg:px-8">
                        <div class="ui-alert-success">
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="px-4 pt-4 sm:px-6 lg:px-8">
                        <div class="ui-alert-error" role="alert">
                            {{ $errors->first() }}
                        </div>
                    </div>
                @endif

                <main class="relative z-0">
                    {{ $slot }}
                </main>
            </div>
    </body>
</html>
