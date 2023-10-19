<?php

namespace App\Http\Controllers;

use App\Models\Films;
use App\Models\Filmsources;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Laravel\Jetstream\Jetstream;
use Illuminate\Support\Facades\Validator;

class FilmsController extends Controller {

    protected const VALIDATION_DEFINITION = [
        'name' => 'required',
        'description' => 'required',
        'sources_id' => 'required|integer',
        'film_nr' => 'required|integer',
        'year' => 'required|integer',
        'duration' => '',
        'audio_lang' => '',
        'subtitle_lang' => '',
        'filmstatus_id' => 'required|integer',
        'created' => '',
        'updated' => '',
    ];

    /**
     * GET|HEAD  /films
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        // $termsFile = Jetstream::localizedMarkdownPath('terms.md');

        return Inertia::render('FilmsList', [
            'films' => Films::all(),
        ]);
    }

    /**
     * POST  /films
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        $validator = Validator::make($request->all(), self::VALIDATION_DEFINITION);
        $errors = $validator->messages()->getMessages();
        $data = $validator->getData();

        if ($errors !== []) {
            return $this->createAndUpdate($request->all(), $validator->messages()->getMessages());
        }

        Films::create($data);

        return redirect(route("films.index"));
    }

    /**
     * GET|HEAD /films/{film}
     * Display the specified resource.
     *
     * @param  Films $film
     * @return \Illuminate\Http\Response
     */
    public function show(Films $film) {
        return Inertia::render('FilmsShow', [
            "film" => $film
        ]);
    }

    /**
     * GET|HEAD /films/{film}/cu
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createAndUpdate(int $filmId = 0, array $errors = [], $films = null) {
        $film = $films ?? Films::find($filmId) ?? new Films();

        // try {
        // $film = Films::find(1);
        // foreach ($film->viewers as $viewer)
        //     var_dump($viewer->pivot->comment);
        //     $film->viewers()->attach(1, ['comment' => 'wuhuuu', 'grades_id' => 1]);
        // exit;

        // } catch (\Throwable $t) {
        //    var_dump($t->getMessage());
        // }
        // exit;

        return Inertia::render('FilmsCU', [
            "film" => $film,
            'filmsources' => Filmsources::all(),
            '_token' => csrf_token(),
            'errors' => $errors,
        ]);
    }

    /**
     * PUT|PATCH /films/{film}
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request) {

        $newData = $request->except(['_method', '_token', 'id']);

        $film = Films::find($request->all()['id']) ?? new Films();

        $validator = Validator::make($newData, self::VALIDATION_DEFINITION);
        $errors = $validator->messages()->getMessages();

        if ($errors !== []) {
            $film->fill($newData);
            return $this->createAndUpdate($request->all()['id'], $validator->messages()->getMessages(), $film);
        }

        $film->fill($newData);
        $film->save();
        return redirect(route("films.show", [$film->id]));
    }

    /**
     * DELETE /films/{film}
     * Remove the specified resource from storage.
     *
     * @param  Films $film
     * @throws \Exception
     * @return \Illuminate\Http\Response
     */
    public function destroy() {
        $film->delete();
        return redirect(route("films.index"));
    }
}
