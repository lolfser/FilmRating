<?php

namespace App\Http\Controllers;

use App\Models\Films;
use App\Models\Filmsources;
use App\Models\Ratings;
use App\Models\Languages;
use App\Models\Viewers;
use App\Models\Genres;
use App\Models\Grades;
use App\Services\SaveFilmsLanguagesServices;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ImportController extends Controller {

    public function index() {
        return Inertia::render('Import', [
            '_token' => csrf_token(),
            'filmsources' => Filmsources::all(),
            'footerLinks' => (new \App\Services\FooterLinkService())->receive(),
        ]);
    }

    public function import(Request $request) {

        if (!(new \App\Services\HasPermissionService())->receive(\App\Models\Permissions::PERMISSION_IMPORT)) {
            $errors[] = 'no permission';
            var_dump($errors);
            exit;
        }

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
        $genresIndex = array_search($data['info-col'], $header, true);

        if ($titleIndex === false
            || $durationIndex === false
            || $filmIdIndex === false
            || $genresIndex === false
        ) {
            exit('Film-ID, Titel, Genre-Index oder Dauer-Spalte nicht gefunden.');
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

            $films->duration = $this->receiveDuration($data[$durationIndex]);

            $films->film_identifier = $data[$filmIdIndex];
            $films->filmsources_id = $filmsource;
            $films->year = $year;
            $films->save();

            $this->handlingGenres(
                $films,
                explode(',', $data[$genresIndex])
            );

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

    private function receiveDuration(string $durationString): int {
        $durationParts = explode(':', $durationString);

        if (count($durationParts) === 1)
            return $durationParts[0];

        if (count($durationParts) === 2)
            return $durationParts[0] * 60 + $durationParts[1];

        return 0;
    }

    private function handlingGenres(
        Films $film,
        array $genresInput
    ): void {
        $allGenres = Genres::all();
        foreach ($genresInput as $key => $input)
            $genresInput[$key] = \trim($input);

        $film->genres()->sync([]);
        $ids = [];

        if ($film->film_identifier === "13") {

        }

        foreach ($allGenres as $genre) {
            if (\in_array($genre->name, $genresInput, true)) {
                $ids[$genre->id] = $genre->id;
            }
        }

        $film->genres()->attach($ids);
        $film->save();
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

            unset($viewerRatingNotFound[array_search($viewer->initials, $viewerRatingNotFound, true)]);

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
