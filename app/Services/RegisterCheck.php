<?php
declare(strict_types=1);
namespace App\Services;

use App\Models\Viewers;

class RegisterCheck {

    public function handle(int $userId): void {

        if (count(Viewers::all()) === 0) {
            $viewer = new Viewers();
            $viewer->initials = 'admin';
            $viewer->comment = 'auto generated with first registered user';
            $viewer->users_id = $userId;
            $viewer->save();
        }

    }

}
