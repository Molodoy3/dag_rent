<?php

namespace App\Models;

use Algolia\ScoutExtended\Facades\Algolia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Game extends Model
{
    use HasFactory;
    use Searchable;
    use SoftDeletes;
    protected $fillable = [
        'id',
        'name',
        'created_at'
    ];
    //при удалении игры также удаляем из индексации Algolia аккаунты связанные и из локальной БД тоже удаляем
    public static function boot()
    {
        parent::boot();

        static::deleting(function ($game) {
            //убираем аккаунты из индексации algolia
            foreach ($game->accounts()->get() as $account) {
                $account->unsearchable();
            }
            //удаляем статистику, связанную с аккаунтами
            $game->accounts()->each(function ($account) {
                $account->statistic()->delete();
            });
            // Мягко удаляем все связанные аккаунты
            $game->accounts()->delete();
        });
    }

    public function searchableAs()
    {
        return 'games'; // Убедитесь, что это совпадает с именем индекса Algolia
    }
    public function toSearchableArray()
    {
        return [
            'id' => (int) $this->id,
            'name' => $this->name,
        ];
    }

    public function accounts(): BelongsToMany {
        return $this->belongsToMany(Account::class, 'account_games');
    }
}
