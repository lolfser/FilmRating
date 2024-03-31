<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Films
 *
 * @property int $id
 * @property string $film_identifier
 * @property string $name
 * @property string|null $description
 * @property int $filmsources_id
 * @property int $year
 * @property int|null $duration
 * @property int|null $filmstatus_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \App\Models\Filmsources $filmsource // from belongsTo
 * @property-read \App\Models\Filmstatus $filmstatus // from belongsTo
 * @property-read \App\Models\Filmmodifications $filmmodifications // from belongsToMany
 * @property-read \App\Models\Films $films // from belongsToMany
 * @property-read \App\Models\Films $films // from belongsToMany
 * @property-read \App\Models\Genres $genres // from belongsToMany
 * @property-read \App\Models\Keywords $keywords // from belongsToMany
 * @property-read \App\Models\Languages $languages // from belongsToMany
 * @property-read \App\Models\Ratings $ratings // from belongsToMany
 * @package App\Models
*/
class Films extends Model {
    protected $table    = 'films';
    protected $fillable = ['film_identifier','name','description','filmsources_id','year','duration','filmstatus_id'];
    protected $casts    = ['id' => 'int', 'filmsources_id' => 'int', 'year' => 'int', 'duration' => 'int', 'filmstatus_id' => 'int', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    public function filmsource() {
        return $this->belongsTo('App\Models\Filmsources', 'filmsources_id');
    }

    public function filmstatus() {
        return $this->belongsTo('App\Models\Filmstatus', 'filmstatus_id');
    }

    public function filmmodifications() {
        return $this->belongsToMany('App\Models\Filmmodifications');
    }

    public function films() {
        return $this->belongsToMany('App\Models\Films');
    }

    public function genres() {
        return $this->belongsToMany('App\Models\Genres');
    }

    public function keywords() {
        return $this->belongsToMany('App\Models\Keywords');
    }

    public function languages() {
        return $this->belongsToMany('App\Models\Languages');
    }

    public function ratings() {
        return $this->hasMany('App\Models\Ratings');
    }
}
