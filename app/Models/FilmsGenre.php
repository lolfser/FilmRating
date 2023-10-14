<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Model FilmsGenre
 *
 * @property int films_id
 * @property int genres_id
 * @property-read \App\Models\Films film // from belongsTo
 * @property-read \App\Models\Genres genre // from belongsTo
 * @package App\Models
*/
class FilmsGenre extends Model {
    protected $table    = 'films_genre';
    protected $fillable = ['films_id','genres_id'];
    protected $casts    = ['films_id' => 'int', 'genres_id' => 'int'];

    public function film() {
        return $this->belongsTo('App\Models\Films', 'films_id');
    }

    public function genre() {
        return $this->belongsTo('App\Models\Genres', 'genres_id');
    }
}