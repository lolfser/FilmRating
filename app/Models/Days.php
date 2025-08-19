<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Days
 *
 * @property int $id
 * @property Carbon $date
 * @package App\Models
*/
class Days extends Model {
    protected $table    = 'days';
    protected $fillable = ['date'];
    protected $casts    = ['id' => 'int', 'date' => 'datetime'];
    protected $appends  = ['dateString'];
    public $timestamps = false;

    public function getDateStringAttribute(): string {
        return $this->attributes['dateString'] ?? '';
    }

    public function setDateStringAttribute(string $value) {
        $this->attributes['dateString'] = $value;
    }
}
