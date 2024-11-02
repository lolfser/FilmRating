<?php
declare(strict_types=1);
namespace App\Http\Controllers;

use App\Models\Filmmodifications;
use App\Models\Films;
use App\Models\Filmstatus;
use App\Models\Filmsources;
use App\Models\Keywords;
use App\Models\Permissions;
use App\Models\Ratings;
use App\Models\Languages;
use App\Models\Grades;
use App\Models\Genres;
use App\Models\Viewers;
use App\Services\FilmsQueryBuilderService;
use App\Services\HasPermissionService;
use App\Services\SaveFilmModificationService;
use App\Services\SaveFilmsKeywordsServices;
use App\Services\SaveFilmsLanguagesServices;
use App\Services\SaveFilmsGenresServices;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RatingsController extends Controller {

    private const ITEMS_PER_PAGE = 100;

    public function index(Request $request): \Inertia\Response {

        $page = (int) ($request->all()['page'] ?? '1');
        if ($page < 1) {
            $request->merge(['page' => 1]);
            return $this->index($request);
        }

        $requestParam = $request->all();
        $filmStatusIds = array_filter(array_map('intval', explode(',', $requestParam['fl_filmstatus'] ?? '')));
        $keywordIds = array_filter(array_map('intval', explode(',', $requestParam['fl_keywords'] ?? '')));
        $filmModificationsIds = array_filter(array_map('intval', explode(',', $requestParam['fl_filmmodifications'] ?? '')));
        $titleDescription = trim($requestParam['fl_title_description'] ?? '');
        $ratedIds = array_filter(array_map('intval', explode(',', $requestParam['fl_rated'] ?? '')));
        $filmSourceIds = array_filter(array_map('intval', explode(',', $requestParam['fl_filmSource'] ?? '')));

        $viewerId = (new \App\Services\ReceiveCurrentViewerIdService())->receive();

        $filmsQuery = (new FilmsQueryBuilderService())->buildFilmsQuery(
            $filmStatusIds,
            $keywordIds,
            $filmModificationsIds,
            $filmSourceIds,
            $titleDescription,
            false,
            $ratedIds[0] ?? 0,
            $viewerId
        );

        $pageCount = ((int) (count($filmsQuery->get()) / self::ITEMS_PER_PAGE)) + 1;

        $films = $filmsQuery
            ->limit(self::ITEMS_PER_PAGE)
            ->offset(($page - 1) * self::ITEMS_PER_PAGE)
            ->get();

        $hasPermSeeGrades = (new \App\Services\HasPermissionService())->receive(\App\Models\Permissions::PERMISSION_SEE_OTHER_VIEWERS_GRADES);
        $hasPermSetFilmStatus = (new HasPermissionService())->receive(Permissions::PERMISSION_CHANGE_FILMSTATUS);

        foreach ($films as $film) {
            // Loading pivots
            $film->ratings;

            if (!$hasPermSeeGrades) {
                $viewerRating = [];
                foreach ($film->ratings as $rating) {
                    if ($rating->viewers_id === $viewerId) {
                        unset($film->ratings);
                        $film->ratings = [$rating];
                        break;
                    }
                }
            }

            $film->genres;
            $film->languages;
            $film->filmmodifications;
            $film->keywords;

            $film->filmstatus;
            if ($hasPermSetFilmStatus) {
                // $film->filmstatus;
            } else {
                $film->filmstatus_id = 0;
                $film->filmstatus = ['id' => 0, 'name' => 'keine Rechte'];
            }

        }

        $filter = [
            'fl_rated' => $ratedIds,
            'fl_page' => $page,
            'fl_filmstatus' => $filmStatusIds,
            'fl_filmmodifications' => $filmModificationsIds,
            'fl_filmSource' => $filmSourceIds,
            'fl_keywords' => $keywordIds,
            'fl_title_description' => $titleDescription,
        ];

        return Inertia::render('Ratings', [
            'films' => $films,
            'grades' => Grades::all(),
            'viewerId' => $viewerId,
            'languages' => Languages::all()->groupBy('type'),
            'filmstatus' => $hasPermSetFilmStatus
                ? Filmstatus::all()
                : [['id' => 0, 'name' => 'keine Rechte']],
            'genres' => Genres::all(),
            'active_filter' => $filter,
            'filterRateOptions' => [
                [
                    'id' => 0,
                    'name' => 'ohne Einschränkung',
                ],
                [
                    'id' => FilmsQueryBuilderService::RATED_I_NOT_RATED,
                    'name' => 'ich habe noch nicht bewertet',
                ],
                [
                    'id' => FilmsQueryBuilderService::RATED_I_RATED,
                    'name' => 'ich habe bereits bewertet',
                ],
                [
                    'id' => FilmsQueryBuilderService::RATED_NOBODY,
                    'name' => 'von niemanden bewertet',
                ],
            ],
            'filmModifications' => Filmmodifications::all(),
            'keywords' => Keywords::all(),
            'viewers' => Viewers::all(),
            'filmsources' => Filmsources::all(),
            'user' => [
                'statuschange' => (new HasPermissionService())->receive(Permissions::PERMISSION_CHANGE_FILMSTATUS)
            ],
            'totalPages' => $pageCount,
            'currentPage' => $page,
            '_token' => csrf_token(),
            'headerLinks' => (new \App\Services\HeaderLinkService())->receive(),
            'footerLinks' => (new \App\Services\FooterLinkService())->receive(),
        ]);
    }

    public function update(Request $request): \Illuminate\Http\RedirectResponse|bool {

        $film = Films::where('film_identifier', $request->all()['id'])->first();
        $viewersId = (new \App\Services\ReceiveCurrentViewerIdService())->receive();

        if ($film === null) {
            // Todo: Error handling
            return redirect(route("rating.index"));
        }

        $isModified = false;
        $requestData = $request->all();

        if (array_key_exists('description', $requestData)) {
            $film->description = $requestData['description'] ?? '';
            $isModified = true;
        }

        if (
            array_key_exists('filmstatus', $requestData)
            && ($requestData['filmstatus'] ?? 0) > 0
            && (new HasPermissionService())->receive(Permissions::PERMISSION_CHANGE_FILMSTATUS)
        ) {
            // TODO: on default first DB-table item, not 1
            $film->filmstatus_id = (int) ($requestData['filmstatus'] ?? 1);
            $isModified = true;
        }

        if ($isModified) {
            $film->save();
        }

        $isModified = false;
        $ratings = Ratings::where('films_id', $film->id)->where('viewers_id', $viewersId);

        if ($ratings->count() === 0) {
            $rating = new Ratings();
            $rating->viewers_id = $viewersId;
            $rating->films_id = $film->id;
            $rating->grades_id = 0;
        } else {
            $rating = $ratings->first();
        }

        if (array_key_exists('grades_id', $requestData)) {
            // TODO: on default first DB-table item, not 1
            $rating->grades_id = $requestData['grades_id'] ?? 0;
            $isModified = true;
        }

        if (array_key_exists('comment', $requestData)) {
            $rating->comment = $requestData['comment'] ?? '';
            $isModified = true;
        }

        if ($isModified) {
            $rating->save();
        }

        (new SaveFilmsLanguagesServices())->save($film, $requestData);
        (new SaveFilmsGenresServices())->save($film, $requestData);
        (new SaveFilmModificationService())->save($film, $requestData);

        if (array_key_exists('keywords', $requestData)) {
            (new SaveFilmsKeywordsServices())->save($film, explode(',', $requestData['keywords'] ?? ''));
        }

        if ($requestData['isAjax'] ?? false) {
            return true;
        }

        return redirect(route("rating.index"));

    }

    public function rate(string $filmIdentifier): \Inertia\Response|\Illuminate\Http\RedirectResponse {
        /** @var Films|null $film */
        $film = Films::where('film_identifier', $filmIdentifier)->first();
        if ($film === null) {
            return redirect(route("rating.index"));
        }

        // Loading pivots
        $film->filmsource;
        $film->genres;
        $film->languages;
        $film->filmstatus;
        $film->filmmodifications;
        $film->keywords;

        $viewersId = (new \App\Services\ReceiveCurrentViewerIdService())->receive();
        $viewerRating = [];

        foreach ($film->ratings as $key => $rating) {
            if ($rating->viewers_id === $viewersId) {
                $viewerRating = $rating;
                break;
            }
        }

        return Inertia::render('RatingEdit', [
            'film' => $film,
            'rating' => $viewerRating,
            'languages' => Languages::all()->groupBy('type'),
            '_token' => csrf_token(),
            'grades' => Grades::all(),
            'filmstatus' => Filmstatus::all(),
            'genres' => Genres::all(),
            'filmModifications' => Filmmodifications::all(),
            'keywords' => Keywords::all(),
        ]);

    }

    public function load(Request $request): Films|\Illuminate\Http\RedirectResponse {

        /** @var Films|null $film */
        $film = Films::where('id', $request->all()['filmId'])->first();

        if ($film === null) {
            return redirect(route("rating.index"));
        }

        $viewerId = (new \App\Services\ReceiveCurrentViewerIdService())->receive();

        $hasPermSeeGrades = (new \App\Services\HasPermissionService())->receive(\App\Models\Permissions::PERMISSION_SEE_OTHER_VIEWERS_GRADES);
        $hasPermSetFilmStatus = (new HasPermissionService())->receive(Permissions::PERMISSION_CHANGE_FILMSTATUS);

        // Loading pivots

        $film->ratings;

        if (!$hasPermSeeGrades) {
            $viewerRating = [];
            foreach ($film->ratings as $rating) {
                if ($rating->viewers_id === $viewerId) {
                    unset($film->ratings);
                    $film->ratings = [$rating];
                    break;
                }
            }
        }


        $film->genres;
        $film->languages;
        $film->keywords;
        $film->filmmodifications;

        $film->filmstatus;
        if ($hasPermSetFilmStatus) {
            // $film->filmstatus;
        } else {
            $film->filmstatus_id = 0;
            $film->filmstatus = ['id' => 0, 'name' => 'keine Rechte'];
        }

        return $film;

    }
}
