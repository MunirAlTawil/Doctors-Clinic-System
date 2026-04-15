<x-app-layout>
    <x-slot name="header">
        <h2 class="ui-page-heading">Doctor reports</h2>
    </x-slot>

    <div class="ui-page max-w-6xl">
        <div class="ui-table-wrap">
            <h3 class="p-4 font-semibold text-blue-900">Monthly performance</h3>
            <table class="ui-table">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Bookings</th>
                        <th>Total revenue</th>
                        <th>Net earnings</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($monthly as $row)
                        <tr>
                            <td>{{ $row->month }}</td>
                            <td>{{ $row->bookings_count }}</td>
                            <td>{{ number_format((float)$row->total_revenue, 2) }}</td>
                            <td class="text-emerald-700 font-semibold">{{ number_format((float)$row->net_earnings, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4">No report data yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
