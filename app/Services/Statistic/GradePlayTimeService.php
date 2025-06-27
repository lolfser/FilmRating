<?php

namespace App\Services\Statistic;

use App\Services\Statistic\Model\TableResult;
use Illuminate\Support\Facades\DB;

class GradePlayTimeService implements StatisticInterface {

    public function receive(array $options = []): TableResult {

        $statusIn = [];

        foreach ($options['status'] ?? [] as $status) {
            $statusIn[] = (int) $status;
        }

        $statusInClause = $statusIn === []
            ? ''
            : ' AND films.filmstatus_id IN (' . implode(',', $statusIn) . ')';

        $films = DB::select("
        (SELECT
            '1' AS 'Note',
            COUNT(1) AS 'Anzahl Filme',
            SUM(filmDuration) / 60 / 60 AS 'Dauer in Stunden'
        FROM (
                SELECT
                    DISTINCT films.id AS filmsId,
                    films.duration AS filmDuration
                FROM films
                JOIN ratings ON ratings.films_id = films.id
                JOIN grades ON ratings.grades_id = grades.id
                WHERE grades.value = 1
                    " . $statusInClause . "
                GROUP BY films.id, films.duration
            ) AS innerMain1
        )
        UNION
        (SELECT
            '2 (ohne 1en)' AS 'Note',
            COUNT(1) AS 'Anzahl Filme',
            SUM(filmDuration) / 60 / 60 AS 'Dauer in Stunden'
        FROM (
                SELECT
                    DISTINCT films.id AS filmsId,
                    films.duration AS filmDuration
                FROM films
                JOIN ratings ON ratings.films_id = films.id
                JOIN grades ON ratings.grades_id = grades.id
                WHERE grades.value = 2
                    " . $statusInClause . "
                    AND films.id NOT IN (
                        SELECT films.id
                        FROM films
                        JOIN ratings ON ratings.films_id = films.id
                        JOIN grades ON ratings.grades_id = grades.id
                        WHERE grades.value = 1
                    )
                GROUP BY films.id, films.duration
            ) AS innerMain2
        )
        ");

        $filmsJson = json_encode($films);
        $filmsJson = $filmsJson !== false ? $filmsJson : '';

        $films = json_decode($filmsJson, true);

        if (!is_array($films)) {
            throw new \Exception("unexpected result");
        }

        return new TableResult(
            ['Note', 'Anzahl Filme', 'Dauer in Stunden'],
            $films
        );

    }

}
