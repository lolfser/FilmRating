<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Films
 *
 * @property int id
 * @property string name
 * @property string description
 * @property int filmsources_id
 * @property string film_nr
 * @property int year
 * @property int|null duration
 * @property int|null filmstatus_id
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property-read \App\Models\Filmsources filmsource // from belongsTo
 * @property-read \App\Models\Filmstatus filmstatus // from belongsTo
 * @property-read \App\Models\Filmmodifications filmmodifications // from belongsToMany
 * @property-read \App\Models\Films films // from belongsToMany
 * @property-read \App\Models\Films films // from belongsToMany
 * @property-read \App\Models\Genres genres // from belongsToMany
 * @property-read \App\Models\Keywords keywords // from belongsToMany
 * @property-read \App\Models\Languages languages // from belongsToMany
 * @property-read \App\Models\Viewers viewers // from belongsToMany
 * @package App\Models
*/
class Films extends Model {
    protected $table    = 'films';
    protected $fillable = ['name','description','filmsources_id','film_nr','year','duration','filmstatus_id'];
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
        return $this->belongsToMany('App\Models\Languages', 'film_language');
    }

    public function viewers() {
        return $this->belongsToMany('App\Models\Viewers', 'film_viewer')
            ->withPivot('comment', 'grades_id');
    }
}
