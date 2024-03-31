<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Filmstatus
 *
 * @property int $id
 * @property string $name
 * @package App\Models
*/
class Filmstatus extends Model {
    protected $table    = 'filmstatus';
    protected $fillable = ['name'];
    protected $casts    = ['id' => 'int'];
    public $timestamps = false;
}
