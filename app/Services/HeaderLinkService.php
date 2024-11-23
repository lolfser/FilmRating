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

        $hasPermissionService = (new \App\Services\HasPermissionService());

        if ($hasPermissionService->receive(\App\Models\Permissions::PERMISSION_SEE_PAGE_RATING)) {
            $links[] = $this->build('/rating/list', 'Filmbewertungen', $currentPath === 'rating');
        }

        if ($hasPermissionService->receive(\App\Models\Permissions::PERMISSION_ADD_FILMS)) {
            $wording = (\Illuminate\Support\Facades\Route::getFacadeRoot()->current()->id > 0)
                ? 'Film bearbeiten'
                : 'Film erstellen';
            $links[] = $this->build('/films/0/cu', $wording, $currentPath === 'films/{id}/cu');
        }

        if ($hasPermissionService->receive(\App\Models\Permissions::PERMISSION_IMPORT)) {
            $links[] = $this->build('/import', 'IMPORT', $currentPath === 'import');
        }

        if ($hasPermissionService->receive(\App\Models\Permissions::PERMISSION_SEE_PAGE_STATICS)) {
            $links[] = $this->build('/stats', 'Statistiken', $currentPath === 'stats');
        }

        if ($hasPermissionService->receive(\App\Models\Permissions::PERMISSION_SEE_PAGE_PROGRAM)) {
            $links[] = $this->build('/program', 'Programm', $currentPath === 'program');
        }

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
