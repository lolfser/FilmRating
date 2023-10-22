<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Viewers
 *
 * @property int users_id
 * @property string initials
 * @property string comment
 * @property-read \App\Models\Users user // from belongsTo
 * @property-read \App\Models\Films films // from belongsToMany
 * @package App\Models
*/
class Viewers extends Model {
    protected $table    = 'viewers';
    protected $fillable = ['users_id','initials','comment'];
    protected $casts    = ['users_id' => 'int'];

    public function user() {
        return $this->belongsTo('App\Models\Users', 'users_id');
    }

    public function films() {
        return $this->hasMany('App\Models\Ratings');
    }
}
