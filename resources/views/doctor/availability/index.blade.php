<x-app-layout>
    <x-slot name="header">
        <h2 class="ui-page-heading">Availability schedule</h2>
    </x-slot>

    <div class="ui-page max-w-6xl">
        <form method="POST" action="{{ route('doctor.availability.store') }}" class="ui-card p-5 space-y-4">
            @csrf
            <h3 class="ui-card-title">Weekly hours</h3>
            <p class="text-sm text-slate-600">Set start and end of your working day <span class="font-medium text-slate-700">on the hour only</span> (no minutes), and pick two weekly off days. Future slots will be generated automatically.</p>

            @php
                $hourOnlyFromDb = function (?string $value, string $fallback): string {
                    if ($value === null || $value === '') {
                        return $fallback;
                    }
                    $h = (int) substr((string) $value, 0, 2);
                    return sprintf('%02d:00', max(0, min(23, $h)));
                };
                $startDefault = old('start_time', $hourOnlyFromDb($doctorProfile?->work_start_time ?? null, '09:00'));
                $endDefault = old('end_time', $hourOnlyFromDb($doctorProfile?->work_end_time ?? null, '17:00'));
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium mb-1" for="start_time">From (hour)</label>
                    <select id="start_time" name="start_time" class="ui-input" required>
                        @for ($h = 0; $h < 24; $h++)
                            @php $opt = sprintf('%02d:00', $h); @endphp
                            <option value="{{ $opt }}" @selected($startDefault === $opt)>{{ $opt }}</option>
                        @endfor
                    </select>
                    <p class="mt-1 text-xs text-slate-500">Whole hours only (e.g. 09:00).</p>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" for="end_time">To (hour)</label>
                    <select id="end_time" name="end_time" class="ui-input" required>
                        @for ($h = 0; $h < 24; $h++)
                            @php $opt = sprintf('%02d:00', $h); @endphp
                            <option value="{{ $opt }}" @selected($endDefault === $opt)>{{ $opt }}</option>
                        @endfor
                    </select>
                    <p class="mt-1 text-xs text-slate-500">Whole hours only (e.g. 17:00).</p>
                </div>
            </div>

            @php
                $selectedOffDays = old('off_days', array_filter([$doctorProfile?->off_day_1, $doctorProfile?->off_day_2]));
            @endphp
            <div>
                <label class="block text-sm font-medium mb-1">Weekly off days (pick two)</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                    @foreach($weekdays as $key => $label)
                        <label class="inline-flex items-center gap-2 rounded-lg border border-blue-100 bg-blue-50 px-3 py-2 text-sm">
                            <input type="checkbox" name="off_days[]" value="{{ $key }}" @checked(in_array($key, $selectedOffDays, true))>
                            <span>{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex flex-wrap gap-2">
                <button type="submit" class="ui-btn-primary">Save schedule</button>
            </div>
        </form>

        <form method="POST" action="{{ route('doctor.availability.destroy', 0) }}" onsubmit="return confirm('Clear all future generated slots?');" class="mt-3">
            @csrf
            @method('DELETE')
            <button type="submit" class="ui-btn-secondary border-rose-200 text-rose-700 hover:bg-rose-50">Clear future slots</button>
        </form>

        <div class="ui-table-wrap">
            <h3 class="p-4 font-semibold text-blue-900">Generated slots (next 30 days)</h3>
            <table class="ui-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>From</th>
                        <th>To</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($availabilities as $slot)
                        <tr>
                            <td>{{ $slot->work_date }}</td>
                            <td>{{ substr((string)$slot->start_time, 0, 5) }}</td>
                            <td>{{ substr((string)$slot->end_time, 0, 5) }}</td>
                            <td><span class="text-emerald-700 font-semibold">Available</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-slate-500">No slots yet. Save your weekly schedule first.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
