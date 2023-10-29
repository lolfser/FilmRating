<?php

namespace App\Services;

use App\Models\Films;
use App\Models\Genres;

class SaveFilmsGenresServices {

    public function save(Films $film, array $inputLanguages): void {

        $genres = $inputLanguages['genres'] ?? [];
        $genres !== []
            ? $film->genres()->sync(explode(',', $genres))
            : $film->genres()->sync($genres);


    }

}
