<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Statistic extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $dates = [
        'added_at'
    ];
    public function getAddedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d\TH:i:s.u\Z');
    }
    public $timestamps = false;
    protected $fillable = [
        'id',
        'account_id',
        'price',
        'added_at'
    ];

    public function account():BelongsTo {
        return $this->belongsTo(Account::class);
    }
}
