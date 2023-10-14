<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Model FilmsKeysords
 *
 * @property int|null film_id
 * @property int|null keyword_id
 * @package App\Models
*/
class FilmsKeysords extends Model {
    protected $table    = 'films_keysords';
    protected $fillable = ['film_id','keyword_id'];
    protected $casts    = ['film_id' => 'int', 'keyword_id' => 'int'];
}