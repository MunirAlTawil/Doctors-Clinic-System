@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2.5 border-l-4 border-blue-600 text-start text-base font-semibold text-blue-900 bg-blue-50/90 focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-blue-400/40 transition duration-200 ease-out'
            : 'block w-full ps-3 pe-4 py-2.5 border-l-4 border-transparent text-start text-base font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-50 hover:border-slate-200 focus:outline-none focus-visible:bg-slate-50 transition duration-200 ease-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
