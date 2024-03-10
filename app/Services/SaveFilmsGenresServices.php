<?php

namespace App\Services;

use App\Models\Films;

class SaveFilmsGenresServices {

    public function save(Films $film, array $userInputs): void {

        $genres = $userInputs['genres'] ?? [];
        $genres !== []
            ? $film->genres()->sync(explode(',', $genres))
            : $film->genres()->sync($genres);

    }

}
