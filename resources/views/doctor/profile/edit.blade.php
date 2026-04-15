<x-app-layout>
    <x-slot name="header">
        <h2 class="ui-page-heading">Doctor profile</h2>
    </x-slot>

    <div class="ui-page max-w-4xl">
        @php
            $currentPhoto = $doctor->doctorProfile?->profile_image;
            $currentPhotoUrl = $currentPhoto ? asset('storage/'.$currentPhoto) : null;
        @endphp
        <form method="POST" action="{{ route('doctor.profile.update') }}" enctype="multipart/form-data" class="ui-card p-6 space-y-5">
            @csrf
            <div class="rounded-2xl border border-blue-100 bg-slate-50/80 p-4">
                <label class="block text-sm font-medium text-slate-700">Profile photo</label>
                <div class="mt-3 flex flex-col items-start gap-4 sm:flex-row sm:items-center">
                    @if($currentPhotoUrl)
                        <img src="{{ $currentPhotoUrl }}" alt="" class="h-24 w-24 rounded-2xl border border-blue-100 object-cover shadow-sm">
                    @else
                        <div class="flex h-24 w-24 items-center justify-center rounded-2xl border border-dashed border-slate-300 bg-white text-xs text-slate-500">No photo</div>
                    @endif
                    <div class="min-w-0 flex-1">
                        <input
                            type="file"
                            name="profile_image"
                            accept="image/jpeg,image/png,image/webp,image/gif"
                            class="block w-full text-sm text-slate-600 file:me-3 file:rounded-xl file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-blue-700 hover:file:bg-blue-100"
                        />
                        <p class="mt-1 text-xs text-slate-500">Optional on update: JPG, PNG, or WebP — up to 2 MB.</p>
                        <x-input-error :messages="$errors->get('profile_image')" class="mt-2" />
                    </div>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Education</label>
                <input name="education_stage" value="{{ old('education_stage', $doctor->doctorProfile?->education_stage) }}" class="ui-input mt-1">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Hourly rate</label>
                <input type="number" step="0.01" min="1" name="hourly_rate" value="{{ old('hourly_rate', $doctor->doctorProfile?->hourly_rate ?? 100) }}" class="ui-input mt-1" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Bio</label>
                <textarea name="bio" class="ui-input mt-1 min-h-32">{{ old('bio', $doctor->doctorProfile?->bio) }}</textarea>
            </div>
            <div>
                <label for="profile-specialty-search" class="block text-sm font-medium text-slate-700">Specialties</label>
                <p class="mt-1 text-xs text-slate-500">Select at least one. You can search by name.</p>
                <div class="mt-2">
                    @include('partials.doctor-specialty-picker', [
                        'specialties' => $specialties,
                        'selectedIds' => old('specialties', $doctor->doctorSpecialties->pluck('id')->all()),
                        'showHeading' => false,
                        'showCounter' => true,
                        'searchId' => 'profile-specialty-search',
                        'gridId' => 'profile-specialty-grid',
                        'countId' => 'profile-specialty-count',
                        'extraInputAttr' => '',
                    ])
                </div>
                <x-input-error :messages="$errors->get('specialties')" class="mt-2" />
            </div>
            <button type="submit" class="ui-btn-primary">Save profile</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var search = document.getElementById('profile-specialty-search');
            var countEl = document.getElementById('profile-specialty-count');
            var grid = document.getElementById('profile-specialty-grid');
            if (!grid) return;

            function boxes() {
                return grid.querySelectorAll('input.specialty-chip-cb[type="checkbox"]');
            }

            function updateCount() {
                if (!countEl) return;
                var n = Array.prototype.filter.call(boxes(), function (c) { return c.checked; }).length;
                if (n === 0) {
                    countEl.textContent = 'No specialty selected';
                    countEl.className = 'text-xs font-medium text-amber-600';
                } else {
                    countEl.textContent = n === 1 ? '1 specialty selected' : (n + ' specialties selected');
                    countEl.className = 'text-xs font-medium text-emerald-700';
                }
            }

            search && search.addEventListener('input', function () {
                var q = this.value.trim().toLowerCase();
                grid.querySelectorAll('[data-specialty-chip]').forEach(function (chip) {
                    var blob = (chip.getAttribute('data-search') || '').toLowerCase();
                    chip.classList.toggle('hidden', q !== '' && blob.indexOf(q) === -1);
                });
            });

            Array.prototype.forEach.call(boxes(), function (c) {
                c.addEventListener('change', updateCount);
            });
            updateCount();
        });
    </script>
</x-app-layout>
