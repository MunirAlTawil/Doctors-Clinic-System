<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        $patientsCount = \App\Models\User::query()->where('role', 'patient')->count();
        $appointmentsCount = Appointment::query()->count();

        $monthly = Appointment::query()
            ->selectRaw("DATE_FORMAT(appointment_date, '%Y-%m') as month")
            ->selectRaw('COUNT(*) as bookings_count')
            ->selectRaw('SUM(total_amount) as total_revenue')
            ->selectRaw('SUM(admin_profit_amount) as admin_profit')
            ->selectRaw('SUM(doctor_net_amount) as doctors_net')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $topDoctors = Appointment::query()
            ->join('users', 'appointments.doctor_id', '=', 'users.id')
            ->select('users.name')
            ->selectRaw('COUNT(appointments.id) as bookings_count')
            ->selectRaw('SUM(appointments.total_amount) as revenue')
            ->groupBy('appointments.doctor_id', 'users.name')
            ->orderByDesc('revenue')
            ->limit(10)
            ->get();

        return view('admin.reports.index', compact('monthly', 'topDoctors', 'patientsCount', 'appointmentsCount'));
    }
}
