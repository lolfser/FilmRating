<?php
declare(strict_types=1);
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller {

    private const ITEMS_PER_PAGE = 100;

    public function index(Request $request): \Inertia\Response {

        return Inertia::render(
            'Dashboard',
            [
                'isLoggedIn' => Auth::check(),
                'headerLinks' => (new \App\Services\HeaderLinkService())->receive(),
            ]
        );
    }

}
