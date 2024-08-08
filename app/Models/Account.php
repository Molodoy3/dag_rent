<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\Traits\Date;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
//use Torann\GeoIP\Facades\GeoIP;

class Account extends Model
{
    //мягкое удаление
    use SoftDeletes;
    use HasFactory;
    //на счет поиска
    //use Searchable;
    protected $dates = [
        'created_at',
        'updated_at',
        'busy'
    ];
    //использование мутатора для даты busy, чтобы получать ее в таком же формате что и created_at и updated_at
    // и чтобы не было проблем с часовым поясом
    public function getBusyAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('Y-m-d\TH:i:s.u\Z') : null;
    }
    //хотел преоброзовывать автоматически дату по часовому поясу пользователя, но не получилось что-то
    /*public function setBusyAttribute($value)
    {
        // Предположим, что $userTimezone - это часовой пояс пользователя, полученный ранее
        $userTimezone = \geoip(session()->ip_address);
        $this->attributes['busy'] = Carbon::parse($value, $userTimezone)->utc()->toDateTimeString();
    }*/

    /*public function searchableAs()
    {
        return 'accounts'; // Убедитесь, что это совпадает с именем индекса Algolia
    }
    public function toSearchableArray()
    {
        $games = $this->games()->pluck('name')->toArray();
        return [
            'id' => (int) $this->id,
            'login' => $this->login,
            'password' => $this->password,
            'busy' => $this->busy ? Carbon::parse($this->busy)->format('Y-m-d\TH:i:s.u\Z') : null,
            'platform_name' => $this->getPlatform()->name ?? null,
            'games' => implode(', ', $games),
            'status' => $this->status,
        ];
    }*/
    public function statistic():HasMany {
        return $this->hasMany(Statistic::class);
    }
    public function games(): BelongsToMany
    {
        return $this->belongsToMany(Game::class, 'account_games', 'account_id', 'game_id');
    }
    public function getPlatform() {
        return Platform::query()->findOrFail($this->platform_id);
    }

    public function platform(): BelongsTo
    {
        return $this->belongsTo(Platform::class);
    }

    protected $fillable = [
        'login',
        'password',
        'platform_id',
        'busy',
        'email',
        'passwordEmail',
        'price',
        'comment',
        'id',
        'user_id',
        'status'
    ];
}
