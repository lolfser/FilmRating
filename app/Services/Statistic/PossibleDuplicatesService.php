<?php

namespace App\Services\Statistic;

use App\Services\Statistic\Model\TableResult;
use Illuminate\Support\Facades\DB;

class PossibleDuplicatesService implements StatisticInterface {

    public function receive(): TableResult {

        $stats = DB::select('
            SELECT films.name, COUNT(1) AS filmCount, GROUP_CONCAT(films.film_identifier SEPARATOR ", ") AS duplicates
            FROM films
            GROUP BY films.name
            HAVING filmCount > 1
            ORDER BY 2 DESC
        ');

        $statsString = json_encode($stats);

        $stats = $statsString !== false
            ? json_decode($statsString, true)
            : [];

        $stats = is_array($stats) ? $stats : [];

        return new TableResult(
            ['Film', 'Anzahl', 'Film-Identifier'],
            $stats
        );

    }

}
