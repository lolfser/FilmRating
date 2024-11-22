<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Permissions
 *
 * @property int $id
 * @property int $viewers_id
 * @property int $permission
 * @property-read \App\Models\Viewers $viewer // from belongsTo
 * @package App\Models
*/
class Permissions extends Model {

    public const PERMISSION_ADD_VIEWERS = 1;
    public const PERMISSION_EDIT_VIEWERS = 2;
    public const PERMISSION_DELETET_VIEWERS = 3;

    public const PERMISSION_ADD_FILMS = 4;
    public const PERMISSION_EDIT_FILMS = 5;
    public const PERMISSION_DELETE_FILMS = 6;
    public const PERMISSION_LIST_FILMS = 7;

    public const PERMISSION_IMPORT = 8;
    public const PERMISSION_CHANGE_FILMSTATUS = 9;

    public const PERMISSION_SEE_OTHER_VIEWERS_GRADES = 10;

    public const PERMISSION_SEE_PAGE_STATICS = 11;
    public const PERMISSION_SEE_PAGE_PROGRAM = 12;
    public const PERMISSION_SEE_PAGE_RATING = 13;

    protected $table    = 'permissions';
    protected $fillable = ['viewers_id','permission'];
    protected $casts    = ['id' => 'int', 'viewers_id' => 'int', 'permission' => 'int'];

    /**
     * @return BelongsTo<Viewers, Permissions>
     */
    public function viewer(): BelongsTo {
        return $this->belongsTo('App\Models\Viewers', 'viewers_id');
    }
}
