<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $doctorId = Auth::id();

        return view('doctor.dashboard', [
            'stats' => [
                'appointments' => Appointment::where('doctor_id', $doctorId)->count(),
                'earnings' => Appointment::where('doctor_id', $doctorId)->sum('doctor_net_amount'),
                'profit_taken' => Appointment::where('doctor_id', $doctorId)->sum('admin_profit_amount'),
            ],
        ]);
    }
}
