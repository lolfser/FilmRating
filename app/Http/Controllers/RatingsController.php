<?php

namespace App\Http\Controllers;

use App\Models\Filmmodifications;
use App\Models\Films;
use App\Models\Keywords;
use App\Models\Ratings;
use App\Models\Languages;
use App\Models\Grades;
use App\Models\Genres;
use App\Services\SaveFilmModificationService;
use App\Services\SaveFilmsKeywordsServices;
use App\Services\SaveFilmsLanguagesServices;
use App\Services\SaveFilmsGenresServices;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RatingsController extends Controller {

    public function index(): \Inertia\Response {

        $films = Films::all();

        foreach ($films as $film) {
            // Loading pivots
            $film->ratings;
            $film->genres;
            $film->languages;
            $film->genres;
            $film->filmmodifications;
            $film->keywords;
        }

        $viewerId = (new \App\Services\ReceiveCurrentViewerIdService())->receive();
        return Inertia::render('Ratings', [
            'films' => $films,
            'grades' => Grades::all(),
            'viewerId' => $viewerId,
            'languages' => Languages::all()->groupBy('type'),
            'genres' => Genres::all(),
            'active_filter' => 'all',
            'filmModifications' => Filmmodifications::all(),
            'keywords' => Keywords::all(),
            '_token' => csrf_token(),
            'headerLinks' => (new \App\Services\HeaderLinkService())->receive(),
            'footerLinks' => (new \App\Services\FooterLinkService())->receive(),
        ]);
    }

    public function filter(Request $request): \Inertia\Response {

        $filter = $request->all()['filter'];

        $viewerId = (new \App\Services\ReceiveCurrentViewerIdService())->receive();

        if ($filter === 'rated') {
            $films = Films::query()
                ->leftJoin('ratings', 'ratings.films_id', '=', 'films.id')
                ->leftJoin('viewers', 'viewers.id', '=', 'ratings.viewers_id')
                ->where('viewers.id', $viewerId)
                ->get('films.*');
        } elseif ($filter === 'open') {
            $films = Films::query()
                ->leftJoin('ratings','ratings.films_id', '=', 'films.id')
                ->leftJoin('viewers','ratings.viewers_id', '=', 'viewers.id')
                ->where('viewers.id', null)
                ->get('films.*');
        } else {
            $films = Films::all();
        }

        foreach ($films as $film) {
            // Loading pivots
            $film->ratings;
            $film->genres;
            $film->languages;
            $film->genres;
            $film->filmmodifications;
            $film->keywords;
        }

        return Inertia::render('Ratings', [
            'films' => $films,
            'grades' => Grades::all(),
            'viewerId' => $viewerId,
            'languages' => Languages::all()->groupBy('type'),
            'genres' => Genres::all(),
            'active_filter' => $filter,
            '_token' => csrf_token(),
            'headerLinks' => (new \App\Services\HeaderLinkService())->receive(),
            'footerLinks' => (new \App\Services\FooterLinkService())->receive(),
        ]);
    }

    public function update(Request $request) {

        $films = Films::where('film_identifier', $request->all()['id']);
        $viewersId = (new \App\Services\ReceiveCurrentViewerIdService())->receive();

        if ($films->count() === 0) {
            return redirect(route("rating.index"));
        }

        $film = $films->first();

        if ($film === null) {
            // Todo: Error handling
            return redirect(route("rating.index"));
        }

        $film->description = $request->all()['description'] ?? '';
        $film->save();

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
        (new SaveFilmModificationService())->save($film, $request->all());
        (new SaveFilmsKeywordsServices())->save($film, explode(',', $request->all()['keywords']));

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

        // Loading pivots
        $film->filmsource;
        $film->genres;
        $film->languages;
        $film->genres;
        $film->filmmodifications;
        $film->keywords;

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
            'filmModifications' => Filmmodifications::all(),
            'keywords' => Keywords::all(),
        ]);

    }

    public function load(Request $request) {

        $films = Films::where('id', $request->all()['filmId']);

        if ($films->count() === 0) {
            return redirect(route("rating.index"));
        }
        $film = $films->first();
        // Loading pivots
        $film->languages;
        $film->genres;
        $film->ratings;
        $film->grades;
        $film->keywords;
        $film->filmmodifications;
        return $film;

    }
}
