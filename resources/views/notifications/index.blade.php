<x-app-layout>
    <x-slot name="header">
        <h2 class="ui-page-heading">Notifications</h2>
    </x-slot>

    <div class="ui-page max-w-5xl space-y-6 pb-12">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-slate-600">Updates for your account and clinic activity.</p>
            <form method="POST" action="{{ route('notifications.read-all') }}">
                @csrf
                <button type="submit" class="ui-btn-secondary min-h-0 py-2.5 text-sm">Mark all as read</button>
            </form>
        </div>

        <div class="space-y-3">
            @forelse($notifications as $notification)
                @php
                    $type = $notification->data['type'] ?? 'info';
                    $itemClass = match ($type) {
                        'success' => 'ui-notify-item-success',
                        'warning' => 'ui-notify-item-warn',
                        default => 'ui-notify-item-info',
                    };
                @endphp
                <div class="ui-notify-item {{ $itemClass }} {{ $notification->read_at ? 'opacity-75' : 'ring-1 ring-cyan-200/40' }}">
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0 flex-1">
                            <h3 class="font-display font-semibold text-slate-900">{{ $notification->data['title'] ?? 'Notification' }}</h3>
                            <p class="mt-1.5 text-sm leading-relaxed text-slate-700">{{ $notification->data['message'] ?? '' }}</p>
                            <p class="mt-3 text-xs font-medium text-slate-500">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                        @if(!$notification->read_at)
                            <form method="POST" action="{{ route('notifications.read', $notification->id) }}" class="shrink-0">
                                @csrf
                                <button type="submit" class="rounded-lg border border-cyan-200 bg-white px-3 py-1.5 text-xs font-semibold text-cyan-800 shadow-sm transition hover:bg-cyan-50">
                                    Mark read
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-dashed border-slate-200/90 bg-white/70 px-8 py-14 text-center backdrop-blur-sm">
                    <div class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 00-12 0v3.2a2 2 0 01-.6 1.4L4 17h5" /></svg>
                    </div>
                    <p class="font-display font-semibold text-slate-800">No notifications yet</p>
                    <p class="mt-1 text-sm text-slate-500">When something changes, it will show up here.</p>
                </div>
            @endforelse
        </div>

        @if($notifications->hasPages())
            <div class="mt-10">
                <div class="ui-pagination-wrap">
                    {{ $notifications->links() }}
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
