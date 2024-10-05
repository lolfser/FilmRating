<?php
declare(strict_types=1);
namespace App\Services;

use App\Models\Films;
use App\Models\Languages;

class SaveFilmsLanguagesServices {

    public function save(Films $film, array $inputLanguages): void {

        $languages = Languages::all()->groupBy('type');

        $shouldUpdated = false;
        $idsToAttach = [];

        foreach ($languages as $type => $language) {
            if (!isset($inputLanguages['language_' . $type])) {
                continue;
            }
            $shouldUpdated = true;
            $id = $inputLanguages['language_' . $type] ?? null;
            if ($id !== null) {
                $idsToAttach[] = (int) $id;
            }
        }

        if ($shouldUpdated) {
            $film->languages()->sync([]);
            $film->languages()->sync($idsToAttach);
        }

    }

}
