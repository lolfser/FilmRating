<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Programblocks
 *
 * @property int $id
 * @property int $films_id
 * @property int $programblockmetas_id
 * @property-read \App\Models\Films $film // from belongsTo
 * @property-read \App\Models\Programblockmetas $programblockmeta // from belongsTo
 * @package App\Models
*/
class Programblocks extends Model {
    protected $table    = 'programblocks';
    protected $fillable = ['films_id','programblockmetas_id'];
    protected $casts    = ['id' => 'int', 'films_id' => 'int', 'programblockmetas_id' => 'int'];
    public $timestamps = false;

    /**
     * @return BelongsTo<Films, Programblocks>
     */
    public function film(): BelongsTo {
        return $this->belongsTo('App\Models\Films', 'films_id');
    }

    /**
     * @return BelongsTo<Programblockmetas, Programblocks>
     */
    public function programblockmeta(): BelongsTo {
        return $this->belongsTo('App\Models\Programblockmetas', 'programblockmetas_id');
    }
}
