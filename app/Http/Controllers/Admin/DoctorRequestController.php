<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\SystemMessageNotification;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DoctorRequestController extends Controller
{
    public function index(): View
    {
        $doctors = User::where('role', 'doctor')
            ->whereHas('doctorProfile', fn ($q) => $q->where('status', 'pending'))
            ->with(['doctorProfile', 'doctorSpecialties'])
            ->latest()
            ->get();

        return view('admin.doctor-requests.index', compact('doctors'));
    }

    public function approve(User $doctor): RedirectResponse
    {
        if (! $doctor->isDoctor() || ! $doctor->doctorProfile || $doctor->doctorProfile->status !== 'pending') {
            return back()->withErrors(['doctor' => 'This action cannot be performed for this doctor.']);
        }

        $doctor->doctorProfile->update([
            'status' => 'approved',
            'approved_at' => now()->toDateString(),
            'rejection_reason' => null,
        ]);

        $doctor->notify(new SystemMessageNotification(
            'Your account was approved',
            'Your request was reviewed and approved. You can now receive bookings.',
            'success'
        ));

        return back()->with('success', 'Doctor request approved.');
    }

    public function reject(Request $request, User $doctor): RedirectResponse
    {
        if (! $doctor->isDoctor() || ! $doctor->doctorProfile || $doctor->doctorProfile->status !== 'pending') {
            return back()->withErrors(['doctor' => 'This action cannot be performed for this doctor.']);
        }

        $data = $request->validate([
            'rejection_reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $doctor->doctorProfile->update([
            'status' => 'rejected',
            'rejection_reason' => $data['rejection_reason'] ?? null,
        ]);

        $doctor->notify(new SystemMessageNotification(
            'Doctor request declined',
            'Your request was declined. Reason: '.($data['rejection_reason'] ?? 'No reason provided'),
            'warning'
        ));

        return back()->with('success', 'Doctor request declined.');
    }
}
