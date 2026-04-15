<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClinicSetting;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $setting = ClinicSetting::firstOrCreate([], [
            'admin_profit_percent' => 15,
        ]);

        return view('admin.settings.index', compact('setting'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'admin_profit_percent' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        $setting = ClinicSetting::firstOrCreate([]);
        $setting->update($data);

        return back()->with('success', 'Settings updated.');
    }
}
