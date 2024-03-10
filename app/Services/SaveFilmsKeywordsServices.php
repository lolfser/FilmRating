<?php

namespace App\Services;

use App\Models\Films;
use App\Models\Keywords;

class SaveFilmsKeywordsServices {

    public function save(Films $film, array $keyordsInput): void {

        $keywords = Keywords::all();
        $film->keywords()->sync([]);
        $attach = [];

        foreach ($keyordsInput as $item) {
            $item = trim($item);
            if ($item === '') continue;
            $found = false;
            foreach ($keywords as $keyword) {
                if ($keyword->name === $item) {
                    $found = true;
                    $attach[] = $keyword->id;
                    break;
                }
            }
            if ($found === false) {
                $x = new Keywords();
                $x->name = $item;
                $x->save();
                $attach[] = $x->id;
            }
        }

        $film->keywords()->attach($attach);

    }

}
