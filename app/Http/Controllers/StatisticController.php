<?php

namespace App\Http\Controllers;

use App\Data\StatisticData;
use App\Events\AccountUpdate;
use App\Models\Account;
use App\Models\Statistic;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StatisticController extends Controller
{
    public function index(Request $request) {
        //dd($request->input("search"));
        return Inertia::render("Statistics/Index", [
            "fields" => Statistic::with('account.platform', 'account.games')->when($request->input("search"), function ($query, $search) {
                $query
                ->where('price', 'like', '%' . $search . '%')
                ->orWhere('added_at', 'like', '%' . $search . '%')
                ->orWhereHas('account', function ($query) use ($search) {
                    $query->where('login', 'like', '%' . $search . '%');
                })
                ->orWhereHas('account.games', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                })
                ->orWhereHas('account.platform', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                });
            })
                ->orderBy('added_at', 'desc')
                ->paginate(100)
                ->withQueryString()
                ->fragment('sales'),

            "general" => [
                "money" => Statistic::sum("price"),
                "count" => Statistic::count(),
                "averagePrice" => Statistic::sum("price") > 0 ? round(Statistic::sum("price") / Statistic::count(), 2) : 0
            ]
        ]);
    }
    public function edit(Statistic $statistic) {
        return Inertia::render("Statistics/Edit", [
            'sale' => Statistic::with('account.platform')->find($statistic->id)
        ]);
    }
    public function update(Request $request) {
        $statistic = $request->statistic;
        StatisticData::validate($request->statistic);

        $busy = $statistic['added_at'] ? Carbon::createFromFormat('d.m H:i', $statistic['added_at'], $statistic['timezone']) : null;
        // Преобразуем время из одного часового пояса в UTC (+0)
        $busyUtc = $busy ? $busy->setTimezone('UTC') : null;
        $statistic['added_at'] = $busyUtc;

        Statistic::query()->find($statistic['id'])->update($statistic);
        //$statistic->update();
        return redirect()->route('statistics.index')->with("message", "Продажа успешна обновлена!");
        //dd($request->statistic);
    }
    public function destroy(Statistic $statistic) {
        $statistic->delete();
        return redirect()->route('statistics.index')->with("message", "Продажа успешна удалена!");
    }
    public function store(Request $request) {
        $statistic = $request->statistic;
        StatisticData::validate($statistic);

        //$statistic['added_at'] = DateTime::createFromFormat('d.m H:i', $statistic['added_at']);
        $busy = $statistic['added_at'] ? Carbon::createFromFormat('d.m H:i', $statistic['added_at'], $statistic['timezone']) : null;
        // Преобразуем время из одного часового пояса в UTC (+0)
        $busyUtc = $busy ? $busy->setTimezone('UTC') : null;
        $statistic['added_at'] = $busyUtc;

        Statistic::query()->create($statistic);
        return redirect()->route('statistics.index')->with("message", "Продажа успешна добавлена!");
    }
    public function create() {
        return Inertia::render("Statistics/Create", [
            'accounts' => Account::with('games')->orderBy("login")->get(),
        ]);
    }
    /*public function updateData() {
        event(
            new AccountUpdate(
                statistics: Statistic::all()
            )
        );
    }*/
    //в использовании команды CheckAccountDates (когда время аренды конается)
    public function add(Account $account) {
        Statistic::query()->create([
            "price" => $account->price,
            'account_id' => $account->id,
            'added_at' => now()
        ]);
    }
}
