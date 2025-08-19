<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\View\View;

class StatsController extends Controller
{

    public function index(): View|\Illuminate\Http\RedirectResponse
    {

        if (!(new \App\Services\HasPermissionService())->receive(\App\Models\Permissions::PERMISSION_SEE_PAGE_STATICS)) {
            return redirect(route('rating.index'));
        }

        return view(
            'stats/index',
            [
                'statsGlobalRatingCount' => (new \App\Services\Statistic\GlobalFilmsStatsService())->receive(),
                'statusStats' => (new \App\Services\Statistic\StatusService())->receive(),
                'viewerStats' => (new \App\Services\Statistic\FilmsForViewerService())->receive(),
                'genreStats' => (new \App\Services\Statistic\GenresService())->receive(),
                'keywordStats' => (new \App\Services\Statistic\KeywordsService())->receive(),
                'noDurationStats' => (new \App\Services\Statistic\FilmsWithoutDurationService())->receive(),
                'notUsedKeywordsStats' => (new \App\Services\Statistic\NotUsedKeywordsService())->receive(),
                'filmCountDurationGroupStats' => (new \App\Services\Statistic\FilmCountDurationGroupService())->receive(),
                'possibleDuplicatesStats' => (new \App\Services\Statistic\PossibleDuplicatesService())->receive(),
                'gradePlayTimeStatsOverAll' => (new \App\Services\Statistic\GradePlayTimeService())->receive(),
                'gradePlayTimeStatsOpen' => (new \App\Services\Statistic\GradePlayTimeService())->receive(['status' => [1]]),
                'gradePlayTimeStatsDabei' => (new \App\Services\Statistic\GradePlayTimeService())->receive(['status' => [2]]),
                'gradePlayTimeStatsRaus' => (new \App\Services\Statistic\GradePlayTimeService())->receive(['status' => [3]]),
                'gradePlayTimeStatsKPJ' => (new \App\Services\Statistic\GradePlayTimeService())->receive(['status' => [4]]),
                'gradePlayTimeStatsVielleicht' => (new \App\Services\Statistic\GradePlayTimeService())->receive(['status' => [5]]),
                'gradePlayTimeStatsODV' => (new \App\Services\Statistic\GradePlayTimeService())->receive(['status' => [1,2,5]]),
                'duplicateRatedStats' => (new \App\Services\Statistic\DuplicateRatedService())->receive(),
                'headerLinks' => (new \App\Services\HeaderLinkService())->receive(),
            ]
        );
    }
}
