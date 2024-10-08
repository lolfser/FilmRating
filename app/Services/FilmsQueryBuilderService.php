<?php
declare(strict_types=1);
namespace App\Services;

use App\Models\Films;
use Illuminate\Support\Facades\DB;

class FilmsQueryBuilderService {

    public const RATED_NOBODY = 1;
    public const RATED_I_RATED = 2;
    public const RATED_I_NOT_RATED = 3;

    /**
     * @param int[] $filmStatusIds
     * @param int[] $keywordIds
     * @param int[] $filmModificationIds
     * @param int[] $filmStatusIds
     * @param string $titleDescription
     * @param bool onlyNotSetInProgram
     *
     * @return \Illuminate\Database\Eloquent\Builder<Films>
     */
    public function buildFilmsQuery(
        array $filmStatusIds,
        array $keywordIds,
        array $filmModificationIds,
        array $filmSourceIds,
        string $titleDescription,
        bool $onlyNotSetInProgram,
        int $rated,
        int $viewerId
    ): \Illuminate\Database\Eloquent\Builder {

        $films = Films::query()->select('films.*');

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

        if ($titleDescription !== '') {
            $films = $films->whereNested(
                function($query) use ($titleDescription) {
                    $query->where('name', 'like', '%' . $titleDescription . '%')
                        ->orWhere('description', 'like', '%' . $titleDescription . '%');
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
                ->orderBy('films.id')
                ->where('viewers.id', $viewerId)
                ->where('ratings.grades_id', '>', 0);

        } elseif ($rated === self::RATED_NOBODY) {
            $films = $films
                ->leftJoin('ratings','ratings.films_id', '=', 'films.id')
                ->leftJoin('viewers','ratings.viewers_id', '=', 'viewers.id')
                ->where('viewers.id', null);
        } elseif ($rated === self::RATED_I_NOT_RATED) {
            $films = $films
                ->leftJoin('ratings','ratings.films_id', '=', 'films.id')
                ->leftJoin('viewers','ratings.viewers_id', '=', 'viewers.id')
                ->orderBy('films.id')
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

        return $films;
    }

}
