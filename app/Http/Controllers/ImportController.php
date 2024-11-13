<?php
declare(strict_types=1);
namespace App\Http\Controllers;

use App\Models\Films;
use App\Models\Filmsources;
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

        /*
            TRUNCATE films_keywords;
            TRUNCATE keywords;
            TRUNCATE films_languages;
            TRUNCATE films_genres;
            TRUNCATE filmmodifications_films;
            TRUNCATE films;
         */

        if (!(new \App\Services\HasPermissionService())->receive(\App\Models\Permissions::PERMISSION_IMPORT)) {
            $errors[] = 'no permission';
            var_dump($errors);
            exit;
        }

        $data = $request->all();

        $year = $data['year'] ?? 49;
        $importdata = $data['importdata'] ?? '';

        if ($importdata === '') {
            $errors[] = 'nix zu importieren';
            var_dump($errors);
            exit;
        }

        $stream = fopen('data://text/plain,' . $importdata, 'r');

        if ($stream === false) {
            return;
        }

        $separator = $data['separator'] ?? ',';
        $enclosure = $data['enclosure'] ?? '"';

        $header = fgetcsv($stream, null, $separator, $enclosure);

        if ($header === false) {
            return;
        }

        $titleIndex = array_search($data['title'], $header, true);
        $durationIndex = array_search($data['duration'], $header, true);
        $filmIdIndex = array_search($data['film-id'], $header, true);

        if ($titleIndex === false
            || $durationIndex === false
            || $filmIdIndex === false
        ) {
            exit('Film-ID, Titel oder Dauer-Spalte nicht gefunden.');
        }

        while (($data = fgetcsv($stream, null, $separator, $enclosure)) !== false) {

            $films = Films::where('film_identifier', $data[$filmIdIndex])->first();

            if ($films !== null) {
                continue;
            }

            $films = new Films();

            $films->name = $data[$titleIndex];

            if ($films->name === '') {
                continue;
            }

            $filmId = (int) $data[$filmIdIndex];

            $films->filmsources_id = $filmId < 1000
                ? 2
                : (
                    $filmId < 3000 ? 1 : 3
                );
            $films->duration = $this->receiveDuration($data[$durationIndex]);

            $films->film_identifier = $data[$filmIdIndex];
            $films->year = $year;
            $films->filmstatus_id = 1;
            $films->save();

        }

        return redirect(route("rating.index"));

    }

    private function receiveDuration(string $durationString): int {

        $durationParts = explode(':', $durationString);

        if (count($durationParts) === 1) {
            return ((int) ($durationParts[0] ?: 0)) * 60;
        }

        if (count($durationParts) === 2) {
            return ((int) $durationParts[0]) * 60 + ((int) $durationParts[1]);
        }

        if (count($durationParts) === 3) {
            return
                ((int) $durationParts[0]) * 60 * 60
                + ((int) $durationParts[1]) * 60
                + ((int) $durationParts[2]);
        }

        return 0;
    }

}
