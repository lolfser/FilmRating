<?php

namespace App\Services\Stats;

use Illuminate\Support\Facades\DB;

class KeywordsService {

    public function receive(): array {

        $stats = DB::select("
            SELECT k.name, COUNT(1) AS counter
            FROM keywords k
            JOIN films_keywords fk ON fk.keywords_id = k.id
            GROUP BY k.name
            ORDER BY counter DESC
        ");

        $stats = json_decode(json_encode($stats), true);

        return $stats;

    }

}
