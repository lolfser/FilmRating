<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Model FilmsFilms
 *
 * @property int films1_id
 * @property int films2_id
 * @property int relationkinds_id
 * @property-read \App\Models\Relationkinds relationkind // from belongsTo
 * @package App\Models
*/
class FilmsFilms extends Model {
    protected $table    = 'films_films';
    protected $fillable = ['films1_id','films2_id','relationkinds_id'];
    protected $casts    = ['films1_id' => 'int', 'films2_id' => 'int', 'relationkinds_id' => 'int'];

    public function relationkind() {
        return $this->belongsTo('App\Models\Relationkinds', 'relationkinds_id');
    }
}