<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\DoctorProfile;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'stats' => [
                'patients' => User::where('role', 'patient')->count(),
                'doctors' => User::where('role', 'doctor')->count(),
                'pending_doctors' => DoctorProfile::where('status', 'pending')->count(),
                'appointments' => Appointment::count(),
                'revenue' => Appointment::sum('total_amount'),
                'admin_profit' => Appointment::sum('admin_profit_amount'),
            ],
        ]);
    }
}
