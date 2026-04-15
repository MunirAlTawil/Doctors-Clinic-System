<x-app-layout>
    <x-slot name="header">
        <h2 class="ui-page-heading">Doctor join requests</h2>
    </x-slot>

    <div class="ui-page">
        <div class="ui-table-wrap">
            <table class="ui-table">
                <thead>
                    <tr>
                        <th>Doctor</th>
                        <th>Status</th>
                        <th>Qualification</th>
                        <th>Specialties</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($doctors as $doctor)
                        <tr>
                            <td>{{ $doctor->name }}<br><span class="text-xs text-gray-500">{{ $doctor->email }}</span></td>
                            <td><span class="rounded-full px-2 py-1 text-xs bg-blue-100 text-blue-700">Pending review</span></td>
                            <td>{{ $doctor->doctorProfile?->education_stage ?: '-' }}</td>
                            <td>{{ $doctor->doctorSpecialties->pluck('name')->join(', ') ?: '-' }}</td>
                            <td class="flex gap-2">
                                <form method="POST" action="{{ route('admin.doctor-requests.approve', $doctor) }}">
                                    @csrf
                                    <button class="rounded-lg px-3 py-1 bg-emerald-100 text-emerald-700 hover:bg-emerald-200 transition">Approve</button>
                                </form>
                                <form method="POST" action="{{ route('admin.doctor-requests.reject', $doctor) }}">
                                    @csrf
                                    <input name="rejection_reason" placeholder="Rejection reason (optional)" class="ui-input !py-1 !px-2 !w-44">
                                    <button class="rounded-lg px-3 py-1 bg-rose-100 text-rose-700 hover:bg-rose-200 transition">Reject</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-slate-500">No pending doctor requests.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
