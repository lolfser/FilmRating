<?php

namespace App\Http\Controllers;

use App\Models\Films;
use App\Models\Programblockmetas;
use App\Models\Programblocks;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProgramblocksController extends Controller {

    public function index(): \Inertia\Response  {

        $allFilms = Films::query()->limit(10)->get();

        foreach ($allFilms as $film) {
            // Loading pivots
            $film->languages;
            $film->genres;
        }

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

        return Inertia::render('Program', [
            'films' => $allFilms,
            'programmetas' => $metas,
            'headerLinks' => (new \App\Services\HeaderLinkService())->receive(),
            'footerLinks' => (new \App\Services\FooterLinkService())->receive(),
            '_token' => csrf_token(),
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


}
