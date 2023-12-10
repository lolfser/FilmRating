<?php

namespace App\Http\Controllers;

use App\Models\Films;
use App\Models\Filmsources;
use App\Models\Ratings;
use App\Models\Languages;
use App\Models\Viewers;
use App\Models\Grades;
use App\Services\SaveFilmsLanguagesServices;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ImportController extends Controller {

    public function index() {
        return Inertia::render('Import', [
            '_token' => csrf_token(),
            'filmsources' => Filmsources::all(),
            'PERMISSION_ADD_FILMS' => (new \App\Services\HasPermissionService())->receive(\App\Models\Permissions::PERMISSION_ADD_FILMS),
        ]);
    }

    public function import(Request $request) {

        $data = $request->all();

        $year = $data['year'];
        $filmsource = $data['filmsources_id'];
        $importdata = $data['importdata'];

        $gradeMap = $this->getGradesMap();

        $stream = fopen('data://text/plain,' . $importdata, 'r');
        $header = fgetcsv($stream);

        $titleIndex = array_search($data['title'], $header, true);
        $durationIndex = array_search($data['duration'], $header, true);
        $filmIdIndex = array_search($data['film-id'], $header, true);

        if ($titleIndex === false || $durationIndex === false || $filmIdIndex === false) {
            exit("Film-ID, titel oder dauer spalte nicht gefunden");
        }

        $viewerMap = [];
        $viewers = Viewers::all();

        foreach ($viewers as $viewer) {
            $index = array_search($viewer->initials, $header, true);
            if ($index === false) {
                continue;
            }
            $viewerMap[$viewer->initials] = $viewer;
        }
        $initIndexMap = [];

        foreach ($header as $index => $head) {
            foreach ($viewerMap as $initials => $viewerObject) {
                if ($head === $initials) {
                    $initIndexMap[$initials] = $index;
                }
            }
        }

        $viewerInitIndexMap = array_keys($initIndexMap);

        while (($data = fgetcsv($stream)) !== false) {

            $films = Films::where('film_identifier', $data[$filmIdIndex])
                ->where('year', $year)
                ->where('filmsources_id', $filmsource)
                ->first() ?? new Films();

            $films->name = $data[$titleIndex];

            if ($films->name === '') {
                break;
            }

            $durationParts = explode(':', $data[$durationIndex]);

            if (count($durationParts) === 1)
                $films->duration = $durationParts[0];
            elseif (count($durationParts) === 2)
                $films->duration = $durationParts[0] * 60 + $durationParts[1];
            else
                $films->duration = 0;

            $films->film_identifier = $data[$filmIdIndex];
            $films->filmsources_id = $filmsource;
            $films->year = $year;
            $films->save();

            $this->handlingRatings(
                $viewerMap,
                $films,
                $data,
                $gradeMap,
                $initIndexMap
            );

        }

        return redirect(route("rating.index"));

    }

    private function getGradesMap(): array {
        $map = [];
        foreach (Grades::all() as $grade) {
            $map[$grade->id] = $grade->value . $grade->trend;
        }
        return $map;
    }

    private function handlingRatings(
        array $viewerMap,
        Films $film,
        array $data,
        array $gradeMap,
        array $initIndexMap
    ): void {

        $viewerRatingNotFound = array_keys($viewerMap);

        foreach ($film->ratings as $rating) {

            $viewer = $rating->viewer()->first();
            $grade = $rating->grade()->first();
            $viewerObject = $viewerMap[$viewer->initials] ?? null;

            if ($viewerObject === null) {
                continue;
            }
            unset($viewerRatingNotFound[$viewer->initials]);

            $viewerGrade = $data[$initIndexMap[$viewer->initials]] ?? '';

            if ($viewerGrade === '') {
                continue;
            }
            $viewerGrade = str_replace(' ', '+', $viewerGrade);
            $gradeId = array_search($viewerGrade, $gradeMap, true);
            if ($gradeId === false) {
                continue;
            }

            $rating->grades_id = $gradeId;
            $rating->save();

        }

        foreach ($viewerRatingNotFound as $init) {
            $viewer = Viewers::where('initials', $init)->first() ?? null;
            if ($viewer === null) {
                continue;
            }
            $rating = new Ratings();
            $rating->viewers_id = $viewer->id;
            $rating->films_id = $film->id;
            $rating->comment = '';

            $viewerGrade = $data[$initIndexMap[$viewer->initials]] ?? '';

            if ($viewerGrade === '') {
                continue;
            }
            $viewerGrade = str_replace(' ', '+', $viewerGrade);
            $gradeId = array_search($viewerGrade, $gradeMap, true);
            if ($gradeId === false) {
                continue;
            }

            $rating->grades_id = $gradeId;
            $rating->save();

        }
    }

}
