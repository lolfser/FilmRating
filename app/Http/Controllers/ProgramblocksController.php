<?php

namespace App\Http\Controllers;

use App\Models\Filmmodifications;
use App\Models\Films;
use App\Models\Filmstatus;
use App\Models\Keywords;
use App\Models\Programblockmetas;
use App\Models\Programblocks;
use App\Services\FilmsQueryBuilderService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProgramblocksController extends Controller {

    public function index(Request $request): \Inertia\Response|\Illuminate\Http\RedirectResponse {

        if (!(new \App\Services\HasPermissionService())->receive(\App\Models\Permissions::PERMISSION_SEE_PAGE_PROGRAM)) {
            return redirect(route('rating.index'));
        }

        $films = $this->buildFilmsQuery($request->all());
        $allFilms = $films/*->limit(10)*/->get();
        $this->loadPivots($allFilms);

        $metas = Programblockmetas::all();

        foreach ($metas as $meta) {
            $meta->location;
            if ($meta->day !== null) {
                $dateString = (new \DateTime($meta->day->date))->format('l, d.m.Y');
                $dateString .= ', ';
                $dateString .= $meta->start;
                $dateString .= ' - ';
                $dateTime = new \DateTime($meta->start ?? '');
                $dateTime->add(new \DateInterval('PT' . ($meta->total_length ?? 0) . 'M'));
                $dateString .= $dateTime->format('H:i');
                $meta->day->setDateStringAttribute($dateString);
            }
            foreach (Programblocks::where('programblockmetas_id', $meta->id)->get() as $block) {
                /** @var Programblocks $block */
                if ($block->film === null) continue;
                // Loading pivots
                $block->film->languages;
                $block->film->genres;
                $block->film->filmstatus;
                $block->film->keywords;
                $block->film->filmmodifications;
                $meta->addBlock($block->film);
            }
        }

        return Inertia::render('Program', [
            'films' => $allFilms,
            'programmetas' => $metas,
            'filmstatus' => Filmstatus::all(),
            'keywords' => Keywords::all(),
            'filmmodifications' => Filmmodifications::all(),
            'headerLinks' => (new \App\Services\HeaderLinkService())->receive(),
            'footerLinks' => (new \App\Services\FooterLinkService())->receive(),
            '_token' => csrf_token(),
            'filter' => [
                'filmstatus' =>
                    ($request->all()['filmstatus'] ?? '') === ''
                        ? ''
                        : array_map(function ($i) {return (int) $i;},explode(',', $request->all()['filmstatus'] ?? '')),
                'filmmodifications' =>
                    ($request->all()['filmmodifications'] ?? '') === ''
                        ? ''
                        : array_map(function ($i) {return (int) $i;},explode(',', $request->all()['filmmodifications'] ?? '')),
                'keywords' =>
                    ($request->all()['keywords'] ?? '') === ''
                        ? ''
                        : array_map(function ($i) {return (int) $i;},explode(',', $request->all()['keywords'] ?? '')),
                'title_description' => $request->all()['title_description'] ?? '',
                'only_not_set' => ($request->all()['only_not_set'] ?? false) === 'true'
            ]
        ]);
    }

    /**
     * @return array<int, mixed>|\Illuminate\Http\RedirectResponse
     */
    public function save(Request $request): array|\Illuminate\Http\RedirectResponse {

        if (!(new \App\Services\HasPermissionService())->receive(\App\Models\Permissions::PERMISSION_SEE_PAGE_PROGRAM)) {
            return redirect(route('rating.index'));
        }

        $meta = Programblockmetas::where('id', $request->all()['programmblockId'])->first();

        if ($meta === null) {
            return [];
        }

        Programblocks::where('programblockmetas_id', $meta->id)->delete();

        foreach (explode(',', $request->all()['films']) as $filmIdentifier) {
            $film = Films::where('film_identifier', $filmIdentifier)->first();
            if ($film === null) {
                continue;
            }
            $pb = new Programblocks();
            $pb->programblockmetas_id = $meta->id;
            $pb->films_id = $film->id;
            $pb->save();
        }

        return $this->load($request);
    }

    /**
     * @return array<int, mixed>|\Illuminate\Http\RedirectResponse
     */
    public function load(Request $request): array|\Illuminate\Http\RedirectResponse {

        if (!(new \App\Services\HasPermissionService())->receive(\App\Models\Permissions::PERMISSION_SEE_PAGE_PROGRAM)) {
            return redirect(route('rating.index'));
        }

        $meta = Programblockmetas::where('id', $request->all()['programmblockId'])->first();
        if ($meta === null) {
            return [];
        }
        $result = Programblocks::where('programblockmetas_id', $meta->id)->get();
        $res = [];
        foreach ($result as $item) {
            $film = $item->film()->first();
            if ($film === null) {
                continue;
            }
            $res[] = [
                'name' => $film->name,
                'film_identifier' => $film->film_identifier,
                'description' => $film->description,
                'genres' => $film->genres,
                'keywords' => $film->keywords,
                'languages' => $film->languages,
                'duration' => $film->duration,
                'filmstatus' => $film->filmstatus,
                'filmmodifications' => $film->filmmodifications,
            ];
        }
        return $res;
    }

    /**
     * @return Collection<int, Films>|\Illuminate\Http\RedirectResponse
     */
    public function filter(Request $request): Collection|\Illuminate\Http\RedirectResponse {

        if (!(new \App\Services\HasPermissionService())->receive(\App\Models\Permissions::PERMISSION_SEE_PAGE_PROGRAM)) {
            return redirect(route('rating.index'));
        }

        $films = $this->buildFilmsQuery($request->all());
        $films = $films/*->limit(10)*/->get();
        $this->loadPivots($films);

        return $films;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Collection<int, Films> $allFilms
     * @return void
     */
    private function loadPivots(\Illuminate\Database\Eloquent\Collection $allFilms): void {

        foreach ($allFilms as $film) {
            // Loading pivots
            $film->languages;
            $film->genres;
            $film->filmstatus;
            $film->keywords;
            $film->filmmodifications;
        }
    }

    /**
     * @param array<string, string> $requestParam
     * @return \Illuminate\Database\Eloquent\Builder<Films>|\Illuminate\Http\RedirectResponse
     */
    private function buildFilmsQuery(array $requestParam): \Illuminate\Database\Eloquent\Builder|\Illuminate\Http\RedirectResponse {

        if (!(new \App\Services\HasPermissionService())->receive(\App\Models\Permissions::PERMISSION_SEE_PAGE_PROGRAM)) {
            return redirect(route('rating.index'));
        }

        $filmStatus = $requestParam['filmstatus'] ?? '';
        $keywords = $requestParam['keywords'] ?? '';
        $filmModifications = $requestParam['filmmodifications'] ?? '';
        $titleDescription = trim($requestParam['title_description'] ?? '');
        $onlyNotSet = ($requestParam['only_not_set'] ?? false) === 'true';

        $films = (new FilmsQueryBuilderService())->buildFilmsQuery(
            filmStatusIds: array_filter(explode(',', $filmStatus)),
            keywordIds: array_filter(explode(',', $keywords)),
            filmModificationIds: array_filter(explode(',', $filmModifications)),
            filmSourceIds: [],
            filmNrTitleDescription: $titleDescription,
            onlyNotSetInProgram: $onlyNotSet,
            rated: [],
            ratedCount: [],
            viewerId: 0
        );

        return $films;
    }

}
