<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class StatsController extends Controller {

    public function index() {

        $allUsedGrades = [];
        $globalRating = $this->receiveStatsGlobalRatingCount();

        $filmsCount = (array_sum(array_column($globalRating, "c"))); // todo if empty?
        $filmsDuration = (array_sum(array_column($globalRating, "d"))); // todo if empty?

        $films = DB::select(
           "SELECT
                sum(duration) / 60 / 60 AS \"Laufzeit in Stunden\",
                grades.value AS \"Note\" ,
                viewers.initials AS \"Sichter\",
                COUNT(1) AS \"Anzahl Filme\"
            FROM films
            JOIN ratings ON ratings.films_id = films.id
            JOIN viewers ON viewers.id = ratings.viewers_id
            JOIN grades ON ratings.grades_id = grades.id
            GROUP BY viewers.id, grades.value, viewers.initials
            ORDER BY viewers.initials, grades.value
        ");

        $films = json_decode(json_encode($films), true);

        $arr = ['Sichter' => []];

        foreach ($films as $key => $dataRow) {
            $allUsedGrades[$dataRow['Note']] = $dataRow['Note'];
            $arr[$dataRow['Sichter']][$dataRow['Note']] = [$dataRow['Anzahl Filme'], round($dataRow['Laufzeit in Stunden'], 2)];
        }

        ksort($allUsedGrades);

        foreach ($arr as $key => $dataRow) {
            foreach($allUsedGrades as $grade) {
                if (!isset($dataRow[$grade])) {
                    $dataRow[$grade] = [0, 0];
                }
            }
            ksort($dataRow);
            $c = $filmsCount;
            $d = $filmsDuration;
            foreach ($dataRow as $dataColum) {
                // var_dump($dataColum);
                if (isset($dataColum[0])) {
                    $c -= $dataColum[0];
                    $d -= $dataColum[1];
                }

            }

            $dataRow[99] = [$c, round($d, 2)];

            $arr[$key] = $dataRow;
        }

        $header = [];
        foreach($allUsedGrades as $grade) {
            array_push($header, ['', 'Note "' . $grade . '"']);
        }
        array_push($header, ['', "offen"]);
        $arr['Sichter'] = $header;

        return Inertia::render('Stats', [
            'stats' => $arr,
            'statsGlobalRatingCount' => $globalRating,
            'headerLinks' => (new \App\Services\HeaderLinkService())->receive(),
            'footerLinks' => (new \App\Services\FooterLinkService())->receive(),
        ]);
    }

    private function receiveStatsGlobalRatingCount(): array {

        $stats = DB::select("
            SELECT '0' AS r, COUNT(1) AS c, (sum(films.duration) / 60 / 60) AS d
            FROM films
            LEFT JOIN ratings ON ratings.films_id = films.id
            WHERE ratings.films_id IS NULL
            UNION
            (
                SELECT
                c_inner AS r,
                COUNT(1) AS c,
                (SUM(Y) / 60 / 60) AS d
                FROM (
                    SELECT COUNT(1) AS c_inner, any_value(films.duration) AS Y
                    FROM films
                    JOIN ratings ON ratings.films_id = films.id
                    GROUP BY ratings.films_id
                ) AS s
                GROUP BY r
            )
            ORDER BY 'Anzahl Bewertung', r
        ");

        $stats = json_decode(json_encode($stats), true);
        $header = ['r' => 'Anzahl Bewertung', 'c' => 'Anzahl Film', 'd' => 'Laufzeit in Stunden' ];
        array_unshift($stats, $header);
        return $stats;

    }

    private function receiveFilmsWithoutDuration() {

    }

}
