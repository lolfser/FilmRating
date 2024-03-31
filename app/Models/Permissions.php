<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    protected $table    = 'permissions';
    protected $fillable = ['viewers_id','permission'];
    protected $casts    = ['id' => 'int', 'viewers_id' => 'int', 'permission' => 'int'];

    public function viewer() {
        return $this->belongsTo('App\Models\Viewers', 'viewers_id');
    }
}
