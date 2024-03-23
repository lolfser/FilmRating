<?php

namespace App\Services\Stats;

use Illuminate\Support\Facades\DB;

class GenresService {

    public function receive(): array {

        $stats = DB::select("
            SELECT g.name, COUNT(1) AS counter
            FROM genres g
            JOIN films_genres fg ON fg.genres_id = g.id
            GROUP BY g.name
            ORDER BY counter DESC
        ");

        $stats = json_decode(json_encode($stats), true);

        return $stats;

    }

}
