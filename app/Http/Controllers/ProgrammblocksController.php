<?php

namespace App\Http\Controllers;

use App\Models\Films;
use App\Models\Programmblocks;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProgrammblocksController extends Controller {

    public function index(): \Inertia\Response  {

        $x = Programmblocks::find(1);
        $allFilms = Films::query()->limit(10)->get();

        return Inertia::render('Program', [
            'films' => $allFilms,
            'headerLinks' => (new \App\Services\HeaderLinkService())->receive(),
            'footerLinks' => (new \App\Services\FooterLinkService())->receive(),
        ]);
    }

}
