<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Locations
 *
 * @property int $id
 * @property string $name
 * @package App\Models
*/
class Locations extends Model {
    protected $table    = 'locations';
    protected $fillable = ['name'];
    protected $casts    = ['id' => 'int'];
    public $timestamps = false;
}
