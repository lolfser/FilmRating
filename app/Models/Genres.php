<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Genres
 *
 * @property int $id
 * @property string $name
 * @package App\Models
*/
class Genres extends Model {
    protected $table    = 'genres';
    protected $fillable = ['name', 'bgcolor', 'fontcolor'];
    protected $casts    = ['id' => 'int'];
    public $timestamps = false;
}
