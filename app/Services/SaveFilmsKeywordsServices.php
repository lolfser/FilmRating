<?php
declare(strict_types=1);
namespace App\Services;

use App\Models\Films;
use App\Models\Keywords;

class SaveFilmsKeywordsServices {

    /**
     * @param string[] $keywordsInput
     */
    public function save(Films $film, array $keywordsInput): void {

        $keywords = Keywords::all();
        $film->keywords()->sync([]);
        $attach = [];

        foreach ($keywordsInput as $item) {
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
