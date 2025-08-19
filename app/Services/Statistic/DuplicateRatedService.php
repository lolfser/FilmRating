<?php

namespace App\Services\Statistic;

use App\Services\Statistic\Model\TableResult;
use Illuminate\Support\Facades\DB;

class DuplicateRatedService implements StatisticInterface {

    public function receive(array $options = []): TableResult {

        $stats = DB::select("
            SELECT COUNT(1) AS c, ratings.films_id, films.film_identifier, viewers.initials
            FROM ratings
            JOIN viewers ON ratings.viewers_id = viewers.id
            JOIN films ON films.id = ratings.films_id
            GROUP BY 2, 3, 4
            HAVING c > 1
            ORDER BY 1 DESC
        ");

        $filmsJson = json_encode($stats);
        $filmsJson = $filmsJson !== false ? $filmsJson : '';

        $stats = json_decode($filmsJson, true);

        if (!is_array($stats)) {
            throw new \Exception("unexpected result");
        }

        return new TableResult(
            ['Anzahl', 'Id', 'Film-Identifier', 'Sichter'],
            $stats
        );

    }

}
