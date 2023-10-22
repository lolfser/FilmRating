<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Languages
 *
 * @property int id
 * @property string language
 * @property string type
 * @property-read \App\Models\Films films // from belongsToMany
 * @package App\Models
*/
class Languages extends Model {
    protected $table    = 'languages';
    protected $fillable = ['language','type'];
    protected $casts    = ['id' => 'int'];
    public $timestamps = false;

    public function films() {
        return $this->belongsToMany('App\Models\Films', 'film_language');
    }
}
