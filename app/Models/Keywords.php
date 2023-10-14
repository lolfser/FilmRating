<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Keywords
 *
 * @property int|null id
 * @property string name
 * @package App\Models
*/
class Keywords extends Model {
    protected $table    = 'keywords';
    protected $fillable = ['name'];
    protected $casts    = ['id' => 'int'];
}