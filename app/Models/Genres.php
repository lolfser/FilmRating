<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Genres
 *
 * @property int id
 * @property string name
 * @package App\Models
*/
class Genres extends Model {
    protected $table    = 'genres';
    protected $fillable = ['name'];
    protected $casts    = ['id' => 'int'];
    public $timestamps = false;
}
