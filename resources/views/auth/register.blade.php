<x-guest-layout>
    <x-slot name="title">Create account</x-slot>
    <div class="mb-8">
        <h1 class="ui-auth-heading">Create account</h1>
        <p class="ui-auth-sub">Choose account type and complete your profile — patients book visits; doctors request approval.</p>
    </div>

    @php
        $initialRole = old('role', 'patient');
    @endphp

    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="mt-1 block w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="role" value="Account type" />
            <div class="grid grid-cols-2 gap-3 mt-2">
                <button
                    type="button"
                    id="role-patient-btn"
                    data-role="patient"
                    class="px-4 py-3 rounded-2xl border transition ui-btn-secondary {{ $initialRole === 'patient' ? '!border-cyan-600 !bg-gradient-to-r !from-cyan-600 !to-teal-600 !text-white' : '' }}"
                >
                    <div class="font-semibold text-sm">Patient</div>
                    <div class="text-xs opacity-80">Book appointments</div>
                </button>
                <button
                    type="button"
                    id="role-doctor-btn"
                    data-role="doctor"
                    class="px-4 py-3 rounded-2xl border transition ui-btn-secondary {{ $initialRole === 'doctor' ? '!border-cyan-600 !bg-gradient-to-r !from-cyan-600 !to-teal-600 !text-white' : '' }}"
                >
                    <div class="font-semibold text-sm">Doctor</div>
                    <div class="text-xs opacity-80">Request approval</div>
                </button>
            </div>

            <input type="hidden" id="role" name="role" value="{{ $initialRole }}">

            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="gender" :value="__('Gender')" />
            <select id="gender" name="gender" class="ui-input block mt-1 w-full">
                <option value="">Choose gender</option>
                <option value="male" @selected(old('gender') === 'male')>Male</option>
                <option value="female" @selected(old('gender') === 'female')>Female</option>
            </select>
            <x-input-error :messages="$errors->get('gender')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="phone" :value="__('Phone')" />
            <x-text-input id="phone" class="mt-1 block w-full" type="text" name="phone" :value="old('phone')" autocomplete="tel" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <div
            id="doctor-fields"
            class="transition-all duration-300 overflow-hidden {{ $initialRole === 'doctor' ? 'max-h-[2200px] opacity-100' : 'max-h-0 opacity-0' }}"
        >
            <div class="mt-4">
            <x-input-label for="education_stage" value="Education (doctor)" />
            <x-text-input id="education_stage" data-doctor-field class="mt-1 block w-full" type="text" name="education_stage" :value="old('education_stage')" />
            <x-input-error :messages="$errors->get('education_stage')" class="mt-2" />
            </div>

            <div class="mt-4">
                <label for="profile_image" class="block text-sm font-medium text-slate-700">Profile photo (optional)</label>
                <input
                    id="profile_image"
                    type="file"
                    name="profile_image"
                    accept="image/jpeg,image/png,image/webp,image/gif"
                    data-doctor-field
                    class="mt-1 block w-full text-sm text-slate-600 file:me-3 file:rounded-xl file:border-0 file:bg-cyan-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-cyan-800 hover:file:bg-cyan-100"
                />
                <p class="mt-1 text-xs text-slate-500">Clear face photo — JPG, PNG, or WebP, up to 2 MB.</p>
                <x-input-error :messages="$errors->get('profile_image')" class="mt-2" />
            </div>

            <div class="mt-4">
                @include('partials.doctor-specialty-picker', [
                    'specialties' => $specialties,
                    'selectedIds' => old('specialties', []),
                    'showHeading' => true,
                    'heading' => 'Medical specialties',
                    'hint' => 'Tap cards to select one or more specialties (required).',
                    'searchId' => 'doctor-specialty-search',
                    'gridId' => 'doctor-specialty-grid',
                    'countId' => 'doctor-specialty-count',
                    'emptyMessage' => 'No active specialties. Contact the clinic.',
                    'extraInputAttr' => 'data-doctor-field',
                ])
                <x-input-error :messages="$errors->get('specialties')" class="mt-2" />
                <x-input-error :messages="$errors->get('specialties.0')" class="mt-2" />
            </div>
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="mt-1 block w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="mt-1 block w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="rounded-md text-sm text-slate-600 underline decoration-slate-300 underline-offset-2 transition hover:text-cyan-900 focus:outline-none focus:ring-2 focus:ring-cyan-500/40 focus:ring-offset-2" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const roleInput = document.getElementById('role');
            const doctorFields = document.getElementById('doctor-fields');
            const patientBtn = document.getElementById('role-patient-btn');
            const doctorBtn = document.getElementById('role-doctor-btn');
            const doctorInputs = document.querySelectorAll('[data-doctor-field]');

            function setDoctorFieldsVisible(isDoctor) {
                if (isDoctor) {
                    doctorFields.classList.remove('max-h-0', 'opacity-0');
                    doctorFields.classList.add('max-h-[2200px]', 'opacity-100');
                } else {
                    doctorFields.classList.remove('max-h-[2200px]', 'opacity-100');
                    doctorFields.classList.add('max-h-0', 'opacity-0');
                }

                doctorInputs.forEach((el) => {
                    el.disabled = !isDoctor;
                });
            }

            function setActiveButtons(role) {
                const isPatient = role === 'patient';
                const patientActiveClass = '!border-cyan-600 !bg-gradient-to-r !from-cyan-600 !to-teal-600 !text-white';
                const doctorActiveClass = '!border-cyan-600 !bg-gradient-to-r !from-cyan-600 !to-teal-600 !text-white';

                if (isPatient) {
                    patientBtn.classList.add(...patientActiveClass.split(' '));
                } else {
                    patientBtn.classList.remove(...patientActiveClass.split(' '));
                }

                if (!isPatient) {
                    doctorBtn.classList.add(...doctorActiveClass.split(' '));
                } else {
                    doctorBtn.classList.remove(...doctorActiveClass.split(' '));
                }
            }

            function setRole(role) {
                roleInput.value = role;
                setDoctorFieldsVisible(role === 'doctor');
                setActiveButtons(role);
            }

            patientBtn?.addEventListener('click', () => setRole('patient'));
            doctorBtn?.addEventListener('click', () => setRole('doctor'));

            const specialtySearch = document.getElementById('doctor-specialty-search');
            const specialtyCountEl = document.getElementById('doctor-specialty-count');
            const specialtyBoxes = () => document.querySelectorAll('#doctor-specialty-grid input[name="specialties[]"]');

            function updateSpecialtyCount() {
                if (!specialtyCountEl) return;
                const n = [...specialtyBoxes()].filter((c) => c.checked).length;
                if (n === 0) {
                    specialtyCountEl.textContent = 'No specialty selected';
                    specialtyCountEl.className = 'text-xs font-medium text-amber-600';
                } else {
                    specialtyCountEl.textContent = n === 1 ? '1 specialty selected' : n + ' specialties selected';
                    specialtyCountEl.className = 'text-xs font-medium text-emerald-700';
                }
            }

            specialtySearch?.addEventListener('input', function () {
                const raw = this.value.trim();
                const q = raw.toLowerCase();
                document.querySelectorAll('[data-specialty-chip]').forEach((chip) => {
                    const blob = (chip.getAttribute('data-search') || '').toLowerCase();
                    const show = q === '' || blob.includes(q);
                    chip.classList.toggle('hidden', !show);
                });
            });

            specialtyBoxes().forEach((c) => c.addEventListener('change', updateSpecialtyCount));

            setRole(roleInput.value || 'patient');
            updateSpecialtyCount();
        });
    </script>
</x-guest-layout>
