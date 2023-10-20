<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Model FilmLanguage
 *
 * @property int films_id
 * @property int languages_id
 * @property-read \App\Models\Films film // from belongsTo
 * @property-read \App\Models\Languages language // from belongsTo
 * @package App\Models
*/
class FilmLanguage extends Model {
    protected $table    = 'film_language';
    protected $fillable = ['films_id','languages_id'];
    protected $casts    = ['films_id' => 'int', 'languages_id' => 'int'];

    public function film() {
        return $this->belongsTo('App\Models\Films', 'films_id');
    }

    public function language() {
        return $this->belongsTo('App\Models\Languages', 'languages_id');
    }
}