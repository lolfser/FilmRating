<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Triggerkinds
 *
 * @property int id
 * @property string name
 * @package App\Models
*/
class Triggerkinds extends Model {
    protected $table    = 'triggerkinds';
    protected $fillable = ['name'];
    protected $casts    = ['id' => 'int'];
}