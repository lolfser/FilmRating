<?php

namespace App\Services;

use App\Models\Permissions;

class FooterLinkService {

    public function receive(): array {

        $links = [];
        $links[] = $this->build('/films', 'Liste aller Filme');
        $links[] = $this->build('/rating', ' Filmbewertungen');

        if ((new \App\Services\HasPermissionService())->receive(\App\Models\Permissions::PERMISSION_ADD_FILMS)) {
            $links[] = $this->build('/films/0/cu', ' Film erstellen');
        }

        if ((new \App\Services\HasPermissionService())->receive(\App\Models\Permissions::PERMISSION_IMPORT)) {
            $links[] = $this->build('/import', ' IMPORT');
        }

        return $links;

    }

    private function build(string $href, string $label): array {
        return [
            'href' => $href,
            'label' => $label,
        ];
    }

}
