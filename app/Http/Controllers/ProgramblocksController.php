<?php

namespace App\Http\Controllers;

use App\Models\Filmmodifications;
use App\Models\Films;
use App\Models\Filmstatus;
use App\Models\Keywords;
use App\Models\Programblockmetas;
use App\Models\Programblocks;
use Illuminate\Database\Eloquent\Collection;
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
            if ($meta->day !== null) {
                $meta->day->dateString = (new \DateTime($meta->day->date))->format('l, d.m.Y');
                $meta->day->dateString .= ', ';
                $meta->day->dateString .= $meta->start;
                $meta->day->dateString .= ' - ';
                $dateTime = new \DateTime($meta->start ?? '');
                $dateTime->add(new \DateInterval('PT' . ($meta->total_length ?? 0) . 'M'));
                $meta->day->dateString .= $dateTime->format('H:i');
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
     * @return Collection<int, Films>
     */
    public function filter(Request $request): Collection {

        $films = $this->buildFilmsQuery($request->all());
        $films = $films/*->limit(10)*/->get();
        $this->loadPivots($films);

        return $films;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Collection<int, Films> $allFilms
     * @return void
     */
    public function loadPivots(\Illuminate\Database\Eloquent\Collection $allFilms): void
    {
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
     * @return \Illuminate\Database\Eloquent\Builder<Films>
     */
    public function buildFilmsQuery(array $requestParam): \Illuminate\Database\Eloquent\Builder
    {
        $filmStatus = $requestParam['filmstatus'] ?? '';
        $keywords = $requestParam['keywords'] ?? '';
        $filmModifications = $requestParam['filmmodifications'] ?? '';
        $titleDescription = $requestParam['title_description'] ?? '';
        $onlyNotSet = ($requestParam['only_not_set'] ?? false) === 'true';

        $films = Films::query();

        if ($filmStatus !== '') {
            $films = $films->whereIn('filmstatus_id', explode(',', $filmStatus));
        }

        if ($keywords !== '') {
            $films = $films->join('films_keywords', 'id', '=', 'films_id')
                ->whereIn('keywords_id', explode(',', $keywords));
        }

        if ($filmModifications !== '') {
            $films = $films->join('filmmodifications_films', 'id', '=', 'films_id')
                ->whereIn('filmmodifications_id', explode(',', $filmModifications));
        }

        if ($titleDescription !== '') {
            $films = $films->whereNested(
                function($query) use ($titleDescription) {
                    $query->where('name', 'like', '%' . $titleDescription . '%')
                        ->orWhere('description', 'like', '%' . $titleDescription . '%');
                }
            );
        }

        if ($onlyNotSet) {
            $filmsAlreadyUsed = DB::table('programblocks')->select('films_id')->groupBy('films_id')->pluck('films_id')->values()->toArray();
            $films = $films->whereNotIn('id', $filmsAlreadyUsed);
        }

        return $films;
    }

}
