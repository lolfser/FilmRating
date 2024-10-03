<?php
declare(strict_types=1);
namespace App\Services;

use App\Models\Filmmodifications;
use App\Models\Films;

class SaveFilmModificationService {

    /**
     * @param string[]|int[] $userInputs
     */
    public function save(Films $film, array $userInputs): void {

        $sync = [];
        $shouldUpdated = false;

        foreach (Filmmodifications::all() as $mod) {
            if (!isset($userInputs['filmModification_' . $mod->id])) {
                continue;
            }
            $shouldUpdated = true;
            if ($userInputs['filmModification_' . $mod->id] === 'true') {
                $sync[] = $mod->id;
            }
        }

        if ($shouldUpdated) {
            $film->filmmodifications()->sync($sync);
            $film->save();
        }

    }

}
