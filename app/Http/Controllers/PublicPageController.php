<?php

namespace App\Http\Controllers;

use App\Models\PageContent;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicPageController extends Controller
{
    public function doctors(Request $request): View
    {
        $query = User::query()
            ->where('role', 'doctor')
            ->whereHas('doctorProfile', fn ($q) => $q->where('status', 'approved'))
            ->with(['doctorProfile', 'doctorSpecialties']);

        if ($request->filled('q')) {
            $q = $request->string('q')->toString();
            $query->where('name', 'like', "%{$q}%");
        }

        if ($request->filled('specialty_id')) {
            $specialtyId = (int) $request->input('specialty_id');
            $query->whereHas('doctorSpecialties', fn ($q) => $q->where('specialties.id', $specialtyId));
        }

        return view('public.doctors', [
            'doctors' => $query->paginate(12)->withQueryString(),
            'specialties' => Specialty::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function specialties(Request $request): View
    {
        $query = Specialty::query()->where('is_active', true);

        if ($request->filled('q')) {
            $q = $request->string('q')->toString();
            $query->where(function ($builder) use ($q) {
                $builder->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        return view('public.specialties', [
            'specialties' => $query->paginate(12)->withQueryString(),
        ]);
    }

    public function about(): View
    {
        $content = PageContent::firstWhere('slug', 'about');
        return view('public.about', compact('content'));
    }

    public function contact(): View
    {
        $content = PageContent::firstWhere('slug', 'contact');
        return view('public.contact', compact('content'));
    }
}
