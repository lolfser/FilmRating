<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Model FilmsFilmmodifications
 *
 * @property int films_id
 * @property int filmmodifications_id
 * @property-read \App\Models\Films film // from belongsTo
 * @property-read \App\Models\Filmmodifications filmmodification // from belongsTo
 * @package App\Models
*/
class FilmsFilmmodifications extends Model {
    protected $table    = 'films_filmmodifications';
    protected $fillable = ['films_id','filmmodifications_id'];
    protected $casts    = ['films_id' => 'int', 'filmmodifications_id' => 'int'];

    public function film() {
        return $this->belongsTo('App\Models\Films', 'films_id');
    }

    public function filmmodification() {
        return $this->belongsTo('App\Models\Filmmodifications', 'filmmodifications_id');
    }
}