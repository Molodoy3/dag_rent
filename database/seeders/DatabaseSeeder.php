<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Game;
use App\Models\Platform;
use App\Models\Role;
use App\Models\Statistic;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Auth::factory(10)->create();

        /*Auth::factory()->create([
            'name' => 'Test Auth',
            'email' => 'test@example.com',
        ]);
        Role::factory()->create([
            'name' => 'admin',
        ]);
        Role::factory()->create([
            'name' => 'client',
        ]);
        Game::factory(5)->create();
        Platform::factory(5)->create();
        AccountData::factory(5)->create();*/

        //ошибку выдает
        //Statistic::factory(10)->create();
    }
}
