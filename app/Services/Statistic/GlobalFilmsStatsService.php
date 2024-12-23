<?php

namespace App\Services\Statistic;

use App\Services\Statistic\Model\TableResult;
use Illuminate\Support\Facades\DB;

class GlobalFilmsStatsService implements StatisticInterface {

    public function receive(): TableResult {

        $stats = DB::select("
            SELECT '0' AS numberRatings, COUNT(1) AS countRatings, (sum(films.duration) / 60 / 60) AS durationInHour
            FROM films
            LEFT JOIN ratings ON ratings.films_id = films.id
            WHERE ratings.films_id IS NULL
            UNION
            (
                SELECT
                c_inner AS numberRatings,
                COUNT(1) AS countRatings,
                (SUM(Y) / 60 / 60) AS durationInHour
                FROM (
                    SELECT COUNT(1) AS c_inner, films.duration AS Y
                    FROM films
                    JOIN ratings ON ratings.films_id = films.id
                    GROUP BY ratings.films_id, Y
                ) AS s
                GROUP BY numberRatings
            )
            ORDER BY 'Anzahl Bewertung', numberRatings
        ");

        $statsString = json_encode($stats);

        $stats = $statsString !== false
            ? json_decode($statsString, true)
            : [];

        $stats = is_array($stats) ? $stats : [];

        return new TableResult(
            ['Anzahl Bewertungen', 'Anzahl Filme', 'Laufzeit in Stunden'],
            $stats
        );

    }

}
