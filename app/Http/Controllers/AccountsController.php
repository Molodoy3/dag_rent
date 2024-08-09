<?php

namespace App\Http\Controllers;

use App\Data\AccountData;
use App\Mail\OrderShipped;
use App\Models\Account;
use App\Models\AccountGame;
use App\Models\Game;
use App\Models\Platform;
use App\Models\User;
use App\Notifications\AccountFree;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class AccountsController extends Controller
{
    public function index(Request $request) {

/*        dd(Account::with("platform", "games")->when($request->input("search"), function ($query, $search) {
            $query
                ->where('login', 'like', '%' . $search . '%')
                ->orWhere('busy', 'like', '%' . $search . '%')
                ->orWhereHas('platform', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                })
                ->orWhereHas('account.games', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                });
        })->get()[2]->games);*/

        //dd(Account::search('mr')->get());

        return Inertia::render("Accounts/Index", [
            "platforms" => Platform::query()->orderBy("name")->get(),

            "accounts" => $accounts = Account::query()->
                //делаем связь
                with("platform", "games")
                    //используем поле для обработки запроса
                    ->when($request->input("platform_id"), function ($query, $platform_id) {
                            //поиск со связанной таблицей
                            $query->whereHas("platform", function ($query) use ($platform_id) {
                                $query->where('id', 'like', '%' . $platform_id . '%');
                            });
                    })
                    ->when($request->input("search"), function ($query, $search) {
                        $query
                            ->where('login', 'like', '%' . $search . '%')
                            ->orWhere('busy', 'like', '%' . $search . '%')
                            ->orWhereHas('platform', function ($query) use ($search) {
                                $query->where('name', 'like', '%' . $search . '%');
                            })
                            ->orWhereHas('games', function ($query) use ($search) {
                                $query->where('name', 'like', '%' . $search . '%');
                            });
                    })
                //сортировка
                ->orderBy("status", "desc")
                ->orderByRaw('busy IS NULL, busy ASC')
                ->paginate(100)
                ->fragment('accounts')
                ->withQueryString(),
            'links' => $accounts
        ]);
    }
    public function show(Account $account) {
        return Inertia::render("Accounts/Show", [
            'account' => $account
        ]);
    }

    public function create() {
        if (!auth()->user())
            abort(403);
        $idPlatform = Platform::query()->first()->id;
        $idUser = auth()->id();
        return Inertia::render("Accounts/Create", [
            'account' => new AccountData(null, "", "", $idPlatform, null, "", "", 0, 1, "",$idUser),
            'platforms' => Platform::all('id', 'name'),
            'games' => Game::all()
        ]);
    }
    public function store(Request $account) {
        //dd($request);
        //проверка полей аккаунта и добавление в табл
        AccountData::validate($account);

        //создание акка + ДОБАВЛЕНИЕ СВЯЗИ ДЛЯ ТОГО ЧТОБЫ ПОИСК РАБОТАЛ КАК НАДО
        $accountData = [
            'id' => $account['id'],
            'login' => $account['login'],
            'password' => $account['password'],
            'platform_id' => $account['platform_id'],
            //'busy' => $account['busy'] ? DateTime::createFromFormat('d.m H:i', $account['busy']) : null,
            'email' => $account['email'],
            'passwordEmail' => $account['passwordEmail'],
            'price' => $account['price'],
            'comment' => $account['comment'],
            //'status' => $status,
            'user_id' => $account['user_id'],
        ];
        $accountNew = Account::query()->with('accountGames')->with('platforms')->create($accountData);

        //добавление игр для акка
        $accountId =$accountNew->id;
        $selectedGames = $account->games;
        foreach ($selectedGames as $gameId) {
            AccountGame::query()->create([
                'account_id' => $accountId,
                'game_id' => $gameId
            ]);
        }

        //ДОБАВЛЯЕМ В ИНДЕКАСАЦИЮ ДЛЯ ПОИСКА ПОЛЯ
        //$accountNew->searchable();
        //$accountNew->games->searchable();

        //можно менять public на local
        $directory = 'public/img/public/accounts/' . $accountId; // Здесь добавляем 'public/' в путь
        if (!Storage::disk('public')->exists($directory)) {
            Storage::makeDirectory($directory);
            if (isset($account->allFiles()['images'])) {
                foreach ($account->allFiles()['images'] as $image) {
                    Storage::disk('public')->putFile($directory, $image);
                }
            }
        }

        return redirect()->route('accounts.index')->with("message", "Аккаунт " . $accountNew->login . " успешно добавлен!");
    }
    public function edit(Account $account) {
        //Mail::to(User::query()->first())->send(new OrderShipped());
        $images = Storage::disk('public')->files('/img/public/accounts/'.$account->id);
        foreach ($images as $image) {
            $image = Storage::disk('public')->url($image);
        }
        return Inertia::render("Accounts/Edit", [
            'account' => $account,
            'gamesAccount' => $account->games()->get()->pluck('id'),
            'games' => Game::all(),
            'platforms' => Platform::all(),
            'images' => $images
        ]);
    }
    public function update(Request $account) {
        //Валидация
        AccountData::validate($account);

        //Удаление изображений
        if (isset($account['imagesForDel'])) {
            $UpdatingImages = $account['imagesForDel'];
            $oldImagesFolder = Storage::disk('public')->files('/img/public/accounts/'.$account['id']);

            $imagesToDelete = []; // Массив для хранения путей к изображениям для удаления
            // Сравнение массивов и формирование списка изображений для удаления
            foreach ($oldImagesFolder as $oldImage) {
                $found = false;
                foreach ($UpdatingImages as $newImageName) {
                    if ($oldImage === $newImageName) {
                        $found = true;
                        break;
                    }
                }
                if (!$found)
                    $imagesToDelete[] = $oldImage;
            }
            // Удаление изображений из хранилища
            foreach ($imagesToDelete as $imageToDelete) {
                Storage::disk('public')->delete($imageToDelete);
            }
        }

        $status = null;
        if (!$account['busy']) {
            $status = $account['status'] == 0 ? null : $account['status'];
        }

        // формируем время из полученного формата и учитываем часовой пояс
        $busy = $account['busy'] ? Carbon::createFromFormat('d.m H:i', $account['busy'], $account['timezone']) : null;
        // Преобразуем время из одного часового пояса в UTC (+0)
        $busyUtc = $busy ? $busy->setTimezone('UTC') : null;
        $accountData = [
            'id' => $account['id'],
            'login' => $account['login'],
            'password' => $account['password'],
            'platform_id' => $account['platform_id'],
            'busy' => $busyUtc,
            'email' => $account['email'],
            'passwordEmail' => $account['passwordEmail'],
            'price' => $account['price'],
            'comment' => $account['comment'],
            'status' => $status,
            'user_id' => $account['user_id'],
        ];
        $accountId =$account['id'];
        Account::query()->with('accountGames')->with('platforms')->where('id', $accountId)->update($accountData);
        $accountUpdate = Account::query()->find($accountId);

        //удаление старых игр
        AccountGame::query()->where('account_id', '=', $accountId)->delete();
        //добавление игр для акка
        $selectedGames = $account->games;
        foreach ($selectedGames as $gameId) {
            AccountGame::query()->create([
                'account_id' => $accountId,
                'game_id' => $gameId
            ]);
        }

        //ДОБАВЛЯЕМ В ИНДЕКАСАЦИЮ ДЛЯ ПОИСКА ПОЛЯ
        //$accountUpdate->searchable();
        //$accountUpdate->games->searchable();

        //Загрузка изображений в папку под именем id аккаунта
        $directory = '/img/public/accounts/' . $accountId;
        if (!Storage::disk('public')->exists($directory))
            Storage::disk('public')->makeDirectory($directory);
        if (isset($account->allFiles()['images'])) {
            foreach ($account->allFiles()['images'] as $image) {
                Storage::disk('public')->putFile($directory, $image);
            }
        }

        return redirect()->route('accounts.index')->with("message", "Аккаунт " . $account['login'] . " успешно обновлен!");
    }
    public function destroy(Account $account) {
        $account->delete();

        return redirect()->route('accounts.index')->with("message", "Аккаунт " . $account['login'] . " успешно удален!");
    }
}
