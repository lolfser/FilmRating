<?php

namespace App\Services;

use App\Models\Films;
use App\Models\Languages;

class SaveFilmsLanguagesServices {

    public function save(Films $film, array $inputLanguages): void {

        $languages = Languages::all()->groupBy('type');
        $film->languages()->sync([]);

        foreach ($languages as $type => $language) {
            $id = $inputLanguages['language_' . $type] ?? null;
            if ($id !== null) {
                $film->languages()->attach($id);
            }
        };

    }

}
