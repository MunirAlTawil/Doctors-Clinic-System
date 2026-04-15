<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\DoctorAvailability;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AvailabilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $doctor = Auth::user()->load('doctorProfile');

        return view('doctor.availability.index', [
            'doctorProfile' => $doctor->doctorProfile,
            'availabilities' => DoctorAvailability::where('doctor_id', Auth::id())
                ->whereDate('work_date', '>=', now()->toDateString())
                ->orderBy('work_date')
                ->limit(30)
                ->get(),
            'weekdays' => [
                'saturday' => 'Saturday',
                'sunday' => 'Sunday',
                'monday' => 'Monday',
                'tuesday' => 'Tuesday',
                'wednesday' => 'Wednesday',
                'thursday' => 'Thursday',
                'friday' => 'Friday',
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'start_time' => ['required', 'regex:/^(?:[01]\\d|2[0-3]):00$/'],
            'end_time' => ['required', 'regex:/^(?:[01]\\d|2[0-3]):00$/'],
            'off_days' => ['required', 'array', 'size:2'],
            'off_days.*' => ['required', 'in:saturday,sunday,monday,tuesday,wednesday,thursday,friday', 'distinct'],
        ], [
            'start_time.regex' => 'Start time must be on the hour only (e.g. 09:00).',
            'end_time.regex' => 'End time must be on the hour only (e.g. 17:00).',
        ]);

        $startTime = $data['start_time'];
        $endTime = $data['end_time'];

        if (! Carbon::createFromFormat('H:i', $endTime)->greaterThan(Carbon::createFromFormat('H:i', $startTime))) {
            return back()->withErrors(['end_time' => 'End time must be after start time.'])->withInput();
        }

        $doctor = Auth::user();
        $doctor->doctorProfile()->updateOrCreate(
            ['user_id' => $doctor->id],
            [
                'work_start_time' => $startTime,
                'work_end_time' => $endTime,
                'off_day_1' => $data['off_days'][0],
                'off_day_2' => $data['off_days'][1],
            ]
        );

        DoctorAvailability::where('doctor_id', $doctor->id)
            ->whereDate('work_date', '>=', now()->toDateString())
            ->delete();

        $offDays = collect($data['off_days']);
        $today = now()->startOfDay();
        $until = now()->addDays(90)->startOfDay();

        for ($date = $today->copy(); $date->lessThanOrEqualTo($until); $date->addDay()) {
            $dayName = strtolower($date->englishDayOfWeek); // saturday, sunday...

            if ($offDays->contains($dayName)) {
                continue;
            }

            DoctorAvailability::create([
                'doctor_id' => $doctor->id,
                'work_date' => $date->toDateString(),
                'start_time' => $startTime,
                'end_time' => $endTime,
                'is_available' => true,
            ]);
        }

        return back()->with('success', 'Weekly schedule saved and future slots updated.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        DoctorAvailability::where('doctor_id', Auth::id())
            ->whereDate('work_date', '>=', now()->toDateString())
            ->delete();

        return back()->with('success', 'Future slots cleared. You can set your schedule again.');
    }
}
