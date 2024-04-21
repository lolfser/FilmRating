<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Programs
 *
 * @property int $id
 * @property string $name
 * @package App\Models
*/
class Programs extends Model {
    protected $table    = 'programs';
    protected $fillable = ['name'];
    protected $casts    = ['id' => 'int'];
    public $timestamps = false;
}
