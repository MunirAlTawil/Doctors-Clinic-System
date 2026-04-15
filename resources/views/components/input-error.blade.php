@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'mt-1.5 space-y-0.5 text-sm text-rose-600']) }} role="alert">
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif
