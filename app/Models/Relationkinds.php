<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Relationkinds
 *
 * @property int id
 * @property string name
 * @package App\Models
*/
class Relationkinds extends Model {
    protected $table    = 'relationkinds';
    protected $fillable = ['name'];
    protected $casts    = ['id' => 'int'];
}