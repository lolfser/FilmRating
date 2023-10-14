<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Filmstatus
 *
 * @property int id
 * @property string name
 * @package App\Models
*/
class Filmstatus extends Model {
    protected $table    = 'filmstatus';
    protected $fillable = ['name'];
    protected $casts    = ['id' => 'int'];
}