<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Specialty;
use App\Support\InterventionPublicImage;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SpecialtyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.specialties.index', [
            'specialties' => Specialty::latest()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:specialties,name'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        $imagePath = $request->file('image')
            ? InterventionPublicImage::store($request->file('image'), 'specialties')
            : null;

        Specialty::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'image_path' => $imagePath,
            'is_active' => true,
        ]);

        return back()->with('success', 'Specialty created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $specialty = Specialty::findOrFail($id);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:specialties,name,'.$specialty->id],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        $imagePath = $specialty->image_path;
        if ($request->file('image')) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = InterventionPublicImage::store($request->file('image'), 'specialties');
        }

        $specialty->update([
            ...$data,
            'image_path' => $imagePath,
            'is_active' => (bool) ($data['is_active'] ?? false),
        ]);

        return back()->with('success', 'Specialty updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        Specialty::findOrFail($id)->delete();

        return back()->with('success', 'Specialty deleted.');
    }
}
