<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Viewers
 *
 * @property int $id
 * @property int $users_id
 * @property string $initials
 * @property string $comment
 * @property-read \App\Models\Users $user // from belongsTo
 * @property-read \App\Models\Films $films // from belongsToMany
 * @package App\Models
*/
class Viewers extends Model {
    protected $table    = 'viewers';
    protected $fillable = ['users_id','initials','comment'];
    protected $casts    = ['id' => 'int', 'users_id' => 'int'];
    public $timestamps = false;

    /**
     * @return BelongsTo<Users, Viewers>
     */
    public function user(): BelongsTo {
        return $this->belongsTo('App\Models\Users', 'users_id');
    }

    /**
     * @return HasMany<Ratings>
     */
    public function films(): HasMany {
        return $this->hasMany('App\Models\Ratings');
    }
}
