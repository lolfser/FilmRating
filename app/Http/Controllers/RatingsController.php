<?php

namespace App\Http\Controllers;

use App\Models\Films;
use App\Models\Ratings;
use App\Models\Languages;
use App\Models\Viewers;
use App\Models\Grades;
use App\Models\Genres;
use App\Services\SaveFilmsLanguagesServices;
use App\Services\SaveFilmsGenresServices;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RatingsController extends Controller {

    public function index() {

        $films = Films::all();

        foreach ($films as $film) {
            $film->ratings; // Loading pivots
            $film->genres; // Loading pivots
            $film->languages; // Loading pivots
            $film->genres; // Loading pivots
        }

        $viewerId = (new \App\Services\ReceiveCurrentViewerIdService())->receive();
        return Inertia::render('Ratings', [
            'films' => $films,
            'grades' => Grades::all(),
            'viewerId' => $viewerId,
            'languages' => Languages::all()->groupBy('type'),
            'genres' => Genres::all(),
            '_token' => csrf_token(),
            'footerLinks' => (new \App\Services\FooterLinkService())->receive(),
        ]);
    }

    public function update(Request $request, Ratings $film_viewer) {

        $films = Films::where('film_identifier', $request->all()['id']);
        $viewersId = (new \App\Services\ReceiveCurrentViewerIdService())->receive();

        if ($films->count() === 0) {
            return redirect(route("rating.index"));
        }

        $film = $films->first();

        $ratings = Ratings::where('films_id', $film->id)->where('viewers_id', $viewersId);

        if ($ratings->count() === 0) {
            $rating = new Ratings();
            $rating->viewers_id = $viewersId;
            $rating->films_id = $film->id;
        } else {
            $rating = $ratings->first();
        }

        $rating->grades_id = $request->all()['grades_id'] ?? 0;
        $rating->comment = $request->all()['comment'] ?? '';
        $rating->save();

        (new SaveFilmsLanguagesServices())->save($film, $request->all());
        (new SaveFilmsGenresServices())->save($film, $request->all());

        if ($request->all()['isAjax'] ?? false) {
            return true;
        }

        return redirect(route("rating.index"));

    }

    public function rate(string $filmIdentifier) {
        $films = Films::where('film_identifier', $filmIdentifier);
        if ($films->count() === 0) {
            return redirect(route("rating.index"));
        }
        $film = $films->first();
        $film->filmsource; // loading pivot
        $film->genres; // loading pivot
        $film->languages; // loading pivot
        $film->genres; // Loading pivots
        $viewersId = (new \App\Services\ReceiveCurrentViewerIdService())->receive();
        $viewerRating = null;

        foreach ($film->ratings as $key => $rating) { // loading pivot
            if ($rating->viewers_id != $viewersId) {
                unset($film->ratings[$key]);
            } else {
                $viewerRating = $rating;
            }
        }

        return Inertia::render('RatingEdit', [
            'film' => $film,
            'rating' => $viewerRating,
            'languages' => Languages::all()->groupBy('type'),
            '_token' => csrf_token(),
            'grades' => Grades::all(),
            'genres' => Genres::all(),
        ]);

    }
}
