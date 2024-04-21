<?php
declare(strict_types=1);
namespace App\Services;

class HeaderLinkService {

    /**
     * @return array<mixed>
     */
    public function receive(): array {

        $currentPath = \Illuminate\Support\Facades\Route::getFacadeRoot()->current()->uri();

        $links = [];
        $links[] = $this->build('/films', 'Liste aller Filme', $currentPath === 'films');
        $links[] = $this->build('/rating', 'Filmbewertungen', $currentPath === 'rating');

        if ((new \App\Services\HasPermissionService())->receive(\App\Models\Permissions::PERMISSION_ADD_FILMS)) {
            $wording = (\Illuminate\Support\Facades\Route::getFacadeRoot()->current()->id > 0)
                ? 'Film bearbeiten'
                : 'Film erstellen';
            $links[] = $this->build('/films/0/cu', $wording, $currentPath === 'films/{id}/cu');
        }

        if ((new \App\Services\HasPermissionService())->receive(\App\Models\Permissions::PERMISSION_IMPORT)) {
            $links[] = $this->build('/import', 'IMPORT', $currentPath === 'import');
        }

        $links[] = $this->build('/stats', 'Statistiken', $currentPath === 'stats');
        $links[] = $this->build('/program', 'Programm', $currentPath === 'program');

        return $links;

    }

    /**
     * @return array<mixed>
     */
     private function build(string $href, string $label, bool $active): array {
        return [
            'href' => $href,
            'label' => $label,
            'active' => $active,
        ];
    }

}
