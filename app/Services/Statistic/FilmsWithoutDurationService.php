<?php

namespace App\Services\Statistic;

use App\Services\Statistic\Model\TableResult;
use Illuminate\Support\Facades\DB;

class FilmsWithoutDurationService implements StatisticInterface {

    public function receive(): TableResult {

        $stats = DB::select("
            SELECT film_identifier, name
            FROM films
            WHERE films.duration = 0
        ");

        $statsString = json_encode($stats);

        $stats = $statsString !== false
            ? json_decode($statsString, true)
            : [];

        $stats = is_array($stats) ? $stats : [];

        return new TableResult(
            ['Film-Identifier', 'Film'],
            $stats
        );

    }

}
