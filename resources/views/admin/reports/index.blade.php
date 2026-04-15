<x-app-layout>
    <x-slot name="header">
        <h2 class="ui-page-heading">Admin reports</h2>
    </x-slot>

    <div class="ui-page">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="ui-card ui-card-hover p-5">
                <p class="text-sm text-blue-700">Patients</p>
                <p class="text-3xl font-bold text-blue-950 mt-2">{{ $patientsCount }}</p>
            </div>
            <div class="ui-card ui-card-hover p-5">
                <p class="text-sm text-blue-700">Appointments</p>
                <p class="text-3xl font-bold text-blue-950 mt-2">{{ $appointmentsCount }}</p>
            </div>
            <div class="ui-card ui-card-hover p-5">
                <p class="text-sm text-blue-700">Current profit share</p>
                <p class="text-3xl font-bold text-blue-950 mt-2">{{ number_format((float)($appointmentsCount > 0 ? 15 : 15), 2) }}%</p>
            </div>
        </div>

        <div class="ui-table-wrap">
            <h3 class="p-4 font-semibold text-blue-900">Monthly revenue summary</h3>
            <table class="ui-table">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Bookings</th>
                        <th>Revenue</th>
                        <th>Admin profit</th>
                        <th>Doctors net</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($monthly as $row)
                        <tr>
                            <td>{{ $row->month }}</td>
                            <td>{{ $row->bookings_count }}</td>
                            <td>{{ number_format((float)$row->total_revenue, 2) }}</td>
                            <td>{{ number_format((float)$row->admin_profit, 2) }}</td>
                            <td>{{ number_format((float)$row->doctors_net, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5">No report data yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="ui-table-wrap">
            <h3 class="p-4 font-semibold text-blue-900">Top doctors</h3>
            <table class="ui-table">
                <thead>
                    <tr>
                        <th>Doctor</th>
                        <th>Bookings</th>
                        <th>Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topDoctors as $doctor)
                        <tr>
                            <td>{{ $doctor->name }}</td>
                            <td>{{ $doctor->bookings_count }}</td>
                            <td>{{ number_format((float)$doctor->revenue, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3">No data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
