<?php

namespace App\Services;

use App\Models\Filmmodifications;
use App\Models\Films;

class SaveFilmModificationService {

    /**
     * @param string[]|int[] $userInputs
     */
    public function save(Films $film, array $userInputs): void {

        $sync = [];

        foreach (Filmmodifications::all() as $mod) {
            if (($userInputs['filmModification_' . $mod->id] ?? null ) !== null) {
                $sync[] = $mod->id;
            }
        }

        $film->filmmodifications()->sync($sync);
        $film->save();

    }

}
