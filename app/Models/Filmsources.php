<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Filmsources
 *
 * @property int $id
 * @property string $name
 * @package App\Models
*/
class Filmsources extends Model {
    protected $table    = 'filmsources';
    protected $fillable = ['name'];
    protected $casts    = ['id' => 'int'];
    public $timestamps = false;
}
