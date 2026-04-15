<x-app-layout>
    <x-slot name="header">
        <h2 class="ui-page-heading">Edit appointment</h2>
    </x-slot>

    @php
        $p = $appointment->patient;
        $startDefault = old('start_time', \Carbon\Carbon::parse($appointment->start_time)->format('H:i'));
        $genderVal = old('patient_gender', $p?->gender);
    @endphp

    <div class="ui-page max-w-2xl">
        <p class="mb-4 text-sm text-slate-600">
            <a href="{{ route('doctor.appointments.index') }}" class="text-cyan-700 hover:underline">← Back to appointments</a>
        </p>

        <x-input-error :messages="$errors->get('specialty_id')" class="mb-4" />

        <form method="POST" action="{{ route('doctor.appointments.update', $appointment) }}" class="ui-card space-y-6 p-6">
            @csrf
            @method('PUT')

            <div class="rounded-xl border border-slate-200 bg-slate-50/80 p-4">
                <h3 class="text-sm font-semibold text-slate-800">Patient</h3>
                <p class="mt-1 text-xs text-slate-500">Contact shown on this appointment (patient login email is not changed).</p>
                <div class="mt-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Name</label>
                        <input type="text" name="patient_name" value="{{ old('patient_name', $p?->name) }}" class="ui-input mt-1" required>
                        <x-input-error :messages="$errors->get('patient_name')" class="mt-2" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Phone</label>
                        <input type="text" name="patient_phone" value="{{ old('patient_phone', $p?->phone) }}" class="ui-input mt-1">
                        <x-input-error :messages="$errors->get('patient_phone')" class="mt-2" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Gender</label>
                        <select name="patient_gender" class="ui-input mt-1">
                            <option value="" @selected($genderVal === null || $genderVal === '')>—</option>
                            <option value="male" @selected($genderVal === 'male')>Male</option>
                            <option value="female" @selected($genderVal === 'female')>Female</option>
                        </select>
                        <x-input-error :messages="$errors->get('patient_gender')" class="mt-2" />
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-4">
                <h3 class="text-sm font-semibold text-slate-800">Visit</h3>
                <div class="mt-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Specialty</label>
                        <select name="specialty_id" class="ui-input mt-1" required>
                            @foreach($specialties as $specialty)
                                <option value="{{ $specialty->id }}" @selected((int) old('specialty_id', $appointment->specialty_id) === $specialty->id)>
                                    {{ $specialty->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('specialty_id')" class="mt-2" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Date</label>
                        <input type="date" name="appointment_date" value="{{ old('appointment_date', $appointment->appointment_date->toDateString()) }}" class="ui-input mt-1" required>
                        <x-input-error :messages="$errors->get('appointment_date')" class="mt-2" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Start time</label>
                        <input type="time" name="start_time" value="{{ $startDefault }}" class="ui-input mt-1" required step="3600">
                        <x-input-error :messages="$errors->get('start_time')" class="mt-2" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Duration (hours)</label>
                        <input type="number" name="hours_count" value="{{ old('hours_count', $appointment->hours_count) }}" min="1" max="8" class="ui-input mt-1" required>
                        <x-input-error :messages="$errors->get('hours_count')" class="mt-2" />
                    </div>
                </div>
            </div>

            <x-input-error :messages="$errors->get('doctor')" class="mt-2" />

            <div class="flex flex-wrap gap-3">
                <button type="submit" class="ui-btn-primary">Save changes</button>
                <a href="{{ route('doctor.appointments.index') }}" class="ui-btn-secondary inline-flex items-center justify-center">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>
