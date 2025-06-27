<?php

namespace App\Services\Statistic;

use App\Services\Statistic\Model\TableResult;
use Illuminate\Support\Facades\DB;

class GenresService implements StatisticInterface {

    public function receive(array $options = []): TableResult {

        $stats = DB::select("
            SELECT g.name, COUNT(1) AS counter
            FROM genres g
            JOIN films_genres fg ON fg.genres_id = g.id
            GROUP BY g.name
            ORDER BY counter DESC
        ");

        $statsString = json_encode($stats);

        $stats = $statsString !== false
            ? json_decode($statsString, true)
            : [];

        $stats = is_array($stats) ? $stats : [];

        return new TableResult(
            ['Genre', 'Anzahl'],
            $stats
        );

    }

}
