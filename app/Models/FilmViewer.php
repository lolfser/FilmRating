<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Model FilmViewer
 *
 * @property int films_id
 * @property int viewers_id
 * @property string comment
 * @property int grades_id
 * @property-read \App\Models\Films film // from belongsTo
 * @property-read \App\Models\Viewers viewer // from belongsTo
 * @property-read \App\Models\Grades grade // from belongsTo
 * @package App\Models
*/
class FilmViewer extends Model {
    protected $table    = 'film_viewer';
    protected $fillable = ['films_id','viewers_id','comment','grades_id'];
    protected $casts    = ['films_id' => 'int', 'viewers_id' => 'int', 'grades_id' => 'int'];

    public function film() {
        return $this->belongsTo('App\Models\Films', 'films_id');
    }

    public function viewer() {
        return $this->belongsTo('App\Models\Viewers', 'viewers_id');
    }

    public function grade() {
        return $this->belongsTo('App\Models\Grades', 'grades_id');
    }
}
