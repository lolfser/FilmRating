<?php

namespace App\Http\Controllers;

use App\Models\Films;
use App\Models\Ratings;
use App\Models\Languages;
use App\Models\Viewers;
use App\Models\Grades;
use App\Services\SaveFilmsLanguagesServices;
use App\Models\Filmsources;
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
        echo "<pre>";
        $stream = fopen('data://text/plain,' . $importdata, 'r');
        $header = fgetcsv($stream);

        $titleIndex = array_search($data['title'], $header, true);
        $durationIndex = array_search($data['duration'], $header, true);
        $filmIdIndex = array_search($data['film-id'], $header, true);
        if ($titleIndex === false || $durationIndex === false || $filmIdIndex === false) {
            exit("Film-ID, titel oder dauer spalte nicht gefunden");
        }

        while (($data = fgetcsv($stream)) !== false) {
            var_dump($data[$titleIndex]);
            var_dump($data[$durationIndex]);

            $films = new Films();
            $films->name = $data[$titleIndex];

            $x = explode(':', $data[$durationIndex]);

            if (count($x) === 1)
                $films->duration = $x[0];
            elseif (count($x) === 2)
                $films->duration = $x[0] * 60 + $x[1];
            else
                $films->duration = 0;

            $films->film_identifier = $data[$filmIdIndex];
            $films->filmsources_id = $filmsource;
            $films->year = $year;
            var_dump($films->duration);
            $films->save();

        }
        exit;

        return redirect(route("rating.index"));

    }
}
