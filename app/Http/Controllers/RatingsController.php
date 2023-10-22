<?php

namespace App\Http\Controllers;

use App\Models\Films;
use App\Models\Ratings;
use App\Models\Languages;
use App\Models\Viewers;
use App\Models\Grades;
use App\Services\SaveFilmsLanguagesServices;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RatingsController extends Controller {

    public function index() {

        $films = Films::all();

        foreach ($films as $film) {
            $film->ratings; // Loading pivots
            $film->languages; // Loading pivots
        }

        $viewerId = 1; // @todo

        return Inertia::render('Ratings', [
            'films' => $films,
            'grades' => Grades::all(),
            'viewerId' => $viewerId,
        ]);
    }

    public function update(Request $request, Ratings $film_viewer) {

        $films = Films::where('film_identifier', $request->all()['id']);
        $viewersId = 1; // todo

        if ($films->count() === 0) {
            return redirect(route("rating.index"));
        }

        $film = $films->first();

        $ratings = Ratings::where('films_id', $film->id)->where('viewers_id', $viewersId);

        if ($ratings->count() === 0) {
            $rating = new Ratings();
            $rating->viewers_id = $viewersId;
            $rating->films_id = $film->id;
            $rating->grades_id = 1;
        } else {
            $rating = $ratings->first();
        }

        $rating->grades_id = $request->all()['grades_id'] ?? 0;
        $rating->comment = $request->all()['comment'] ?? '';
        $rating->save();

        $saveFilmsLanguagesServices = (new SaveFilmsLanguagesServices())->save($film, $request->all());

        return redirect(route("rating.index"));

    }

    public function rate(string $filmIdentifier) {
        $films = Films::where('film_identifier', $filmIdentifier);
        if ($films->count() === 0) {
            return redirect(route("rating.index"));
        }
        $film = $films->first();
        $film->filmsource->name; // loading pivot
        $film->languages; // loading pivot
        $viewersId = 1; // TODO

        foreach ($film->ratings as $key => $rating) { // loading pivot
            if ($rating->viewers_id != $viewersId) {
                unset($film->ratings[$key]);
            }
        }

        return Inertia::render('RatingEdit', [
            "film" => $film,
            'languages' => Languages::all()->groupBy('type'),
            '_token' => csrf_token(),
            'grades' => Grades::all(),
        ]);

    }
}
