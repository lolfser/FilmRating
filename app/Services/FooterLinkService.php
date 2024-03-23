<?php

namespace App\Services;

class FooterLinkService {

    public function receive(): array {

       $currentPath = \Illuminate\Support\Facades\Route::getFacadeRoot()->current()->uri();

        $links = [];
        $links[] = $this->build('/films', 'Liste aller Filme', $currentPath === 'films');
        $links[] = $this->build('/rating', ' Filmbewertungen', $currentPath === 'rating');

        if ((new \App\Services\HasPermissionService())->receive(\App\Models\Permissions::PERMISSION_ADD_FILMS)) {
            $links[] = $this->build('/films/0/cu', ' Film erstellen', $currentPath === 'films/0/cu');
        }

        if ((new \App\Services\HasPermissionService())->receive(\App\Models\Permissions::PERMISSION_IMPORT)) {
            $links[] = $this->build('/import', ' IMPORT', $currentPath === 'import');
        }

        $links[] = $this->build('/stats', ' Statistiken', $currentPath === 'stats');

        return $links;

    }

    private function build(string $href, string $label, bool $active): array {
        return [
            'href' => $href,
            'label' => $label,
            'active' => $active,
        ];
    }

}
