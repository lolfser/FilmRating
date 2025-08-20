<?php
declare(strict_types=1);
namespace App\Services;

use App\Models\Films;

class SaveFilmsLanguagesServices {

    public function save(Films $film, array $inputLanguages): void {

        $shouldUpdated = false;
        $idsToAttach = [];

        foreach ($inputLanguages as $key => $languageId) {
            if (!str_starts_with($key, 'language_')) {
                continue;
            }
            $shouldUpdated = true;
            if ($languageId !== null) {
                $idsToAttach[] = (int) $languageId;
            }
        }

        if ($shouldUpdated) {
            $film->languages()->sync([]);
            $film->languages()->sync($idsToAttach);
        }

    }

}
