<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Programblockmetas
 *
 * @property int $id
 * @property int|null $locations_id
 * @property int|null $days_id
 * @property time|null $start
 * @property float|null $total_length
 * @property float|null $puffer_per_item
 * @property-read \App\Models\Locations $location // from belongsTo
 * @property-read \App\Models\Days $day // from belongsTo
 * @package App\Models
*/
class Programblockmetas extends Model {
    protected $table    = 'programblockmetas';
    protected $fillable = ['locations_id','days_id','start','total_length','puffer_per_item'];
    protected $casts    = ['id' => 'int', 'locations_id' => 'int', 'days_id' => 'int'];
    public $timestamps = false;

    public function location() {
        return $this->belongsTo('App\Models\Locations', 'locations_id');
    }

    public function day() {
        return $this->belongsTo('App\Models\Days', 'days_id');
    }
}
