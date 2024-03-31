<?php
declare(strict_types=1);
namespace App\Services;

class FooterLinkService {

    /**
     * @return array<mixed>
     */
    public function receive(): array {

        $currentPath = $this->receiveCurrentPath();

        if ($currentPath === null) {
            // Todo: Add error handling
            return [];
        }

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

    private function receiveCurrentPath(): ?string {

        $root = \Illuminate\Support\Facades\Route::getFacadeRoot();

        if (!$root instanceof \Illuminate\Routing\Router) {
            // Todo: Add error handling
            return null;
        }

        return $root->current()?->uri();

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
