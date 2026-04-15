<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $now = now();
        $today = $now->toDateString();
        $nowTime = $now->format('H:i:s');

        $upcoming = Appointment::query()
            ->where('patient_id', Auth::id())
            ->where(function ($q) use ($today, $nowTime) {
                $q->where('appointment_date', '>', $today)
                    ->orWhere(function ($q2) use ($today, $nowTime) {
                        $q2->where('appointment_date', '=', $today)
                            ->where('start_time', '>=', $nowTime);
                    });
            })
            ->with('doctor')
            ->orderBy('appointment_date')
            ->orderBy('start_time')
            ->get();

        $past = Appointment::query()
            ->where('patient_id', Auth::id())
            ->where(function ($q) use ($today, $nowTime) {
                $q->where('appointment_date', '<', $today)
                    ->orWhere(function ($q2) use ($today, $nowTime) {
                        $q2->where('appointment_date', '=', $today)
                            ->where('start_time', '<', $nowTime);
                    });
            })
            ->with('doctor')
            ->orderBy('appointment_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get();

        return view('patient.dashboard', [
            'upcomingAppointments' => $upcoming,
            'pastAppointments' => $past,
        ]);
    }
}
