<?php
declare(strict_types=1);
namespace App\Http\Controllers;

use App\Models\Films;
use App\Models\Filmsources;
use App\Models\Filmstatus;
use App\Models\Genres;
use App\Models\Languages;
use App\Services\SaveFilmsGenresServices;
use App\Services\SaveFilmsLanguagesServices;
use Illuminate\Http\Request;
use Inertia\Inertia;
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

    public function index(): \Inertia\Response {

        $films = Films::orderBy('film_identifier')->whereNot('film_identifier', '')->get();

        $editFilmsIsAllowed = (new \App\Services\HasPermissionService())->receive(\App\Models\Permissions::PERMISSION_EDIT_FILMS);

        foreach ($films as $film) {
            // Loading pivots
            $film->languages;
            $film->genres;
            if ($editFilmsIsAllowed) {
                $film->userActions = [
                    [
                        'icon' => '/svgs/pen.svg',
                        'title' => 'bearbeiten',
                        'href' => '/films/' . $film->id . '/cu',
                    ]
                ];
            }
        }

        return Inertia::render('FilmsList', [
            'films' => $films,
            'headerLinks' => (new \App\Services\HeaderLinkService())->receive(),
            'footerLinks' => (new \App\Services\FooterLinkService())->receive(),
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
            return $this->createAndUpdate((int) $request->all()['id'], $errors);
        }

        Films::create($data);
        return redirect(route("films.index"));
    }

    public function show(Films $film): \Inertia\Response {
        $film->languages; // Loading pivots
        $film->genres; // Loading pivots
        return Inertia::render('FilmsShow', [
            'film' => $film,
            'headerLinks' => (new \App\Services\HeaderLinkService())->receive(),
        ]);
    }

    public function createAndUpdate(int $filmId = 0, array $errors = [], $films = null) {
        if (!(new \App\Services\HasPermissionService())->receive(\App\Models\Permissions::PERMISSION_ADD_FILMS)) {
            return redirect(route("films.index"));
        }
        $film = $films ?? Films::find($filmId) ?? new Films();
        Languages::all()->groupBy('type');

        $film->languages; // Loading pivots
        $film->genres; // Loading pivots

        return Inertia::render('FilmsCU', [
            'film' => $film,
            'filmsources' => Filmsources::all(),
            'genres' => Genres::all(),
            'languages' => Languages::all()->groupBy('type'),
            'filmstatus' => Filmstatus::all(),
            '_token' => csrf_token(),
            'errors' => $errors,
            'headerLinks' => (new \App\Services\HeaderLinkService())->receive(),
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
            return $this->createAndUpdate((int) $request->all()['id'], $errors, $film);
        }

        $film->fill($newData); // @todo unique check of film identifier
        $film->save();

        (new SaveFilmsGenresServices())->save($film, $request->all());
        (new SaveFilmsLanguagesServices())->save($film, $request->all());

        return redirect(route("films.show", [$film->id]));
    }
}
