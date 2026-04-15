<x-app-layout>
    <x-slot name="header">
        <h2 class="ui-page-heading">User management</h2>
    </x-slot>

    <div class="ui-page">
        <form method="GET" class="ui-card p-4 grid grid-cols-1 md:grid-cols-4 gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, or phone" class="ui-input">
            <select name="role" class="ui-input">
                <option value="">All roles</option>
                <option value="admin" @selected(request('role') === 'admin')>Admin</option>
                <option value="doctor" @selected(request('role') === 'doctor')>Doctor</option>
                <option value="patient" @selected(request('role') === 'patient')>Patient</option>
            </select>
            <button class="ui-btn-primary">Filter</button>
        </form>

        <div class="ui-table-wrap">
            <table class="ui-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->role === 'admin')
                                    Admin
                                @elseif($user->role === 'doctor')
                                    Doctor
                                @else
                                    Patient
                                @endif
                            </td>
                            <td>
                                <span class="{{ $user->is_active ? 'text-emerald-700' : 'text-rose-700' }}">
                                    {{ $user->is_active ? 'Active' : 'Suspended' }}
                                </span>
                            </td>
                            <td>
                                <div class="flex flex-wrap gap-2 mb-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="px-2 py-1 rounded bg-blue-100 text-blue-700 hover:bg-blue-200 transition">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.users.toggle-active', $user) }}">
                                        @csrf
                                        <button class="px-2 py-1 rounded bg-amber-100 text-amber-700 hover:bg-amber-200 transition">
                                            {{ $user->is_active ? 'Suspend' : 'Unsuspend' }}
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-2 py-1 rounded bg-rose-100 text-rose-700 hover:bg-rose-200 transition">Delete</button>
                                    </form>
                                </div>

                                <details class="rounded-lg border border-blue-100 bg-blue-50 p-2">
                                    <summary class="cursor-pointer text-xs text-blue-700 font-semibold">Send notification</summary>
                                    <form method="POST" action="{{ route('admin.users.notify', $user) }}" class="mt-2 space-y-2">
                                        @csrf
                                        <input name="title" class="ui-input !py-1 !text-xs" placeholder="Notification title" required>
                                        <textarea name="message" class="ui-input !py-1 !text-xs min-h-16" placeholder="Notification message" required></textarea>
                                        <select name="type" class="ui-input !py-1 !text-xs">
                                            <option value="info">Info</option>
                                            <option value="success">Success</option>
                                            <option value="warning">Warning</option>
                                        </select>
                                        <button class="px-2 py-1 rounded bg-indigo-100 text-indigo-700 hover:bg-indigo-200 transition text-xs">Send</button>
                                    </form>
                                </details>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div>{{ $users->links() }}</div>
    </div>
</x-app-layout>
