<?php

namespace App\Http\Controllers;

use App\Models\Films;
use App\Models\Filmsources;
use App\Models\Languages;
use App\Services\SaveFilmsLanguagesServices;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Laravel\Jetstream\Jetstream;
use Illuminate\Support\Facades\Validator;

class FilmsController extends Controller {

    protected const VALIDATION_DEFINITION = [
        'name' => 'required',
        'film_identifier' => 'required',
        'description' => '',
        'filmsources_id' => 'required|integer',
        'year' => 'required|integer',
        'duration' => '',
        'filmstatus_id' => 'required|integer',
        'created' => '',
        'updated' => '',
    ];

    public function index() {

        $films = Films::all();
        foreach ($films as $film) {
            $film->languages; // Loading pivots
        }

        return Inertia::render('FilmsList', [
            'films' => $films,
            'PERMISSION_ADD_FILMS' => (new \App\Services\HasPermissionService())->receive(\App\Models\Permissions::PERMISSION_ADD_FILMS),
        ]);
    }

    public function store(Request $request) {

        $validator = Validator::make($request->all(), self::VALIDATION_DEFINITION);
        $errors = $validator->messages()->getMessages();

        if (!(new \App\Services\HasPermissionService())->receive(\App\Models\Permissions::PERMISSION_ADD_FILMS)) {
            $errors[] = 'no permission';
        }

        $data = $validator->getData();

        if ($errors !== []) {
            return $this->createAndUpdate($request->all(), $errors);
        }

        Films::create($data);
        return redirect(route("films.index"));
    }

    public function show(Films $film) {
        $film->languages; // Loading pivots
        return Inertia::render('FilmsShow', [
            'film' => $film
        ]);
    }

    public function createAndUpdate(int $filmId = 0, array $errors = [], $films = null) {
        if (!(new \App\Services\HasPermissionService())->receive(\App\Models\Permissions::PERMISSION_ADD_FILMS)) {
            return redirect(route("films.index"));
        }
        $film = $films ?? Films::find($filmId) ?? new Films();
        Languages::all()->groupBy('type');

        foreach ($film->languages as $language) {
            // Loading pivots
        }

        return Inertia::render('FilmsCU', [
            'film' => $film,
            'filmsources' => Filmsources::all(),
            'languages' => Languages::all()->groupBy('type'),
            '_token' => csrf_token(),
            'errors' => $errors,
        ]);
    }

    public function update(Request $request) {

        $newData = $request->except(['_method', '_token', 'id']);

        $film = Films::find($request->all()['id']) ?? new Films();

        $validator = Validator::make($newData, self::VALIDATION_DEFINITION);
        $errors = $validator->messages()->getMessages();

        if (!(new \App\Services\HasPermissionService())->receive(\App\Models\Permissions::PERMISSION_ADD_FILMS)) {
            $errors[] = 'no permission';
        }

        if ($errors !== []) {
            $film->fill($newData);
            return $this->createAndUpdate($request->all()['id'], $errors, $film);
        }

        $film->fill($newData); // @todo unique check of film identifier
        $film->save();

        $saveFilmsLanguagesServices = (new SaveFilmsLanguagesServices())->save($film, $request->all());

        return redirect(route("films.show", [$film->id]));
    }
}
