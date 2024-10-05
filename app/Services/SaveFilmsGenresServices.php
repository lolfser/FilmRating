<?php
declare(strict_types=1);
namespace App\Services;

use App\Models\Films;

class SaveFilmsGenresServices {

    public function save(Films $film, array $userInputs): void {

        if (!array_key_exists('genres', $userInputs)) {
            return;
        }

        $genres = $userInputs['genres'] ?? [];
        is_string($genres)
            ? $film->genres()->sync(explode(',', $genres))
            : $film->genres()->sync($genres);

    }

}
