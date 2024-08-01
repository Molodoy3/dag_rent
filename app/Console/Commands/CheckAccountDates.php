<?php

namespace App\Console\Commands;

use App\Http\Controllers\NotificationManagerController;
use App\Http\Controllers\StatisticController;
use App\Mail\OrderShipped;
use App\Models\Account;
use App\Models\User;
use App\Notifications\AccountFree;
use DateTime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CheckAccountDates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-account-dates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $accountsLiberated = Account::query()->where('busy', '!=', null)->get();

        foreach ($accountsLiberated as $account) {
            if (new DateTime($account->busy) < new DateTime()) {
                //сброс даты
                $account->busy=null;
                $account->status=1;
                $account->save();

                //добавление статистики
                $statisticController = new StatisticController();
                $statisticController->add($account);

                //отправка уведомлений и писем админам
                $admins = User::query()->where('role_id', '=', 1)->get();
                foreach ($admins as $admin) {
                    //проверка на то, что пользователь подписан на уведомления
                    if (count($admin->routeNotificationForWebPush())) {
                        $admin->notify(new AccountFree($account));
                        Mail::to($admin)->send(new OrderShipped($account));
                    }
                }
            }
        }
    }
}
