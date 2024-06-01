<?php

namespace App\Http\Controllers;

use App\Models\Films;
use App\Models\Filmstatus;
use App\Models\Programblockmetas;
use App\Models\Programblocks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ProgramblocksController extends Controller {

    public function index(Request $request): \Inertia\Response {

        $films = $this->buildFilmsQuery($request->all());
        $allFilms = $films/*->limit(10)*/->get();
        $this->loadPivots($allFilms);

        $metas = Programblockmetas::all();

        foreach ($metas as $meta) {
            $meta->location;
            foreach (Programblocks::where('programblockmetas_id', $meta->id)->get() as $block) {
                /** @var Programblocks $block */
                // Loading pivots
                $block->film->languages;
                $block->film->genres;
                $meta->addBlock($block->film);
            }
        }

        $selectedIds = array_map(
            function ($item) {
                return (int) $item;
            },
            explode(',', $request->all()['filmstatus'] ?? '')
        );

        return Inertia::render('Program', [
            'films' => $allFilms,
            'programmetas' => $metas,
            'filmstatus' => Filmstatus::all(),
            'headerLinks' => (new \App\Services\HeaderLinkService())->receive(),
            'footerLinks' => (new \App\Services\FooterLinkService())->receive(),
            '_token' => csrf_token(),
            'filter' => [
                //'filmstatus' => Filmstatus::query()->whereIn('id', $selectedIds)->get(),
                'filmstatus' => array_map(function ($i) {return (int) $i;},explode(',', $request->all()['filmstatus'] ?? '')),
                'only_not_set' => ($request->all()['only_not_set'] ?? false) === 'true'
            ]
        ]);
    }

    /**
     * @return array<int, mixed>|\Illuminate\Http\RedirectResponse
     */
    public function save(Request $request): array|\Illuminate\Http\RedirectResponse {

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
                'languages' => $film->languages,
                'duration' => $film->duration,
            ];
        }
        return $res;
    }

    /**
     * @return array<int, mixed>|\Illuminate\Http\RedirectResponse
     */
    public function filter(Request $request): mixed/*|array|\Illuminate\Http\RedirectResponse*/ {

        $films = $this->buildFilmsQuery($request->all());
        $films = $films/*->limit(10)*/->get();
        $this->loadPivots($films);

        return $films;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Collection|array $allFilms
     * @return void
     */
    public function loadPivots(\Illuminate\Database\Eloquent\Collection|array $allFilms): void
    {
        foreach ($allFilms as $film) {
            // Loading pivots
            $film->languages;
            $film->genres;
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function buildFilmsQuery(array $requestParam): \Illuminate\Database\Eloquent\Builder
    {
        $filmStatus = $requestParam['filmstatus'] ?? [];
        $onlyNotSet = ($requestParam['only_not_set'] ?? false) === 'true';

        $films = Films::query();

        if ($filmStatus !== []) {
            $films = $films->whereIn('filmstatus_id', explode(',', $filmStatus));
        }

        if ($onlyNotSet) {
            $filmsAlreadyUsed = DB::table('programblocks')->select('films_id')->groupBy('films_id')->pluck('films_id')->values()->toArray();
            $films = $films->whereNotIn('id', $filmsAlreadyUsed);
        }

        return $films;
    }

}
