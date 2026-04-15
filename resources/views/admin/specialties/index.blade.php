<x-app-layout>
    <x-slot name="header">
        <h2 class="ui-page-heading">Specialties</h2>
    </x-slot>

    <div class="ui-page">
        <form method="POST" action="{{ route('admin.specialties.store') }}" enctype="multipart/form-data" class="ui-card p-5 space-y-4">
            @csrf
            <h3 class="ui-card-title">Add specialty</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium mb-1">Specialty name</label>
                    <input name="name" placeholder="e.g. Internal medicine" class="ui-input" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Description</label>
                    <input name="description" placeholder="Short description" class="ui-input">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Image</label>
                    <input type="file" name="image" class="ui-input" accept="image/*">
                </div>
                <div class="flex items-end">
                    <button class="ui-btn-primary w-full md:w-auto">Add specialty</button>
                </div>
            </div>
        </form>

        <div class="ui-table-wrap">
            <table class="ui-table">
                <thead>
                    <tr>
                        <th>Specialty</th>
                        <th>Description</th>
                        <th>Image</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($specialties as $specialty)
                        <tr>
                            <td class="font-semibold text-blue-900">{{ $specialty->name }}</td>
                            <td>{{ $specialty->description ?: 'No description' }}</td>
                            <td>
                                @if($specialty->image_path)
                                    <img src="{{ asset('storage/'.$specialty->image_path) }}" alt="{{ $specialty->name }}" class="w-16 h-16 rounded-xl object-cover border border-blue-100">
                                @else
                                    <span class="text-xs text-slate-400">No image</span>
                                @endif
                            </td>
                            <td>
                                <span class="rounded-full px-2 py-1 text-xs font-semibold {{ $specialty->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                    {{ $specialty->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="flex flex-col gap-2">
                                    <form method="POST" action="{{ route('admin.specialties.update', $specialty) }}" enctype="multipart/form-data" class="space-y-2 bg-slate-50 border border-slate-200 rounded-xl p-3">
                                        @csrf
                                        @method('PATCH')
                                        <input name="name" value="{{ $specialty->name }}" class="ui-input !py-1.5 !text-xs">
                                        <input name="description" value="{{ $specialty->description }}" class="ui-input !py-1.5 !text-xs">
                                        <input type="file" name="image" class="ui-input !py-1.5 !text-xs" accept="image/*">
                                        <label class="inline-flex items-center gap-1 text-xs text-slate-700">
                                            <input type="checkbox" name="is_active" value="1" @checked($specialty->is_active)>
                                            Active
                                        </label>
                                        <button class="rounded-lg px-3 py-1 bg-blue-100 text-blue-700 hover:bg-blue-200 transition text-xs">Save</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.specialties.destroy', $specialty) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="rounded-lg px-3 py-1 bg-rose-100 text-rose-700 hover:bg-rose-200 transition text-xs">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
