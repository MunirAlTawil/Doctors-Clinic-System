<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Specialty;
use App\Models\User;
use App\Support\InterventionPublicImage;
use App\Notifications\SystemMessageNotification;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query()->latest();

        if ($request->filled('role')) {
            $query->where('role', $request->string('role')->toString());
        }

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        return view('admin.users.index', [
            'users' => $query->paginate(15)->withQueryString(),
        ]);
    }

    public function toggleActive(User $user): RedirectResponse
    {
        if (auth()->id() === $user->id) {
            return back()->withErrors(['user' => 'You cannot deactivate your own account.']);
        }

        $newStatus = ! $user->is_active;
        $user->update([
            'is_active' => $newStatus,
        ]);

        $user->notify(new SystemMessageNotification(
            $newStatus ? 'Your account was activated' : 'Your account was deactivated',
            $newStatus
                ? 'An administrator reactivated your account. You can use the system again.'
                : 'Your account was deactivated by the clinic. Contact support for details.',
            $newStatus ? 'success' : 'warning'
        ));

        return back()->with('success', 'User status updated.');
    }

    public function resetPassword(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($data['password']),
        ]);

        return back()->with('success', 'Password updated.');
    }

    public function notify(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'message' => ['required', 'string', 'max:2000'],
            'type' => ['nullable', 'in:info,success,warning'],
        ]);

        $user->notify(new SystemMessageNotification(
            $data['title'],
            $data['message'],
            $data['type'] ?? 'info'
        ));

        return back()->with('success', 'Notification sent.');
    }

    public function edit(User $user): View
    {
        $user->load(['doctorProfile', 'doctorSpecialties']);

        return view('admin.users.edit', [
            'user' => $user,
            'specialties' => Specialty::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'phone' => ['nullable', 'string', 'max:30'],
            'gender' => ['nullable', 'in:male,female'],
            'is_active' => ['nullable', 'boolean'],
            'education_stage' => ['nullable', 'string', 'max:255'],
            'hourly_rate' => ['nullable', 'numeric', 'min:0'],
            'bio' => ['nullable', 'string', 'max:2000'],
            'status' => ['nullable', 'in:pending,approved,rejected'],
            'specialties' => ['nullable', 'array'],
            'specialties.*' => ['exists:specialties,id'],
            'profile_image' => ['nullable', 'image', 'max:3072'],
        ]);

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'gender' => $data['gender'] ?? null,
            'is_active' => (bool) ($data['is_active'] ?? false),
        ]);

        if ($user->isDoctor()) {
            $profileImage = $user->doctorProfile?->profile_image;
            if ($request->file('profile_image')) {
                if ($profileImage) {
                    Storage::disk('public')->delete($profileImage);
                }
                $profileImage = InterventionPublicImage::store($request->file('profile_image'), 'doctor-profiles');
            }

            $user->doctorProfile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'education_stage' => $data['education_stage'] ?? null,
                    'hourly_rate' => $data['hourly_rate'] ?? 0,
                    'bio' => $data['bio'] ?? null,
                    'status' => $data['status'] ?? 'pending',
                    'profile_image' => $profileImage,
                ]
            );

            $user->doctorSpecialties()->sync($data['specialties'] ?? []);
        }

        $user->notify(new SystemMessageNotification(
            'Your account was updated',
            'The clinic updated some of your profile information. Please review your details.',
            'info'
        ));

        return redirect()->route('admin.users.index')->with('success', 'User updated.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if (auth()->id() === $user->id) {
            return back()->withErrors(['user' => 'You cannot delete your own account.']);
        }

        $user->delete();

        return back()->with('success', 'User deleted.');
    }
}
