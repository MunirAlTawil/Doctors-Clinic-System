<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\DoctorProfile;
use App\Support\InterventionPublicImage;
use App\Models\Specialty;
use App\Models\User;
use App\Notifications\SystemMessageNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register', [
            'specialties' => Specialty::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $role = $request->input('role') ?: 'patient';
        $isDoctor = $role === 'doctor';

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'role' => ['nullable', 'in:doctor,patient'],
            'gender' => ['nullable', 'in:male,female'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'education_stage' => ['nullable', 'string', 'max:255'],
            'specialties' => ['nullable', 'array'],
            'specialties.*' => ['exists:specialties,id'],
        ];

        if ($isDoctor) {
            $rules['specialties'] = ['required', 'array', 'min:1'];
            $rules['profile_image'] = ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:2048'];
        }

        $request->validate($rules, [
            'specialties.required' => 'Please select at least one medical specialty.',
            'specialties.min' => 'Please select at least one medical specialty.',
            'profile_image.image' => 'The file must be an image.',
            'profile_image.max' => 'The image must not be larger than 2 MB.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $role,
            'gender' => $request->gender,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        if ($role === 'doctor') {
            $profileImagePath = null;
            if ($request->file('profile_image')) {
                $profileImagePath = InterventionPublicImage::store($request->file('profile_image'), 'doctor-profiles');
            }

            DoctorProfile::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'education_stage' => $request->education_stage,
                'hourly_rate' => 0,
                'profile_image' => $profileImagePath,
            ]);

            $user->doctorSpecialties()->sync($request->input('specialties', []));

            Notification::send(
                User::activeAdmins()->get(),
                new SystemMessageNotification(
                    'New doctor registration',
                    'Doctor '.$user->name.' ('.$user->email.') submitted a join request. Review doctor requests to approve or reject.',
                    'info'
                )
            );
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
