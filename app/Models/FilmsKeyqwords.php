<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Model FilmsKeyqwords
 *
 * @property int|null films_id
 * @property int|null keywords_id
 * @property-read \App\Models\Films film // from belongsTo
 * @property-read \App\Models\Keywords keyword // from belongsTo
 * @package App\Models
*/
class FilmsKeyqwords extends Model {
    protected $table    = 'films_keyqwords';
    protected $fillable = ['films_id','keywords_id'];
    protected $casts    = ['films_id' => 'int', 'keywords_id' => 'int'];

    public function film() {
        return $this->belongsTo('App\Models\Films', 'films_id');
    }

    public function keyword() {
        return $this->belongsTo('App\Models\Keywords', 'keywords_id');
    }
}