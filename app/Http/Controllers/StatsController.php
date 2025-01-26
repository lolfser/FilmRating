<?php
declare(strict_types=1);
namespace App\Http\Controllers;

use Illuminate\View\View;

class StatsController extends Controller {

    public function index(): View|\Illuminate\Http\RedirectResponse {

        if (!(new \App\Services\HasPermissionService())->receive(\App\Models\Permissions::PERMISSION_SEE_PAGE_STATICS)) {
            return redirect(route('rating.index'));
        }

        return view(
            'stats/index',
            [
                'statsGlobalRatingCount' => (new \App\Services\Statistic\GlobalFilmsStatsService())->receive(),
                'viewerStats' => (new \App\Services\Statistic\FilmsForVierwerService())->receive(),
                'genreStats' => (new \App\Services\Statistic\GenresService())->receive(),
                'keywordStats' => (new \App\Services\Statistic\KeywordsService())->receive(),
                'noDurationStats' => (new \App\Services\Statistic\FilmsWithoutDurationService())->receive(),
                'notUsedKeywordsStats' => (new \App\Services\Statistic\NotUsedKeywordsService())->receive(),
                'filmCountDurationGroupStats' => (new \App\Services\Statistic\FilmCountDurationGroupService())->receive(),
                'possibleDuplicatesStats' => (new \App\Services\Statistic\PossibleDuplicatesService())->receive(),
                'gradePlayTimeStats' => (new \App\Services\Statistic\GradePlayTimeService())->receive(),
                'headerLinks' => (new \App\Services\HeaderLinkService())->receive(),
            ]
        );
    }
}
