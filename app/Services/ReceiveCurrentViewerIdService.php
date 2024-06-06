<?php
declare(strict_types=1);
namespace App\Services;

use App\Models\Viewers;

class ReceiveCurrentViewerIdService {

    public function receive(): int {

        $userId = \Auth::id();
        $viewers = Viewers::all()->where('users_id', $userId);
        return (int) $viewers->first()?->id;

    }

}
