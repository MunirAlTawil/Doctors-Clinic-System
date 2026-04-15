<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function index(Request $request): View
    {
        $query = Appointment::query()
            ->with(['patient', 'doctor']);

        if ($request->filled('date')) {
            $query->where('appointment_date', $request->string('date'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        } else {
            // Default oversight scope for graduation demo: show booked appointments only
            $query->where('status', 'booked');
        }

        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', (int) $request->string('doctor_id'));
        }

        if ($request->filled('patient_search')) {
            $search = $request->string('patient_search')->toString();
            $query->whereHas('patient', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $appointments = $query->latest()->paginate(15);

        $doctors = User::where('role', 'doctor')->with('doctorProfile')->get();

        return view('admin.appointments.index', [
            'appointments' => $appointments,
            'doctors' => $doctors,
            'filters' => $request->all(),
        ]);
    }
}
