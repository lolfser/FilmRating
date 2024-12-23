<?php

namespace App\Services\Statistic;

use App\Services\Statistic\Model\TableResult;
use Illuminate\Support\Facades\DB;

class FilmsForVierwerService implements StatisticInterface {

    public function receive(): TableResult {

        $films = DB::select(
           "SELECT sum(duration) / 60 / 60 as duration, count(1) as filmsCount
            FROM films
        ");

        $filmsJson = json_encode($films);
        $filmsJson = $filmsJson !== false ? $filmsJson : '';

        $films = json_decode($filmsJson, true);
        if (!is_array($films)) {
            throw new \Exception("unexpected result");
        }
        $filmsDuration = $films[0]['duration'] ?? 0;
        $filmsCount = $films[0]['filmsCount'] ?? 0;

        /** @var array<int, int> $allUsedGrades Like [3 => 3] */
        $allUsedGrades = [];

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

        $filmsJson = json_encode($films);
        $filmsJson = $filmsJson !== false ? $filmsJson : '';

        $films = json_decode($filmsJson, true);

        if (!is_array($films)) {
            throw new \Exception("unexpected result");
        }

        $arr = ['Sichter' => []];

        foreach ($films as $key => $dataRow) {
            if (!is_array($dataRow)) {
                // Todo: Error handling
                continue;
            }
            $allUsedGrades[$dataRow['Note']] = $dataRow['Note'];
            $arr[$dataRow['Sichter']][$dataRow['Note']] = [$dataRow['Anzahl Filme'], round((float)$dataRow['Laufzeit in Stunden'], 2)];
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

                if (isset($dataColum[0])) {
                    $c -= $dataColum[0];
                    $d -= $dataColum[1];
                }

            }

            $dataRow[99] = [$c, round($d, 2)];

            $arr[$key] = $dataRow;
        }

        $header = ['Sichter'];
        foreach($allUsedGrades as $grade) {
            $header[] = 'Note "' . $grade . '"';
        }
        $header[] = 'offen';

        $result = [];
        foreach ($arr as $viewerInitials => $dataRow) {
            if ($viewerInitials === 'Sichter') {continue;}
            $result[$viewerInitials][] = $viewerInitials;
            foreach ($dataRow as $dataColum) {
                $result[$viewerInitials][] = $dataColum[1] . ' (' . $dataColum[0] . ')';
            }
        }

        return new TableResult($header, $result);

    }

}
