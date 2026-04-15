<x-app-layout>
    <x-slot name="header">
        <h2 class="ui-page-heading">Appointments</h2>
    </x-slot>

    <div class="ui-page max-w-7xl">
        <div class="ui-card p-4">
            <form method="GET" class="flex flex-wrap gap-3 items-end">
                <div class="min-w-[210px] flex-1">
                    <label class="block text-sm font-medium mb-1">Date</label>
                    <input type="date" name="date" value="{{ $filters['date'] ?? '' }}" class="ui-input">
                </div>

                <div class="min-w-[200px]">
                    <label class="block text-sm font-medium mb-1">Status</label>
                    <select name="status" class="ui-input">
                        <option value="">All</option>
                        <option value="booked" @selected(($filters['status'] ?? '') === 'booked')>Booked</option>
                        <option value="completed" @selected(($filters['status'] ?? '') === 'completed')>Completed</option>
                        <option value="canceled" @selected(($filters['status'] ?? '') === 'canceled')>Canceled</option>
                    </select>
                </div>

                <div class="min-w-[220px]">
                    <label class="block text-sm font-medium mb-1">Doctor</label>
                    <select name="doctor_id" class="ui-input">
                        <option value="">All</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" @selected(($filters['doctor_id'] ?? '') == $doctor->id)>{{ $doctor->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="min-w-[240px] flex-1">
                    <label class="block text-sm font-medium mb-1">Patient search</label>
                    <input type="text" name="patient_search" value="{{ $filters['patient_search'] ?? '' }}" placeholder="Name or email" class="ui-input">
                </div>

                <button class="ui-btn-secondary">Filter</button>
            </form>
        </div>

        <div class="ui-table-wrap">
            <table class="ui-table">
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Hours</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $appointment)
                        <tr>
                            <td>
                                <div class="font-semibold">{{ $appointment->patient?->name }}</div>
                                <div class="text-xs text-slate-500">{{ $appointment->patient?->email }}</div>
                            </td>
                            <td class="font-semibold">{{ $appointment->doctor?->name }}</td>
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
                        </tr>
                    @empty
                        <tr><td colspan="7">No matching appointments.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ $appointments->links() }}</div>
    </div>
</x-app-layout>
