<?php

namespace App\Http\Controllers;

use App\Data\PlatformData;
use App\Models\Platform;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PlatformController extends Controller
{
    public function index() {
        return Inertia::render("Platforms/Index", [
            'platforms' => Platform::all()
        ]);
    }
    public function create() {
        return Inertia::render("Platforms/Create");
    }
    public function store(Request $request) {
        PlatformData::validate($request);
        Platform::query()->create(['name' => $request->name]);
        return redirect()->route('platforms.index')->with("message", "Платформа " . $request->name . " добавлена!");
    }
    public function edit(Platform $Platform) {
        return Inertia::render("Platforms/Edit", [
            "platform" => $Platform
        ]);
    }
    public function update(Request $request) {
        PlatformData::validate($request);
        Platform::query()->find($request->id)->update([
            'name' => $request->name
        ]);
        return redirect()->route('platforms.index')->with("message", "Платформа " . $request->name . " обновлена!");
    }
    public function destroy(Platform $Platform) {
        $Platform->delete();
        return redirect()->route('platforms.index')->with("message", "Платформа " . $Platform->name . " удалена!");
    }
}
