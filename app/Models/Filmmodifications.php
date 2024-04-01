<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Model Filmmodifications
 *
 * @property int $id
 * @property string $name
 * @property-read \App\Models\Films $films // from belongsToMany
 * @package App\Models
*/
class Filmmodifications extends Model {
    protected $table    = 'filmmodifications';
    protected $fillable = ['name'];
    protected $casts    = ['id' => 'int'];
    public $timestamps = false;

    /**
     * @return BelongsToMany<Films>
     */
    public function films(): BelongsToMany {
        return $this->belongsToMany('App\Models\Films');
    }
}
