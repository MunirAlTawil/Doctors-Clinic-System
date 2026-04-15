@php
    $selected = collect($selectedIds ?? [])->map(fn ($v) => (string) $v);
    $inputName = $inputName ?? 'specialties';
    $searchId = $searchId ?? 'doctor-specialty-search';
    $gridId = $gridId ?? 'doctor-specialty-grid';
    $countId = $countId ?? 'doctor-specialty-count';
    $extraInputAttr = $extraInputAttr ?? '';
@endphp

<div class="space-y-2">
    @if(!empty($showHeading))
        <div class="flex flex-wrap items-end justify-between gap-2">
            <span class="block text-sm font-medium text-gray-700">{{ $heading ?? 'Medical specialties' }}</span>
            <span id="{{ $countId }}" class="text-xs font-medium text-slate-500">No specialty selected</span>
        </div>
    @elseif(!empty($showCounter))
        <div class="flex justify-end">
            <span id="{{ $countId }}" class="text-xs font-medium text-slate-500">No specialty selected</span>
        </div>
    @endif

    @if(!empty($hint))
        <p class="text-xs text-slate-500">{{ $hint }}</p>
    @endif

    <input
        type="search"
        id="{{ $searchId }}"
        autocomplete="off"
        placeholder="Search specialties by name…"
        class="ui-input block w-full text-sm"
        {!! $extraInputAttr !!}
    />

    <div class="rounded-2xl border border-blue-100 bg-slate-50/80 p-3 shadow-inner">
        <div id="{{ $gridId }}" class="grid max-h-52 grid-cols-1 gap-2 overflow-y-auto overscroll-contain sm:grid-cols-2 sm:max-h-64">
            @forelse($specialties as $specialty)
                @php
                    $sid = (string) $specialty->id;
                    $isChecked = $selected->contains($sid);
                    $searchBlob = mb_strtolower($specialty->name.' '.(string) ($specialty->description ?? ''), 'UTF-8');
                @endphp
                <label
                    data-specialty-chip
                    data-search="{{ e($searchBlob) }}"
                    class="group flex cursor-pointer items-stretch rounded-xl border border-slate-200 bg-white text-slate-800 transition hover:border-blue-300 hover:bg-blue-50/60 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-600 has-[:checked]:text-white has-[:checked]:shadow-md has-[:checked]:ring-1 has-[:checked]:ring-blue-500/30 has-[:checked]:hover:bg-blue-600 has-[:focus-visible]:ring-2 has-[:focus-visible]:ring-blue-400"
                >
                    <input
                        type="checkbox"
                        name="{{ $inputName }}[]"
                        value="{{ $specialty->id }}"
                        class="sr-only specialty-chip-cb"
                        @checked($isChecked)
                        {!! $extraInputAttr !!}
                        @if(filled($specialty->description))
                            title="{{ $specialty->description }}"
                        @endif
                    />
                    <span class="flex min-h-[3rem] flex-1 flex-col justify-center px-3 py-2 focus-within:outline-none">
                        <span class="line-clamp-2 text-sm font-medium leading-snug group-has-[:checked]:text-white">{{ $specialty->name }}</span>
                        @if(filled($specialty->description))
                            <span class="mt-0.5 line-clamp-2 text-[11px] font-normal text-slate-500 group-has-[:checked]:text-blue-100">{{ $specialty->description }}</span>
                        @endif
                    </span>
                </label>
            @empty
                <p class="col-span-full rounded-xl border border-amber-200 bg-amber-50 px-3 py-4 text-center text-sm text-amber-900">
                    {{ $emptyMessage ?? 'No active specialties are available.' }}
                </p>
            @endforelse
        </div>
    </div>
</div>
