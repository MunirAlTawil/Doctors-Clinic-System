<x-app-layout>
    <x-slot name="header">
        <h2 class="ui-page-heading">Appointments</h2>
    </x-slot>

    <div class="ui-page max-w-6xl">
        <div class="ui-card p-4">
            <form method="GET" class="flex flex-wrap gap-3 items-end">
                <div class="flex-1 min-w-[220px]">
                    <label class="block text-sm font-medium mb-1">Filter by date (optional)</label>
                    <input type="date" name="date" value="{{ $date }}" class="ui-input">
                </div>
                <div class="min-w-[220px]">
                    <label class="block text-sm font-medium mb-1">Status</label>
                    <select name="status" class="ui-input">
                        <option value="" @selected($status === '')>Booked only</option>
                        <option value="booked" @selected($status === 'booked')>Booked</option>
                        <option value="completed" @selected($status === 'completed')>Completed</option>
                        <option value="canceled" @selected($status === 'canceled')>Canceled</option>
                    </select>
                </div>
                <button class="ui-btn-secondary">Apply</button>
            </form>
        </div>

        <div class="ui-table-wrap">
            <table class="ui-table">
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Hours</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Update</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $appointment)
                        <tr>
                            <td>
                                <div class="font-semibold">{{ $appointment->patient?->name }}</div>
                                <div class="text-xs text-slate-500">{{ $appointment->patient?->email }}</div>
                            </td>
                            <td>{{ $appointment->appointment_date }}</td>
                            <td>{{ $appointment->start_time }} - {{ $appointment->end_time }}</td>
                            <td>{{ $appointment->hours_count }}</td>
                            <td class="font-semibold text-emerald-700">{{ number_format((float)$appointment->total_amount, 2) }}</td>
                            <td>
                                @if($appointment->status === 'completed')
                                    <span class="rounded-full px-2 py-1 text-xs bg-emerald-100 text-emerald-700 font-semibold">Completed</span>
                                @elseif($appointment->status === 'canceled')
                                    <span class="rounded-full px-2 py-1 text-xs bg-rose-100 text-rose-700 font-semibold">Canceled</span>
                                @else
                                    <span class="rounded-full px-2 py-1 text-xs bg-blue-100 text-blue-700 font-semibold">Booked</span>
                                @endif
                            </td>
                            <td class="space-y-2">
                                @if($appointment->status === 'booked')
                                    <a href="{{ route('doctor.appointments.edit', $appointment) }}" class="ui-btn-secondary inline-flex w-full items-center justify-center">Edit</a>
                                @endif
                                <form method="POST" action="{{ route('doctor.appointments.status', $appointment) }}" class="flex gap-2">
                                    @csrf
                                    <input type="hidden" name="status" value="completed">
                                    <button class="ui-btn-secondary" type="submit">Complete</button>
                                </form>
                                <form method="POST" action="{{ route('doctor.appointments.status', $appointment) }}" class="flex gap-2">
                                    @csrf
                                    <input type="hidden" name="status" value="canceled">
                                    <button class="ui-btn-secondary border-rose-200 text-rose-700" type="submit">Cancel</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7">No matching appointments.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
