<?php

namespace App\Console\Commands;

use App\Events\AccountUpdate;
use App\Models\Statistic;
use Illuminate\Console\Command;

class UpdateStatisticsData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-statistics-data';

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
        event(
            new AccountUpdate(
                //statistics: Statistic::all()
            )
        );
    }
}
