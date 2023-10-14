<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Filmsources
 *
 * @property int|null id
 * @property string|null name
 * @package App\Models
*/
class Filmsources extends Model {
    protected $table    = 'filmsources';
    protected $fillable = ['name'];
    protected $casts    = ['id' => 'int'];
}