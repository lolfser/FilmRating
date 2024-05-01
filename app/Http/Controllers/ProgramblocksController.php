<?php

namespace App\Http\Controllers;

use App\Models\Films;
use App\Models\Programblockmetas;
use App\Models\Programblocks;
use Database\Seeders\ProgramblockmetasSeeder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProgramblocksController extends Controller {

    public function index(): \Inertia\Response  {

        $x = Programblocks::find(1);
        $allFilms = Films::query()->limit(10)->get();

        $metas = Programblockmetas::all();

        foreach ($metas as $block) {
            $block->programblock;
            $block->location;
        }

        return Inertia::render('Program', [
            'films' => $allFilms,
            'programmetas' => $metas,
            'headerLinks' => (new \App\Services\HeaderLinkService())->receive(),
            'footerLinks' => (new \App\Services\FooterLinkService())->receive(),
        ]);
    }

}
