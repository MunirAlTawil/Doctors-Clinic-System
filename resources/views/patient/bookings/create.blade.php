@extends('layouts.public')

@section('title', 'Book appointment — '.config('app.name'))

@section('header')
    @include('partials.booking-header')
@endsection

@section('content')
    @if (session('success'))
        <div class="mx-auto max-w-7xl px-4 pt-4 sm:px-6 lg:px-8">
            <div class="ui-alert-success">{{ session('success') }}</div>
        </div>
    @endif
    @if ($errors->any())
        <div class="mx-auto max-w-7xl px-4 pt-4 sm:px-6 lg:px-8">
            <div class="ui-alert-error" role="alert">{{ $errors->first() }}</div>
        </div>
    @endif

    <div class="ui-page max-w-7xl pb-16">
        <div class="mb-8 max-w-2xl">
            <span class="text-xs font-semibold uppercase tracking-widest text-cyan-700/90">Scheduling</span>
            <h2 class="ui-marketing-heading mt-2 text-3xl text-slate-900 sm:text-4xl">Book appointment</h2>
            <p class="mt-2 text-slate-600">Choose specialty, doctor, and date, then pick available hours.</p>
        </div>

        <div class="ui-card p-5 sm:p-6">
            <form method="GET" action="{{ route('bookings.create') }}" class="flex flex-wrap items-end gap-4">
                <div class="w-full">
                    <label class="mb-2 block text-sm font-medium text-slate-700">Specialty</label>
                    <div class="mb-2 grid grid-cols-2 gap-2 md:grid-cols-4 lg:grid-cols-6">
                        <button
                            type="button"
                            data-specialty-id=""
                            class="specialty-chip rounded-xl border px-3 py-2 text-sm transition {{ $selectedSpecialtyId === '' ? 'border-cyan-600 bg-gradient-to-r from-cyan-600 to-teal-600 text-white shadow-md' : 'border-cyan-200 text-cyan-900 hover:bg-cyan-50' }}"
                        >
                            All specialties
                        </button>
                        @foreach($specialties as $specialty)
                            <button
                                type="button"
                                data-specialty-id="{{ $specialty->id }}"
                                class="specialty-chip rounded-xl border px-3 py-2 text-sm transition {{ (string)$selectedSpecialtyId === (string)$specialty->id ? 'border-cyan-600 bg-gradient-to-r from-cyan-600 to-teal-600 text-white shadow-md' : 'border-cyan-200 text-cyan-900 hover:bg-cyan-50' }}"
                            >
                                {{ $specialty->name }}
                            </button>
                        @endforeach
                    </div>
                    <select id="specialty_id" name="specialty_id" class="ui-input hidden" onchange="this.form.submit()">
                        <option value="">Select specialty</option>
                        @foreach($specialties as $specialty)
                            <option value="{{ $specialty->id }}" @selected($selectedSpecialtyId == $specialty->id)>{{ $specialty->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="min-w-[240px] flex-1">
                    <label class="mb-1 block text-sm font-medium text-slate-700">Search doctor</label>
                    <input type="text" name="doctor_search" value="{{ $doctorSearch }}" placeholder="Doctor name or email" class="ui-input">
                </div>

                <div class="min-w-[200px]">
                    <label class="mb-1 block text-sm font-medium text-slate-700">Date</label>
                    <input type="date" name="appointment_date" value="{{ $selectedDate }}" class="ui-input">
                </div>

                <input type="hidden" name="doctor_id" value="{{ $selectedDoctorId }}">

                <button class="ui-btn-primary" type="submit">Show available hours</button>
            </form>
        </div>

        <div class="ui-card p-5 sm:p-6">
            <h3 class="ui-card-title mb-4">
                @if($selectedSpecialtyId === '')
                    All approved doctors
                @else
                    Doctors in selected specialty
                @endif
            </h3>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                @forelse($doctors as $doctor)
                    @php
                        $image = $doctor->doctorProfile?->profile_image;
                        $imageUrl = $image
                            ? (str_starts_with($image, 'http') ? $image : asset('storage/'.$image))
                            : null;
                    @endphp
                    <a
                        href="{{ route('bookings.create', array_merge(request()->query(), ['doctor_id' => $doctor->id])) }}"
                        class="{{ (string)$selectedDoctorId === (string)$doctor->id ? 'ring-2 ring-cyan-500 ring-offset-2 ' : '' }} ui-card ui-card-hover block p-4"
                    >
                        <div class="flex items-center gap-3">
                            @if($imageUrl)
                                <img src="{{ $imageUrl }}" alt="{{ $doctor->name }}" class="h-14 w-14 rounded-full border border-cyan-100 object-cover">
                            @else
                                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-gradient-to-br from-cyan-100 to-teal-100 font-bold text-cyan-800">
                                    {{ strtoupper(substr($doctor->name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <h4 class="font-display font-semibold text-slate-900">{{ $doctor->name }}</h4>
                                <p class="text-xs text-slate-500">{{ $doctor->doctorSpecialties->pluck('name')->join(', ') }}</p>
                            </div>
                        </div>
                        <div class="mt-3 text-sm">
                            <span class="text-slate-500">Hourly rate:</span>
                            <span class="font-semibold text-emerald-700">{{ number_format((float)($doctor->doctorProfile?->hourly_rate ?? 0), 2) }}</span>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full text-sm text-slate-500">
                        @if($selectedSpecialtyId === '')
                            No approved doctors at the moment.
                        @else
                            No approved doctors for this specialty.
                        @endif
                    </div>
                @endforelse
            </div>
        </div>

        @if($selectedSpecialtyId !== '' && $selectedDoctorId !== '' && $selectedDate !== '' && count($availableHourSlots) > 0)
            <div class="ui-card p-6 sm:p-8">
                <div class="mb-6">
                    <h3 class="ui-card-title">Available hours</h3>
                    <p class="mt-1 text-sm text-slate-600">Pick consecutive hours. Slots already booked are not shown.</p>
                </div>

                <form method="POST" action="{{ route('bookings.store') }}" class="space-y-4" id="booking-form">
                    @csrf
                    <input type="hidden" name="specialty_id" value="{{ $selectedSpecialtyId }}">
                    <input type="hidden" name="doctor_id" value="{{ $selectedDoctorId }}">
                    <input type="hidden" name="appointment_date" value="{{ $selectedDate }}">

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700">Select hours</label>
                            <div id="hour-grid" class="grid grid-cols-3 gap-2 sm:grid-cols-4 md:grid-cols-5">
                                @foreach($availableHourSlots as $time)
                                    <button
                                        type="button"
                                        data-slot="{{ $time }}"
                                        class="hour-slot rounded-lg border border-cyan-200 px-3 py-2 text-sm text-cyan-800 transition hover:bg-cyan-50"
                                    >
                                        {{ $time }}
                                    </button>
                                @endforeach
                            </div>
                            <p class="mt-2 text-xs text-slate-500">You can select up to 8 consecutive hours.</p>
                            <div id="selected-slots-inputs"></div>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Payment method</label>
                            <select name="payment_method" id="payment_method" class="ui-input" required>
                                <option value="cash" @selected(old('payment_method', 'cash') === 'cash')>Cash</option>
                                <option value="card" @selected(old('payment_method') === 'card')>Card</option>
                            </select>
                        </div>
                    </div>

                    <div id="card-fields-wrapper" class="hidden space-y-3">
                        <div class="flex items-center gap-2 text-xs">
                            <span class="rounded bg-cyan-100 px-2 py-1 font-semibold text-cyan-800">VISA</span>
                            <span class="rounded bg-amber-100 px-2 py-1 font-semibold text-amber-800">MasterCard</span>
                            <span class="text-slate-500">Demo payment gateway.</span>
                        </div>

                        <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                            <select name="card_type" class="ui-input card-field">
                                <option value="visa" @selected(old('card_type', 'visa') === 'visa')>Visa</option>
                                <option value="mastercard" @selected(old('card_type') === 'mastercard')>MasterCard</option>
                            </select>
                            <input type="text" name="cardholder_name" value="{{ old('cardholder_name') }}" placeholder="Cardholder name" class="ui-input card-field">
                            <input type="text" name="card_number" value="{{ old('card_number') }}" placeholder="Card number" class="ui-input card-field">
                            <div class="grid grid-cols-2 gap-2">
                                <input type="text" name="expiry_month" value="{{ old('expiry_month') }}" placeholder="MM" class="ui-input card-field">
                                <input type="text" name="expiry_year" value="{{ old('expiry_year') }}" placeholder="YY" class="ui-input card-field">
                            </div>
                        </div>
                    </div>

                    <button class="ui-btn-primary" type="submit">Confirm booking</button>
                </form>
            </div>
        @elseif($selectedSpecialtyId !== '' && $selectedDoctorId !== '' && $selectedDate !== '' && count($availableHourSlots) === 0)
            <div class="ui-card p-6">
                <h3 class="ui-card-title">No available hours</h3>
                <p class="mt-1 text-sm text-slate-600">Try another date or doctor, or make sure the specialty matches this doctor.</p>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const paymentMethod = document.getElementById('payment_method');
            const cardWrapper = document.getElementById('card-fields-wrapper');
            const cardFields = document.querySelectorAll('.card-field');
            const specialtySelect = document.getElementById('specialty_id');
            const specialtyChips = document.querySelectorAll('.specialty-chip');
            const hourButtons = document.querySelectorAll('.hour-slot');
            const selectedSlotsInputs = document.getElementById('selected-slots-inputs');
            const bookingForm = document.getElementById('booking-form');
            const selectedSlots = new Set();

            specialtyChips.forEach((chip) => {
                chip.addEventListener('click', () => {
                    if (!specialtySelect) return;
                    specialtySelect.value = chip.dataset.specialtyId ?? '';
                    specialtySelect.form?.submit();
                });
            });

            const toggleCardFields = () => {
                if (!paymentMethod || !cardWrapper) return;
                const isCard = paymentMethod.value === 'card';

                cardWrapper.classList.toggle('hidden', !isCard);
                cardFields.forEach((field) => {
                    field.required = isCard;
                    if (!isCard && field.tagName !== 'SELECT') {
                        field.value = '';
                    }
                });
            };

            paymentMethod?.addEventListener('change', toggleCardFields);
            toggleCardFields();

            const sortTime = (arr) => arr.sort((a, b) => a.localeCompare(b));
            const toMinutes = (time) => {
                const [h, m] = time.split(':').map(Number);
                return (h * 60) + m;
            };

            const renderSelectedInputs = () => {
                if (!selectedSlotsInputs) return;
                selectedSlotsInputs.innerHTML = '';
                sortTime(Array.from(selectedSlots)).forEach((slot) => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'selected_slots[]';
                    input.value = slot;
                    selectedSlotsInputs.appendChild(input);
                });
            };

            const refreshButtons = () => {
                hourButtons.forEach((btn) => {
                    const slot = btn.dataset.slot;
                    const active = selectedSlots.has(slot);
                    btn.classList.toggle('bg-gradient-to-r', active);
                    btn.classList.toggle('from-cyan-600', active);
                    btn.classList.toggle('to-teal-600', active);
                    btn.classList.toggle('text-white', active);
                    btn.classList.toggle('border-cyan-600', active);
                });
            };

            hourButtons.forEach((btn) => {
                btn.addEventListener('click', () => {
                    const slot = btn.dataset.slot;

                    if (selectedSlots.has(slot)) {
                        selectedSlots.delete(slot);
                    } else {
                        if (selectedSlots.size >= 8) {
                            alert('Maximum 8 hours per booking.');
                            return;
                        }
                        selectedSlots.add(slot);
                    }

                    const ordered = sortTime(Array.from(selectedSlots));
                    let contiguous = true;
                    for (let i = 1; i < ordered.length; i++) {
                        if ((toMinutes(ordered[i]) - toMinutes(ordered[i - 1])) !== 60) {
                            contiguous = false;
                            break;
                        }
                    }

                    if (!contiguous) {
                        alert('Please select consecutive hours only.');
                        selectedSlots.delete(slot);
                    }

                    renderSelectedInputs();
                    refreshButtons();
                });
            });

            bookingForm?.addEventListener('submit', (e) => {
                if (selectedSlots.size < 1) {
                    e.preventDefault();
                    alert('Please select at least one hour before confirming.');
                }
            });
        });
    </script>
@endsection
