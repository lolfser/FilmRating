<?php

namespace App\Http\Controllers;

use App\Models\Filmmodifications;
use App\Models\Films;
use App\Models\Filmsources;
use App\Models\Ratings;
use App\Models\Languages;
use App\Models\Viewers;
use App\Models\Genres;
use App\Models\Grades;
use App\Services\SaveFilmsLanguagesServices;
use App\Services\SaveFilmsKeywordsServices;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ImportController extends Controller {

    public function index() {
        return Inertia::render('Import', [
            '_token' => csrf_token(),
            'filmsources' => Filmsources::all(),
            'headerLinks' => (new \App\Services\HeaderLinkService())->receive(),
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

        if ($stream === false) {
            return;
        }

        $header = fgetcsv($stream);

        if ($header === false) {
            return;
        }

        $titleIndex = array_search($data['title'], $header, true);
        $durationIndex = array_search($data['duration'], $header, true);
        $filmIdIndex = array_search($data['film-id'], $header, true);
        $genresIndex = array_search($data['info-col'], $header, true);
        $languageIndex = array_search($data['language-col'], $header, true);
        $queerIndex = array_search($data['queer-col'], $header, true);
        $child9Index = array_search($data['child9-col'], $header, true);
        $child13Index = array_search($data['child13-col'], $header, true);
        $child17Index = array_search($data['child17-col'], $header, true);

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

        $saveFilmsLanguagesServices = new SaveFilmsLanguagesServices();
        $saveFilmsKeywordsServices = new SaveFilmsKeywordsServices();
        $allMods = $this->receiveAllModifications();
        $allGenres = Genres::all();

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

            $this->handlingFilmModifications(
                $films,
                $allMods,
                $data[$queerIndex] === 'TRUE',
                $data[$child9Index] === 'TRUE',
                $data[$child13Index] === 'TRUE',
                $data[$child17Index] === 'TRUE'
            );

            $this->handlingInfoSpalte(
                $films,
                $saveFilmsKeywordsServices,
                $allGenres,
                explode(',', $data[$genresIndex])
            );

            $this->handlingLanguages(
                $saveFilmsLanguagesServices,
                $films,
                explode('_', $data[$languageIndex])
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

    /**
     * @return array<mixed>
     */
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
            return $durationParts[0] ?: 0;

        if (count($durationParts) === 2)
            return ((int) $durationParts[0]) * 60 + ((int) $durationParts[1]);

        return 0;
    }

    private function handlingInfoSpalte(
        Films $film,
        SaveFilmsKeywordsServices $saveFilmsKeywordsServices,
        Collection $allGenres,
        array $genresInput
    ): void {

        $usedGenres = [];
        $usedKeywords = [];
        $usedDescriptions = [];
        $allGenresArray = [];

        foreach ($allGenres as $genre) {
            $allGenresArray[$genre->name] = $genre;
        }

        foreach ($genresInput as $key => $input) {

            $input = \trim($input);
            $input = $input === 'Doku' ? 'Dokumentation' : $input;

            if (isset($allGenresArray[$input])) {
                $usedGenres = $allGenresArray[$input];
                continue;
            }

            if (count(explode(' ', $input)) > 2) {
                $usedDescriptions[] = $input;
                continue;
            }

            $usedKeywords[] = $input;

        }

        $film->description = implode(', ', $usedDescriptions);
        $saveFilmsKeywordsServices->save($film, $usedKeywords);

        $film->genres()->sync([]);
        $film->genres()->attach($usedGenres);

        $film->save();
    }

    private function handlingLanguages(
        SaveFilmsLanguagesServices $saveFilmsLanguagesServices,
        Films $film,
        array $languagesInput
    ): void {

        if ($languagesInput === [])
            return;

        $map = [];
        foreach (Languages::all() as $lang) {
            $map[$lang->type][$lang->language] = $lang->id;
        }

        $idAudio    = $map['audio'][strtolower($languagesInput[0])] ?? false;
        $idSubtitle = $map['subtitle'][strtolower($languagesInput[1] ?? false)] ?? false;

        if ($idAudio === false && $idSubtitle === false) {
            return;
        }

        if ($idAudio !== false && $idSubtitle !== false) {
            $saveFilmsLanguagesServices->save($film, [
                'language_audio' => $idAudio,
                'language_subtitle' => $idSubtitle
            ]);
            return;
        }

        if ($idAudio !== false) {
            $saveFilmsLanguagesServices->save($film, [
                'language_audio' => $idAudio,
            ]);
            return;
        }

        $saveFilmsLanguagesServices->save($film, [
            'language_subtitle' => $idSubtitle
        ]);

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

    private function handlingFilmModifications(
        Films $films,
        array $allMods,
        bool  $isQueer,
        bool  $isChild9,
        bool  $isChild13,
        bool  $isChild17
    ): void {
        $films->filmmodifications()->sync([]);
        $mods = [];

        if ($isQueer)
            $mods[] = $allMods['queer'];
        if ($isChild9)
            $mods[] = $allMods['child9'];
        if ($isChild13)
            $mods[] = $allMods['child13'];
        if ($isChild17)
            $mods[] = $allMods['child17'];

        $films->filmmodifications()->attach($mods);
        $films->save();
    }

    /**
     * @return array<mixed>
     */
    public function receiveAllModifications(): array
    {
        $allMods = [];
        foreach (Filmmodifications::all() as $mod) {
            if ($mod->name === 'child9') {
                $allMods['child9'] = $mod->id;
                continue;
            }
            if ($mod->name === 'child13') {
                $allMods['child13'] = $mod->id;
                continue;
            }
            if ($mod->name === 'child17') {
                $allMods['child17'] = $mod->id;
                continue;
            }
            if ($mod->name === 'queer') {
                $allMods['queer'] = $mod->id;
                continue;
            }
        }
        return $allMods;
    }

}
