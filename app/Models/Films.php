<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Films
 *
 * @property int $id
 * @property string $film_identifier
 * @property string $name
 * @property string|null $description
 * @property int $filmsources_id
 * @property int $year
 * @property int|null $duration
 * @property int|null $filmstatus_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \App\Models\Filmsources $filmsource // from belongsTo
 * @property-read \App\Models\Filmstatus $filmstatus // from belongsTo
 * @property-read \App\Models\Filmmodifications $filmmodifications // from belongsToMany
 * @property-read \App\Models\Films $films // from belongsToMany
 * @property-read \App\Models\Films $films // from belongsToMany
 * @property-read \App\Models\Genres $genres // from belongsToMany
 * @property-read \App\Models\Keywords $keywords // from belongsToMany
 * @property-read \App\Models\Languages $languages // from belongsToMany
 * @property-read \App\Models\Ratings $ratings // from belongsToMany
 * @package App\Models
*/
class Films extends Model {
    protected $table    = 'films';
    protected $fillable = ['film_identifier','name','description','filmsources_id','year','duration','filmstatus_id'];
    protected $casts    = ['id' => 'int', 'filmsources_id' => 'int', 'year' => 'int', 'duration' => 'int', 'filmstatus_id' => 'int', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    /**
     * @return BelongsTo<Filmsources, Films>
     */
    public function filmsource(): BelongsTo {
        return $this->belongsTo('App\Models\Filmsources', 'filmsources_id');
    }

    /**
     * @return BelongsTo<Filmstatus, Films>
     */
    public function filmstatus(): BelongsTo {
        return $this->belongsTo('App\Models\Filmstatus', 'filmstatus_id');
    }

    /**
     * @return BelongsToMany<Filmmodifications>
     */
    public function filmmodifications(): BelongsToMany {
        return $this->belongsToMany('App\Models\Filmmodifications');
    }

    /**
     * @return BelongsToMany<Films>
     */
    public function films(): BelongsToMany {
        return $this->belongsToMany('App\Models\Films');
    }

    /**
     * @return BelongsToMany<Genres>
     */
    public function genres(): BelongsToMany {
        return $this->belongsToMany('App\Models\Genres');
    }

    /**
     * @return BelongsToMany<Keywords>
     */
    public function keywords(): BelongsToMany {
        return $this->belongsToMany('App\Models\Keywords');
    }

    /**
     * @return BelongsToMany<Languages>
     */
    public function languages(): BelongsToMany {
        return $this->belongsToMany('App\Models\Languages');
    }

    /**
     * @return HasMany<Ratings>
     */
    public function ratings(): HasMany {
        return $this->hasMany('App\Models\Ratings');
    }
}
