<?php

namespace App\Http\Controllers;

use App\Data\UserData;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Role;
use App\Models\User;
use DateTime;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\In;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use PhpParser\Token;

class UserController extends Controller
{
    public function create() {
        return Inertia::render("Auth/Login");
    }
    public function createNew() {
        //dd(1);

        return Inertia::render("Auth/CreateNew", [
            "roles" => Role::all()
        ]);
    }
    public function storeNew(Request $request) {
        //dd($request->user['name']);
        $user = $request->user;
        UserData::validate($user);

        $user = User::create([
            'name' => $user['name'],
            'email' => $user['email'],
            'role_id' => $user['role_id'],
            'password' => Hash::make($user['password']),
        ]);

        $user->markEmailAsVerified();

        //event(new Registered($user));

        return redirect()->route('user.show', auth()->user()->id)->with("message", "Новый пользователь " . $user->name . " добавлен!");
    }
    public function store(LoginRequest $request): RedirectResponse {
        //dd($request);
        $request->authenticate();
        $request->session()->regenerate();
        return redirect()->route('accounts.index')->with('message', 'Добро пожаловать');
    }
    public function show($id) {
        $user = User::find($id);
        $isPushSubscription =  false;
        if (count($user->routeNotificationForWebPush()))
            $isPushSubscription =  true;

        //для вывода картинки пользователя
        $userDir = Storage::disk('public')->files('/img/public/users/'.$id);
        if (count($userDir))
            $userIcon = '/storage/' . $userDir[0];
        else
            $userIcon = '/storage/img/public/users/default.webp';

        return Inertia::render("Auth/Profile", [
            "user" => $user,
            "isPushSubscription" => $isPushSubscription,
            "userIcon" => $userIcon
        ]);
    }
    public function editPassword() {
        return Inertia::render('Auth/EditPassword');
    }
    public function updatePassword(Request $request) {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('user.show', auth()->user()->id)->with("message", "Пароль успешно обновлен!");
    }
    public function logout(Request $request): RedirectResponse
    {
        //dd(1);
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('user.login');
    }
    public function updateIcon(Request $request) {
        $userID = $request->userID;

        $directory = 'img/public/users/' . $userID;
        //dd(!Storage::disk('public')->exists($directory));
        if (!Storage::disk('public')->exists($directory)) {
            Storage::makeDirectory($directory);
            Storage::disk('public')->putFile($directory, $request->allFiles()['image'][0]);
        } else {
            // Чистим папку перед загрузкой нового изображения
            Storage::disk('public')->deleteDirectory($directory); // Удаляем текущую папку (включая содержимое)
            Storage::makeDirectory($directory); // Создаем папку заново
            // Загружаем новое изображение
            Storage::disk('public')->putFile($directory, $request->allFiles()['image'][0]);
        }


        /*$user = User::find($userID);
        $userDir = Storage::disk('public')->files('/img/public/users/'.$userID);
        $userIcon = '/storage/' . $userDir[0];
        $isPushSubscription =  false;
        if (count($user->routeNotificationForWebPush()))
            $isPushSubscription =  true;
        return Inertia::render("Auth/Profile", [
            "user" => $user,
            "isPushSubscription" => $isPushSubscription,
            "userIcon" => $userIcon
        ])->with("message", "Ваша аватарка успешно обновлена!");*/
        return redirect()->route('user.show', $userID)->with("message", "Ваша аватарка успешно обновлена!");
    }
}
