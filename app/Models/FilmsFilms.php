<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model FilmsFilms
 *
 * @property int $films1_id
 * @property int $films2_id
 * @property int $relationkinds_id
 * @property-read \App\Models\Relationkinds $relationkind // from belongsTo
 * @package App\Models
*/
class FilmsFilms extends Model {
    protected $table    = 'films_films';
    protected $fillable = ['films1_id','films2_id','relationkinds_id'];
    protected $casts    = ['films1_id' => 'int', 'films2_id' => 'int', 'relationkinds_id' => 'int'];

    /**
     * @return BelongsTo<Relationkinds, FilmsFilms>
     */
    public function relationkind(): BelongsTo {
        return $this->belongsTo('App\Models\Relationkinds', 'relationkinds_id');
    }
}
