<?php

namespace App\Http\Controllers;

use App\Models\Films;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
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
        // return view("films.list", ['films' => Films::all()]);
        // $termsFile = Jetstream::localizedMarkdownPath('terms.md');

        return Inertia::render('Films', [
            'films' => Films::all()
        ]);
    }

    /**
     * GET|HEAD  /films/create
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view("films.edit");
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
        return view("films.view", ["films" => $film]);
    }

    /**
     * GET|HEAD /films/{film}/edit
     * Show the form for editing the specified resource.
     *
     * @param  Films $film
     * @return \Illuminate\Http\Response
     */
    public function edit(Films $film) {
        return view("films.edit", ["films" => $film]);
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
