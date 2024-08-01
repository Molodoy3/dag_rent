<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Platform extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'id',
        'name',
        'created_at'
    ];
    public static function boot()
    {
        parent::boot();

        static::deleting(function ($platform) {
            //убираем аккаунты из индексации algolia
            foreach ($platform->accounts()->get() as $account) {
                $account->unsearchable();
            }
            //удаляем статистику, связанную с аккаунтами
            $platform->accounts()->each(function ($account) {
                $account->statistic()->delete();
            });
            // Мягко удаляем все связанные аккаунты
            $platform->accounts()->delete();
        });
    }
    public function accounts(): HasMany {
        return $this->HasMany(Account::class);
    }
}
