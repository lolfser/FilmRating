<?php

namespace App\Http\Controllers;

use App\Models\Films;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Laravel\Jetstream\Jetstream;

class FilmsController extends Controller {

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
     * GET|HEAD  /films/create
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return Inertia::render('FilmsCreate', [
            '_token' => csrf_token(),
            'film' => new Films(),
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
        // TODO complete validation

        try {
            $this->validate($request, [
                'name' => 'required',
                'description' => 'required',
                'sources_id' => 'required',
                'film_nr' => 'required',
                'year' => 'required',
                'duration' => '',
                'audio_lang' => '',
                'subtitle_lang' => '',
                'filmstatus_id' => 'required',
                'created' => '',
                'updated' => '',
            ]);
        } catch (\Throwable $t) {
            // und nu?
            var_dump($t->getMessage());
            exit;
        };

        $data = $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
            'sources_id' => 'required',
            'film_nr' => 'required',
            'year' => 'required',
            'duration' => '',
            'audio_lang' => '',
            'subtitle_lang' => '',
            'filmstatus_id' => 'required',
            'created' => '',
            'updated' => '',
        ]);
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
     * GET|HEAD /films/{film}/edit
     * Show the form for editing the specified resource.
     *
     * @param  Films $film
     * @return \Illuminate\Http\Response
     */
    public function edit(Films $film) {
        return Inertia::render('FilmsEdit', [
            "film" => $film,
            '_token' => csrf_token(),
        ]);
    }

    /**
     * PUT|PATCH /films/{film}
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Films $film
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Films $film) {
        $newData = $request->except(['_method', '_token', 'id']);
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
