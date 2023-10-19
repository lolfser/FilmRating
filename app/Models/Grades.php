<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Grades
 *
 * @property int id
 * @property int value
 * @property string trend
 * @package App\Models
*/
class Grades extends Model {
    protected $table    = 'grades';
    protected $fillable = ['value','trend'];
    protected $casts    = ['id' => 'int', 'value' => 'int'];
    public $timestamps = false;
}
