<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Programmblocks
 *
 * @property int $id
 * @property int $films_id
 * @property-read \App\Models\Films $film // from belongsTo
 * @package App\Models
*/
class Programmblocks extends Model {
    protected $table    = 'programmblocks';
    protected $fillable = ['films_id'];
    protected $casts    = ['id' => 'int', 'films_id' => 'int'];
    public $timestamps = false;

    public function film() {
        return $this->belongsTo('App\Models\Films', 'films_id');
    }
}
