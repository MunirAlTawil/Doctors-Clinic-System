<x-app-layout>
    <x-slot name="header">
        <h2 class="ui-page-heading">Edit user</h2>
    </x-slot>

    <div class="ui-page max-w-5xl space-y-4">
        <form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data" class="ui-card p-6 space-y-4">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium mb-1">Name</label>
                    <input name="name" class="ui-input" value="{{ old('name', $user->name) }}" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Email</label>
                    <input type="email" name="email" class="ui-input" value="{{ old('email', $user->email) }}" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Phone</label>
                    <input name="phone" class="ui-input" value="{{ old('phone', $user->phone) }}">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Gender</label>
                    <select name="gender" class="ui-input">
                        <option value="">Not specified</option>
                        <option value="male" @selected(old('gender', $user->gender) === 'male')>Male</option>
                        <option value="female" @selected(old('gender', $user->gender) === 'female')>Female</option>
                    </select>
                </div>
            </div>

            <label class="inline-flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $user->is_active))>
                <span class="text-sm">Account active</span>
            </label>

            @if($user->isDoctor())
                <div class="border-t border-blue-100 pt-4">
                    <h3 class="ui-card-title mb-3">Doctor details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium mb-1">Status</label>
                            <select name="status" class="ui-input">
                                <option value="pending" @selected(old('status', $user->doctorProfile?->status) === 'pending')>Pending review</option>
                                <option value="approved" @selected(old('status', $user->doctorProfile?->status) === 'approved')>Approved</option>
                                <option value="rejected" @selected(old('status', $user->doctorProfile?->status) === 'rejected')>Rejected</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Hourly rate</label>
                            <input type="number" step="0.01" min="0" name="hourly_rate" class="ui-input" value="{{ old('hourly_rate', $user->doctorProfile?->hourly_rate) }}">
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="block text-sm font-medium mb-1">Education / qualification</label>
                        <input name="education_stage" class="ui-input" value="{{ old('education_stage', $user->doctorProfile?->education_stage) }}">
                    </div>
                    <div class="mt-3">
                        <label class="block text-sm font-medium mb-1">Bio</label>
                        <textarea name="bio" class="ui-input min-h-28">{{ old('bio', $user->doctorProfile?->bio) }}</textarea>
                    </div>
                    <div class="mt-3">
                        <label class="block text-sm font-medium mb-1">Specialties</label>
                        <select name="specialties[]" multiple class="ui-input min-h-28">
                            @foreach($specialties as $specialty)
                                <option value="{{ $specialty->id }}" @selected(collect(old('specialties', $user->doctorSpecialties->pluck('id')->all()))->contains($specialty->id))>{{ $specialty->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mt-3">
                        <label class="block text-sm font-medium mb-1">Profile image</label>
                        <input type="file" name="profile_image" class="ui-input">
                        @if($user->doctorProfile?->profile_image)
                            <img src="{{ asset('storage/'.$user->doctorProfile->profile_image) }}" alt="Doctor image" class="w-28 h-28 rounded-full object-cover border border-blue-100 mt-3">
                        @endif
                    </div>
                </div>
            @endif

            <div class="flex gap-2">
                <button class="ui-btn-primary">Save changes</button>
                <a href="{{ route('admin.users.index') }}" class="ui-btn-secondary">Back</a>
            </div>
        </form>

        <form method="POST" action="{{ route('admin.users.reset-password', $user) }}" class="ui-card p-6 space-y-4">
            @csrf
            <h3 class="ui-card-title">Reset password</h3>
            <p class="text-sm text-slate-600">Set a new password for this user.</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium mb-1">New password</label>
                    <input type="password" name="password" class="ui-input" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Confirm password</label>
                    <input type="password" name="password_confirmation" class="ui-input" required>
                </div>
            </div>
            <div>
                <button class="ui-btn-primary">Update password</button>
            </div>
        </form>
    </div>
</x-app-layout>
