<?php

namespace App\Services\Statistic;

use App\Services\Statistic\Model\TableResult;
use Illuminate\Support\Facades\DB;

class StatusService implements StatisticInterface {

    public function receive(array $options = []): TableResult {

        $stats = DB::select("
            SELECT filmstatus.name, COUNT(1), SUM(duration) / 60 / 60
            FROM films
            JOIN filmstatus ON filmstatus.id = films.filmstatus_id
            GROUP BY filmstatus.name
        ");

        $statsString = json_encode($stats);

        $stats = $statsString !== false
            ? json_decode($statsString, true)
            : [];

        $stats = is_array($stats) ? $stats : [];

        return new TableResult(
            ['Status', 'Anzahl Filme', 'Laufzeit in Std'],
            $stats
        );

    }

}
