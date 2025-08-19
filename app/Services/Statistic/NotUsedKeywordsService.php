<?php

namespace App\Services\Statistic;

use App\Services\Statistic\Model\TableResult;
use Illuminate\Support\Facades\DB;

class NotUsedKeywordsService implements StatisticInterface {

    public function receive(array $options = []): TableResult {

        $stats = DB::select("
            SELECT k.id, k.name
            FROM keywords k
            LEFT JOIN films_keywords fk ON fk.keywords_id = k.id
            WHERE fk.films_id IS NULl
            ORDER BY LOWER(k.name)
        ");

        $statsString = json_encode($stats);

        $stats = $statsString !== false
            ? json_decode($statsString, true)
            : [];

        $stats = is_array($stats) ? $stats : [];

        return new TableResult(
            ['ID', 'Stichwort'],
            $stats
        );

    }

}
