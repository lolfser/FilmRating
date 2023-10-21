<?php

namespace App\Http\Controllers;

use App\Models\Films;
use App\Models\FilmsViewers;
use App\Models\Languages;
use App\Models\Viewers;
use App\Models\Grades;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Laravel\Jetstream\Jetstream;

class FilmsViewersController extends Controller {

    /**
     * GET|HEAD  /rating
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $films = Films::all();

        foreach ($films as $film) {
            foreach ($film->viewers as $viewer) {
                // Loading pivots
            }
            foreach ($film->languages as $viewer) {
                // Loading pivots
            }
        }

        $viewerId = 1; // @todo

        return Inertia::render('FilmsViewers', [
            'films' => $films,
            'grades' => Grades::all(),
            'viewerId' => $viewerId,
        ]);
    }

    /**
     * GET|HEAD  /film_viewer/create
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view("film_viewer.edit");
    }

    /**
     * POST  /film_viewer
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        // TODO complete validation
        $data = $this->validate($request, [
            'films_id' => 'required',
            'viewers_id' => 'required',
            'comment' => 'required',
            'grades_id' => 'required',
        ]);

        FilmViewer::create($data);

        return redirect(route("film_viewer.index"));
    }

    /**
     * GET|HEAD /film_viewer/{film_viewer}
     * Display the specified resource.
     *
     * @param  FilmViewer $film_viewer
     * @return \Illuminate\Http\Response
     */
    public function show(FilmViewer $film_viewer) {
        return view("film_viewer.view", ["film_viewer" => $film_viewer]);
    }

    /**
     * GET|HEAD /film_viewer/{film_viewer}/edit
     * Show the form for editing the specified resource.
     *
     * @param  FilmViewer $film_viewer
     * @return \Illuminate\Http\Response
     */
    public function edit(FilmViewer $film_viewer) {
        return view("film_viewer.edit", ["film_viewer" => $film_viewer]);
    }

    /**
     * PUT|PATCH /film_viewer/{film_viewer}
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  FilmsViewers $film_viewer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FilmsViewers $film_viewer) {
        var_dump("demnächst"); exit;
        var_dump($film_viewer); exit;
        echo "a"; exit;
        $newData = $request->except(['_method', '_token', 'id']);
        $film_viewer->fill($newData);
        $film_viewer->save();
        return redirect(route("film_viewer.show", [$film_viewer->id]));
    }

    /**
     * DELETE /film_viewer/{film_viewer}
     * Remove the specified resource from storage.
     *
     * @param  FilmViewer $film_viewer
     * @throws \Exception
     * @return \Illuminate\Http\Response
     */
    public function destroy() {
        $film_viewer->delete();
        return redirect(route("film_viewer.index"));
    }

    /**
     * GET /rating/{filmId}/cu/
     * Remove the specified resource from storage.
     *
     * @param  Films $films
     * @throws \Exception
     * @return \Illuminate\Http\Response
     */
    public function rate(string $filmIdentifier) {
        $films = Films::where('film_identifier', $filmIdentifier);
        if ($films->count() === 0) {
            return redirect(route("rating.index"));
        }
        $film = $films->first();
        $film->filmsource->name; // loading pivot
        $film->languages; // loading pivot
        $viewersId = 1; // TODO
        $review = FilmsViewers::where('films_id', $film->first()->id)->where('viewers_id', $viewersId);
        if ($review->count() === 0) {
            $review = new FilmsReview();
        }

        return Inertia::render('RatingEdit', [
            "film" => $film,
            "review" => $review,
            'languages' => Languages::all()->groupBy('type'),
            '_token' => csrf_token(),
        ]);

    }
}
