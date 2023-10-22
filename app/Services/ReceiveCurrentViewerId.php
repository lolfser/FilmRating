<?php

namespace App\Services;

use App\Models\Viewers;

class ReceiveCurrentViewerId {

    public function receive(): int {

        $userId = \Auth::id();
        $viewers = Viewers::all()->where('users_id', $userId);
        return $viewers->first()->id;

    }

}
