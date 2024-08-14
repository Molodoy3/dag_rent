<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        //if (auth()->user())
        //    abort(403);

        // Получаем ID пользователя
        $userId = $request->user() ? $request->user()->id : null;

        // Логика определения изображения
        $userDir = Storage::disk('public')->files('/img/public/users/' . $userId);
        $userIcon = count($userDir) ? '/storage/' . $userDir[0] : '/storage/img/public/users/default.webp';

        return array_merge(parent::share($request), [
            'flash' => [
                'message' => fn () => $request->session()->get('message'),
                'id_elem' => fn () => $request->session()->get('id_elem'),
            ],
            'auth' => [
                'id' => $request->user() ? $request->user()->id : null,
                'user' => $request->user(),
                'isAdmin' => $request->user() ? ( $request->user()->role_id == 1 ? true : false ) : null,
                'icon' => $userIcon,  // Добавляем иконку
                /*'permissions' => [
                    'post' => [
                        //'update' => $request->post ? Gate::allows('update-post', $request->post) : false,
                    ],
                ],*/
            ],
        ]);
    }
}
