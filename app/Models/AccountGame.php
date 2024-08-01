<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class AccountGame extends Model
{
    use HasFactory;
    use Searchable;
    public function searchableAs()
    {
        return 'AccountGames'; // Убедитесь, что это совпадает с именем индекса Algolia
    }

    protected $fillable = [
      'account_id',
      'game_id',
    ];
}
