<?php

namespace App\Services\Statistic;

use App\Services\Statistic\Model\TableResult;
use Illuminate\Support\Facades\DB;

class KeywordsService implements StatisticInterface {

    public function receive(array $options = []): TableResult {

        $stats = DB::select("
            SELECT k.name, COUNT(1) AS counter
            FROM keywords k
            JOIN films_keywords fk ON fk.keywords_id = k.id
            GROUP BY k.name
            ORDER BY counter DESC
        ");

        $statsString = json_encode($stats);

        $stats = $statsString !== false
            ? json_decode($statsString, true)
            : [];

        $stats = is_array($stats) ? $stats : [];

        return new TableResult(
            ['Stichwort', 'Anzahl'],
            $stats
        );

    }

}
