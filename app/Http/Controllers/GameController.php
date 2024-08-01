<?php

namespace App\Http\Controllers;

use App\Data\GameData;
use App\Models\Game;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GameController extends Controller
{
    public function index() {
        return Inertia::render("Games/Index", [
            'games' => Game::all()
        ]);
    }
    public function create() {
        return Inertia::render("Games/Create");
    }
    public function store(Request $request) {
        GameData::validate($request);
        Game::query()->create(['name' => $request->name]);
        return redirect()->route('games.index')->with("message", "Игра " . $request->name . " добавлена!");
    }
    public function edit(Game $game) {
        return Inertia::render("Games/Edit", [
            "game" => $game
        ]);
    }
    public function update(Request $request) {
        GameData::validate($request);
        Game::query()->find($request->id)->update([
            'name' => $request->name
        ]);
        return redirect()->route('games.index')->with("message", "Игра " . $request->name . " обновлена!");
    }
    public function destroy(Game $game) {
        $game->delete();
        return redirect()->route('games.index')->with("message", "Игра " . $game->name . " удалена!");
    }
}
