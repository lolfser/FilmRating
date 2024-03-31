<?php
declare(strict_types=1);
namespace App\Services;

use App\Models\Permissions;

class HasPermissionService {

    public function receive(int $permission): bool {

        $viewerId = (new ReceiveCurrentViewerIdService())->receive();
        $permissions = Permissions::all()->where('viewers_id', $viewerId)->where('permission', $permission);
        return $permissions->count() === 1;

    }

}
