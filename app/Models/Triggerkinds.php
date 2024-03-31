<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Triggerkinds
 *
 * @property int $id
 * @property string $name
 * @package App\Models
*/
class Triggerkinds extends Model {
    protected $table    = 'triggerkinds';
    protected $fillable = ['name'];
    protected $casts    = ['id' => 'int'];
    public $timestamps = false;
}
