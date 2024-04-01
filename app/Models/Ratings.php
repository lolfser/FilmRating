<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Ratings
 *
 * @property int $id
 * @property int $films_id
 * @property int $viewers_id
 * @property string $comment
 * @property int $grades_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read \App\Models\Films $film // from belongsTo
 * @property-read \App\Models\Viewers $viewer // from belongsTo
 * @property-read \App\Models\Grades $grade // from belongsTo
 * @package App\Models
*/
class Ratings extends Model {
    protected $table    = 'ratings';
    protected $fillable = ['films_id','viewers_id','comment','grades_id'];
    protected $casts    = ['id' => 'int', 'films_id' => 'int', 'viewers_id' => 'int', 'grades_id' => 'int', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    /**
     * @return BelongsTo<Films, Ratings>
     */
    public function film(): BelongsTo {
        return $this->belongsTo('App\Models\Films', 'films_id');
    }

    /**
     * @return BelongsTo<Viewers, Ratings>
     */
    public function viewer(): BelongsTo {
        return $this->belongsTo('App\Models\Viewers', 'viewers_id');
    }

    /**
     * @return BelongsTo<Grades, Ratings>
     */
    public function grade(): BelongsTo {
        return $this->belongsTo('App\Models\Grades', 'grades_id');
    }
}
