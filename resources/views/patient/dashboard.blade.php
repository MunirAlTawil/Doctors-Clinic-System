<x-app-layout>
    <x-slot name="header">
        <h2 class="ui-page-heading">Patient dashboard</h2>
    </x-slot>

    <div class="ui-page">
        <div class="space-y-6">
            <div>
                <h3 class="ui-section-heading">Upcoming appointments</h3>
                <div class="ui-table-wrap">
                    <table class="ui-table">
                        <thead>
                            <tr>
                                <th>Doctor</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Hours</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($upcomingAppointments as $appointment)
                                <tr>
                                    <td>{{ $appointment->doctor?->name }}</td>
                                    <td>{{ $appointment->appointment_date }}</td>
                                    <td>{{ $appointment->start_time }} - {{ $appointment->end_time }}</td>
                                    <td>{{ $appointment->hours_count }}</td>
                                    <td class="font-semibold text-emerald-700">{{ number_format((float)$appointment->total_amount, 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5">No upcoming appointments.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div>
                <h3 class="ui-section-heading">Past appointments</h3>
                <div class="ui-table-wrap">
                    <table class="ui-table">
                        <thead>
                            <tr>
                                <th>Doctor</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Hours</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pastAppointments as $appointment)
                                <tr>
                                    <td>{{ $appointment->doctor?->name }}</td>
                                    <td>{{ $appointment->appointment_date }}</td>
                                    <td>{{ $appointment->start_time }} - {{ $appointment->end_time }}</td>
                                    <td>{{ $appointment->hours_count }}</td>
                                    <td class="font-semibold text-emerald-700">{{ number_format((float)$appointment->total_amount, 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5">No past appointments.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
