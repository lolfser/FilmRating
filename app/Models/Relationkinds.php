<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Relationkinds
 *
 * @property int $id
 * @property string $name
 * @package App\Models
*/
class Relationkinds extends Model {
    protected $table    = 'relationkinds';
    protected $fillable = ['name'];
    protected $casts    = ['id' => 'int'];
    public $timestamps = false;
}
