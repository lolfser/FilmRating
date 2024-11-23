<?php
declare(strict_types=1);
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Inertia\Inertia;

class DashboardController extends Controller {

    public function index(Request $request): View {

        if (Auth::check()) {
            return view(
                'home.dashboard',
                [
                    'headerLinks' => (new \App\Services\HeaderLinkService())->receive(),
                    'hasPermProgram' => (new \App\Services\HasPermissionService())->receive(\App\Models\Permissions::PERMISSION_SEE_PAGE_PROGRAM),
                    'hasPermRating' => (new \App\Services\HasPermissionService())->receive(\App\Models\Permissions::PERMISSION_SEE_PAGE_RATING),
                ]
            );
        }

        return view(
            'home.index',
        );
    }

}
