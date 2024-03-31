<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Keywords
 *
 * @property int|null $id
 * @property string $name
 * @package App\Models
*/
class Keywords extends Model {
    protected $table    = 'keywords';
    protected $fillable = ['name'];
    protected $casts    = ['id' => 'int'];
    public $timestamps = false;
}
