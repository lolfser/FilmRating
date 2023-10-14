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
 * @property int sources_id
 * @property int film_nr
 * @property int year
 * @property int|null duration
 * @property string|null audio_lang
 * @property string|null subtitle_lang
 * @property string filmstatus_id
 * @property Carbon|null created
 * @property Carbon|null updated
 * @property-read \App\Models\Filmstatus filmstatus // from belongsTo
 * @property-read \App\Models\Filmmodifications filmmodifications // from belongsToMany
 * @property-read \App\Models\Films films // from belongsToMany
 * @property-read \App\Models\Films films // from belongsToMany
 * @property-read \App\Models\Viewers viewers // from belongsToMany
 * @package App\Models
*/
class Films extends Model {
    protected $table    = 'films';
    protected $fillable = ['name','description','sources_id','film_nr','year','duration','audio_lang','subtitle_lang','filmstatus_id','created','updated'];
    protected $casts    = ['id' => 'int', 'sources_id' => 'int', 'film_nr' => 'int', 'year' => 'int', 'duration' => 'int', 'created' => 'datetime', 'updated' => 'datetime'];

    public function filmstatus() {
        return $this->belongsTo('App\Models\Filmstatus', 'filmstatus_id');
    }

    public function filmmodifications() {
        return $this->belongsToMany('App\Models\Filmmodifications');
    }

    public function films() {
        return $this->belongsToMany('App\Models\Films');
    }

    public function films() {
        return $this->belongsToMany('App\Models\Films');
    }

    public function viewers() {
        return $this->belongsToMany('App\Models\Viewers');
    }
}