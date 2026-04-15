<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        $doctorId = Auth::id();

        $monthly = Appointment::query()
            ->where('doctor_id', $doctorId)
            ->selectRaw("DATE_FORMAT(appointment_date, '%Y-%m') as month")
            ->selectRaw('COUNT(*) as bookings_count')
            ->selectRaw('SUM(total_amount) as total_revenue')
            ->selectRaw('SUM(doctor_net_amount) as net_earnings')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('doctor.reports.index', compact('monthly'));
    }
}
