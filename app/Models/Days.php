<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Days
 *
 * @property int $id
 * @property Carbon $date
 * @package App\Models
*/
class Days extends Model {
    protected $table    = 'days';
    protected $fillable = ['date'];
    protected $casts    = ['id' => 'int', 'date' => 'datetime'];
    public $timestamps = false;
}
