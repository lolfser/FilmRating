<?php

namespace App\Services\Statistic;

use App\Services\Statistic\Model\TableResult;
use Illuminate\Support\Facades\DB;

class GenresService implements StatisticInterface {

    public function receive(array $options = []): TableResult {

        // @TODO: WHERE as options

        $stats = DB::select("
            SELECT g.name as genreName, fs.name as statusName, COUNT(1) AS counter, (SUM(f.duration) / 60 / 60) as Dauer
            FROM genres g
            JOIN films_genres fg ON fg.genres_id = g.id
            JOIN films f ON f.id = fg.films_id
            JOIN filmstatus fs ON fs.id = f.filmstatus_id
            WHERE fs.name = 'open' OR fs.name = 'vielleicht' OR fs.name = 'dabei'
            GROUP BY g.name, fs.name
            ORDER BY g.name ASC
        ");

        $statsString = json_encode($stats);

        $stats = $statsString !== false
            ? json_decode($statsString, true)
            : [];

        $stats = is_array($stats) ? $stats : [];

        return new TableResult(
            ['Genre', 'Status', 'Anzahl', 'Dauer in Std.'],
            $stats
        );

    }

}
