<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Specialty;
use App\Support\InterventionPublicImage;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        $doctor = Auth::user()->load(['doctorProfile', 'doctorSpecialties']);
        $specialties = Specialty::where('is_active', true)->orderBy('name')->get();

        return view('doctor.profile.edit', compact('doctor', 'specialties'));
    }
    
    public function update(Request $request): RedirectResponse
    {
        $doctor = Auth::user();

        $data = $request->validate([
            'education_stage' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:2000'],
            'hourly_rate' => ['required', 'numeric', 'min:1'],
            'specialties' => ['required', 'array', 'min:1'],
            'specialties.*' => ['exists:specialties,id'],
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:2048'],
        ], [
            'profile_image.image' => 'The file must be an image.',
            'profile_image.max' => 'The image must not be larger than 2 MB.',
        ]);

        $existing = $doctor->doctorProfile;
        $profileImagePath = $existing?->profile_image;

        if ($request->file('profile_image')) {
            if ($profileImagePath && Storage::disk('public')->exists($profileImagePath)) {
                Storage::disk('public')->delete($profileImagePath);
            }
            $profileImagePath = InterventionPublicImage::store($request->file('profile_image'), 'doctor-profiles');
        }

        $doctor->doctorProfile()->updateOrCreate(
            ['user_id' => $doctor->id],
            [
                'education_stage' => $data['education_stage'] ?? null,
                'bio' => $data['bio'] ?? null,
                'hourly_rate' => $data['hourly_rate'],
                'profile_image' => $profileImagePath,
            ]
        );

        $doctor->doctorSpecialties()->sync($data['specialties']);

        return back()->with('success', 'Doctor profile updated.');
    }
}
