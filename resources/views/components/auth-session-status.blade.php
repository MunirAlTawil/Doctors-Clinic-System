@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'rounded-lg border border-emerald-200/90 bg-emerald-50/90 px-3 py-2 text-sm font-medium text-emerald-800']) }} role="status">
        {{ $status }}
    </div>
@endif
