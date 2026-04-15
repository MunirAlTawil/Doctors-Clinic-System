<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageContent;
use App\Support\InterventionPublicImage;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PageContentController extends Controller
{
    public function index(): View
    {
        $pages = PageContent::orderBy('slug')->get();

        return view('admin.pages.index', compact('pages'));
    }

    public function edit(PageContent $page): View
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, PageContent $page): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'body' => ['nullable', 'string'],
            'is_published' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);

        $imagePath = $page->image_path;
        if ($request->file('image')) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = InterventionPublicImage::store($request->file('image'), 'pages');
        }

        $page->update([
            'title' => $data['title'],
            'subtitle' => $data['subtitle'] ?? null,
            'body' => $data['body'] ?? null,
            'is_published' => (bool) ($data['is_published'] ?? false),
            'image_path' => $imagePath,
        ]);

        return redirect()->route('admin.pages.index')->with('success', 'Page content updated.');
    }
}
