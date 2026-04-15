@php
    $variant = $variant ?? 'pending';
@endphp

@if($variant === 'pending')
    <div class="px-4 sm:px-6 lg:px-8 pt-4" role="status" aria-live="polite">
        <div class="relative overflow-hidden rounded-2xl border border-amber-200/90 bg-gradient-to-br from-amber-50 via-white to-orange-50/40 shadow-lg shadow-amber-900/10 ring-1 ring-amber-900/5">
            <div class="pointer-events-none absolute -start-12 -top-12 h-40 w-40 rounded-full bg-amber-300/25 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-8 end-10 h-32 w-32 rounded-full bg-orange-200/20 blur-2xl"></div>
            <div class="relative flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:gap-6 sm:p-6">
                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-amber-100 text-amber-800 shadow-inner ring-1 ring-amber-200/80">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1 space-y-2">
                    <div class="flex flex-wrap items-center gap-2">
                        <h3 class="text-lg font-bold text-amber-950 sm:text-xl">Your application is under review</h3>
                        <span class="rounded-full bg-amber-200/80 px-2.5 py-0.5 text-xs font-semibold text-amber-900">Pending approval</span>
                    </div>
                    <p class="max-w-3xl text-sm leading-relaxed text-amber-900/85 sm:text-[0.9375rem]">
                        Your doctor account is not active yet. Please wait for an administrator to review and approve your request. You can complete your profile in the meantime; bookings and full reporting will be available after approval.
                    </p>
                    <p class="text-xs font-medium text-amber-800/70">
                        You will receive a notification when your status changes.
                    </p>
                </div>
            </div>
        </div>
    </div>
@elseif($variant === 'rejected')
    <div class="px-4 sm:px-6 lg:px-8 pt-4" role="alert">
        <div class="relative overflow-hidden rounded-2xl border border-rose-200/90 bg-gradient-to-br from-rose-50 via-white to-rose-50/30 shadow-lg shadow-rose-900/10 ring-1 ring-rose-900/5">
            <div class="pointer-events-none absolute -start-10 -top-10 h-36 w-36 rounded-full bg-rose-300/20 blur-3xl"></div>
            <div class="relative flex flex-col gap-4 p-5 sm:flex-row sm:items-start sm:gap-6 sm:p-6">
                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-rose-100 text-rose-700 shadow-inner ring-1 ring-rose-200/80">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1 space-y-3">
                    <div class="flex flex-wrap items-center gap-2">
                        <h3 class="text-lg font-bold text-rose-950 sm:text-xl">Your application was not approved</h3>
                        <span class="rounded-full bg-rose-200/80 px-2.5 py-0.5 text-xs font-semibold text-rose-900">Rejected</span>
                    </div>
                    <p class="max-w-3xl text-sm leading-relaxed text-rose-900/85 sm:text-[0.9375rem]">
                        Your doctor registration request was declined at this time. Check your email or notifications; contact the clinic if you need more information.
                    </p>
                    @if(filled($rejectionReason ?? null))
                        <div class="rounded-xl border border-rose-200/80 bg-white/80 px-4 py-3 text-sm text-rose-900/90 shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-wide text-rose-700/80">Administrator note</p>
                            <p class="mt-1.5 leading-relaxed text-rose-950">{{ $rejectionReason }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif
