<?php

namespace App\Services\Statistic;

use App\Services\Statistic\Model\TableResult;
use Illuminate\Support\Facades\DB;

class FilmCountDurationGroupService implements StatisticInterface {

    public function receive(array $options = []): TableResult {

        $stats = DB::select('
            SELECT "0 - 5 min" AS duration_label, COUNT(1) AS count, SUM(duration) / 60 / 60 AS duration_h
            FROM films
            WHERE duration > 0 AND duration <= 300
            UNION
            SELECT "5 - 10 min" AS duration_label, COUNT(1) AS count, SUM(duration) / 60 / 60 AS duration_h
            FROM films
            WHERE duration > 300 AND duration <= 600
            UNION
            SELECT "10 - 15 min" AS duration_label, COUNT(1) AS count, SUM(duration) / 60 / 60 AS duration_h
            FROM films
            WHERE duration > 600 AND duration <= 900
            UNION
            SELECT "15 - 20 min" AS duration_label, COUNT(1) AS count, SUM(duration) / 60 / 60 AS duration_h
            FROM films
            WHERE duration > 900 AND duration <= 1200
            UNION
            SELECT "20 - 25 min" AS duration_label, COUNT(1) AS count, SUM(duration) / 60 / 60 AS duration_h
            FROM films
            WHERE duration > 1200 AND duration <= 1500
            UNION
            SELECT "25 - 30 min" AS duration_label, COUNT(1) AS count, SUM(duration) / 60 / 60 AS duration_h
            FROM films
            WHERE duration > 1500 AND duration <= 1800
            UNION
            SELECT "30 - 45 min" AS duration_label, COUNT(1) AS count, SUM(duration) / 60 / 60 AS duration_h
            FROM films
            WHERE duration > 1800 AND duration <= 2700
            UNION
            SELECT "45 - 60 min" AS duration_label, COUNT(1) AS count, SUM(duration) / 60 / 60 AS duration_h
            FROM films
            WHERE duration > 2700 AND duration <= 3600
            UNION
            SELECT "60 - ... min" AS duration_label, COUNT(1) AS count, SUM(duration) / 60 / 60 AS duration_h
            FROM films
            WHERE duration > 3600
            UNION
            SELECT "55 - ... min" AS duration_label, COUNT(1) AS count, SUM(duration) / 60 / 60 AS duration_h
            FROM films
            WHERE duration > 3300
        ');

        $statsString = json_encode($stats);

        $stats = $statsString !== false
            ? json_decode($statsString, true)
            : [];

        $stats = is_array($stats) ? $stats : [];

        return new TableResult(
            ['Dauer-Gruppe', 'Anzahl der Filme', 'Dauer in Stunden gesamt'],
            $stats
        );

    }

}
