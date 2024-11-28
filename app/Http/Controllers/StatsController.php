<?php
declare(strict_types=1);
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Inertia\Inertia;

class StatsController extends Controller {

    public function index(): View|\Illuminate\Http\RedirectResponse {

        if (!(new \App\Services\HasPermissionService())->receive(\App\Models\Permissions::PERMISSION_SEE_PAGE_STATICS)) {
            return redirect(route('rating.index'));
        }

        /** @var array<int, int> $allUsedGrades Like [3 => 3] */
        $allUsedGrades = [];
        $globalRating = $this->receiveStatsGlobalRatingCount();

		$globalCount = array_column($globalRating, "c");
		$filmsCount = (array_sum($globalCount)); // todo if empty?

		$globalDuration = array_column($globalRating, "d");
        $filmsDuration = (array_sum($globalDuration)); // todo if empty?

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

        $header = [];
        foreach($allUsedGrades as $grade) {
            array_push($header, ['', 'Note "' . $grade . '"']);
        }
        array_push($header, ['', "offen"]);
        $arr['Sichter'] = $header;

        return view(
            'stats/index',
            [
                'stats' => $arr,
                'statsGlobalRatingCount' => $globalRating,
                'genreStats' => (new \App\Services\Stats\GenresService())->receive(),
                'keywordStats' => (new \App\Services\Stats\KeywordsService())->receive(),
                'noDurationStats' => $this->receiveFilmsWithoutDuration(),
                'headerLinks' => (new \App\Services\HeaderLinkService())->receive(),
                'footerLinks' => (new \App\Services\FooterLinkService())->receive(),
                'footerLinks' => (new \App\Services\FooterLinkService())->receive(),
            ]
        );
    }

    /**
     * @return array<mixed>
     */
    private function receiveStatsGlobalRatingCount(): array {

        $stats = DB::select("
            SELECT '0' AS r, COUNT(1) AS c, (sum(films.duration) / 60 / 60) AS d
            FROM films
            LEFT JOIN ratings ON ratings.films_id = films.id
            WHERE ratings.films_id IS NULL
            UNION
            (
                SELECT
                c_inner AS r,
                COUNT(1) AS c,
                (SUM(Y) / 60 / 60) AS d
                FROM (
                    SELECT COUNT(1) AS c_inner, films.duration AS Y
                    FROM films
                    JOIN ratings ON ratings.films_id = films.id
                    GROUP BY ratings.films_id, Y
                ) AS s
                GROUP BY r
            )
            ORDER BY 'Anzahl Bewertung', r
        ");

        $statsJson = json_encode($stats);
        $statsJson = $statsJson !== false ? $statsJson : '';

        $stats = (array) json_decode($statsJson, true);
        return $stats;

    }

    /**
     * @return array<mixed>
     */
    private function receiveFilmsWithoutDuration(): array {

        $stats = DB::select("
            SELECT *
            FROM films
            WHERE films.duration = 0
        ");

        $statsJson = json_encode($stats);
        $statsJson = $statsJson !== false ? $statsJson : '';

        $stats = (array) json_decode($statsJson, true);
        $header = ['film_identifier' => 'Film-Identifier', 'name' => 'Film'];
        array_unshift($stats, $header);
        return $stats;
    }

}
