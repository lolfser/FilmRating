<?php

namespace App\Http\Controllers;

use App\Models\Films;
use App\Models\Ratings;
use App\Models\Languages;
use App\Models\Viewers;
use App\Models\Grades;
use App\Models\Genres;
use App\Services\SaveFilmsLanguagesServices;
use App\Services\SaveFilmsGenresServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class StatsController extends Controller {

    public function index() {

        $viewerId = (new \App\Services\ReceiveCurrentViewerIdService())->receive();
        $allUsedGrades = [];

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

        $films = json_decode(json_encode($films), true);;

        $arr = ['Sichter' => []];

        foreach ($films as $key => $dataRow) {
            $allUsedGrades[$dataRow['Note']] = $dataRow['Note'];
            $arr[$dataRow['Sichter']][$dataRow['Note']] = [round($dataRow['Anzahl Filme'],2), round($dataRow['Laufzeit in Stunden'], 2)];
        }

        ksort($allUsedGrades);

        foreach ($arr as $key => $dataRow) {
            foreach($allUsedGrades as $grade) {
                if (!isset($dataRow[$grade])) {
                    $dataRow[$grade] = [0, 0];
                }
            }
            ksort($dataRow);
            $arr[$key] = $dataRow;
        }

        $header = [];
        foreach($allUsedGrades as $grade) {
            array_push($header, ['', 'Note "' . $grade . '"']);
        }

        $arr['Sichter'] = $header;

        return Inertia::render('Stats', [
            'stats' => $arr,
            'footerLinks' => (new \App\Services\FooterLinkService())->receive(),
        ]);
    }
}
