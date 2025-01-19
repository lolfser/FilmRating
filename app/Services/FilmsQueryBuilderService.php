<?php
declare(strict_types=1);
namespace App\Services;

use App\Models\Films;
use Illuminate\Support\Facades\DB;

class FilmsQueryBuilderService {

    public const RATED_I_RATED = 1;
    public const RATED_I_NOT_RATED = 2;

    /**
     * @param int[] $filmStatusIds
     * @param int[] $keywordIds
     * @param int[] $filmModificationIds
     * @param int[] $filmSourceIds
     * @param int[] $ratedCount
     *
     * @return \Illuminate\Database\Eloquent\Builder<Films>
     */
    public function buildFilmsQuery(
        array $filmStatusIds,
        array $keywordIds,
        array $filmModificationIds,
        array $filmSourceIds,
        string $filmNrTitleDescription,
        bool $onlyNotSetInProgram,
        int $rated,
        array $ratedCount,
        int $viewerId
    ): \Illuminate\Database\Eloquent\Builder {

        $films = Films::query()->select('films.*')->distinct();

        if ($filmStatusIds !== []) {
            $films = $films->whereIn('filmstatus_id', $filmStatusIds);
        }

        if ($keywordIds !== []) {
            $films = $films->join('films_keywords', 'id', '=', 'films_id')
                ->whereIn('keywords_id', $keywordIds);
        }

        if ($filmModificationIds !== []) {
            $films = $films->join('filmmodifications_films', 'id', '=', 'films_id')
                ->whereIn('filmmodifications_id', $filmModificationIds);
        }

        if ($filmSourceIds !== []) {
            $films = $films->whereIn('filmsources_id', $filmSourceIds);
        }

        if ($filmNrTitleDescription !== '') {
            $films = $films->whereNested(
                function($query) use ($filmNrTitleDescription) {
                    $query->where('name', 'like', '%' . $filmNrTitleDescription . '%')
                        ->orWhere('description', 'like', '%' . $filmNrTitleDescription . '%')
                        ->orWhere('film_identifier', '=', $filmNrTitleDescription);
                }
            );
        }

        if ($onlyNotSetInProgram) {
            $filmsAlreadyUsed = DB::table('programblocks')->select('films_id')->groupBy('films_id')->pluck('films_id')->values()->toArray();
            $films = $films->whereNotIn('id', $filmsAlreadyUsed);
        }

        if ($rated === self::RATED_I_RATED) {
            $films = $films
                ->leftJoin('ratings', 'ratings.films_id', '=', 'films.id')
                ->leftJoin('viewers', 'viewers.id', '=', 'ratings.viewers_id')
                ->where('viewers.id', $viewerId)
                ->where('ratings.grades_id', '>', 0);
        } elseif ($rated === self::RATED_I_NOT_RATED) {
            $films = $films
                ->leftJoin('ratings','ratings.films_id', '=', 'films.id')
                ->leftJoin('viewers','ratings.viewers_id', '=', 'viewers.id')
                ->whereNested(
                    function($query) use ($viewerId) {
                        $query->whereNested(
                            function($query) use ($viewerId) {
                                $query->where('viewers.id', '=', $viewerId)
                                    ->where('ratings.grades_id', '=', 0);
                            }
                        )
                        ->orWhereNotIn('films.id',
                            function ($query) use ($viewerId) {
                                $query->select('films.id')->from('films')
                                    ->leftJoin('ratings', 'ratings.films_id', '=', 'films.id')
                                    ->leftJoin('viewers', 'viewers.id', '=', 'ratings.viewers_id')
                                    ->where('viewers.id', $viewerId);
                            }
                        );
                    }
                );
        }

        if ($ratedCount !== []) {
            $films = $films->select(
                'films.*',
                DB::raw('(SELECT COUNT(1) FROM ratings WHERE ratings.films_id = films.id AND ratings.grades_id > 0) AS rateCount')
            )->havingRaw('rateCount IN (' . implode(', ', $ratedCount) . ')');
        }

        // @TODO: new feature: dynamic via config in DB table filmsources
        $films = $films
            ->orderBy(DB::raw("filmsources_id = 3"))
            ->orderBy(DB::raw("filmsources_id = 1"))
            ->orderBy(DB::raw("filmsources_id = 2"))
            ->orderBy("id");

        return $films;
    }

}
