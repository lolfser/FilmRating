<?php
declare(strict_types=1);
namespace App\Http\Controllers;

use App\Models\Days;
use App\Models\Films;
use App\Models\Genres;
use App\Models\Programblockmetas;
use App\Models\Programblocks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ExportController extends Controller {

    public function print(Request $request, int $dayId): \Inertia\Response {

        $day = Days::where('id', $dayId)->first();

        if ($day === null) {
            echo "Tag ist unbekannt";
            exit;
        }

        $metas = Programblockmetas::where('days_id', $dayId)->get();
        $startTime = (int) $day->date->timestamp;
        $this->printStyle('@media screen');
        $this->printStyle('@media print');
        echo "<table>";
        echo '<tr><td>Tag: ' . (new \DateTime($day->date->toString()))->format('l, d.m.Y') . '</td></tr>';
        foreach ($metas as $meta) {
            $timestampNext = (new \DateTime($meta->start))->setDate(1970, 1 ,1)->getTimestamp() + $startTime;
            echo '<tr>
                      <td>' . substr($meta->start ?? '', 0, -3) . ' ' . $meta->location->name . '</td>
                      <td colspan="2">Puffer pro Film: ' . $meta->puffer_per_item . ' Minuten</td>
                  </tr>';
            foreach (
                Programblocks::where('programblockmetas_id', $meta->id)
                    ->orderBy('programblockmetas_id')->orderBy('id')
                    ->get() as $block
            ) {

                $film = Films::where('id', $block->films_id)->limit(1)->get()->first();

                if ($film === null) {
                    continue;
                }

                $timestampNow = $timestampNext;
                $timestampNext = (int)($timestampNow + $film->duration + ($meta->puffer_per_item * 60.0));

                echo '<tr>';
                    echo '<td>' . (new \DateTime())->setTimestamp($timestampNow)->format('H:i') . '<br>
                        ' . round($film->duration / 60, 2) . ' Minuten</td>';
                    echo '<td>' . $film->film_identifier . '</td>';
                    echo '<td>';
                        echo '<b>' . $film->name . '</b> ';
                        foreach ($film->genres as $genre) {
                            echo '<span class="genre genre-bg-color-' . md5($genre->bgcolor) . ' genre-font-color-' . md5($genre->fontcolor) . '">';
                                echo $genre->name;
                            echo '</span>';
                        }
                        if (($film->description ?? '') !== '') {
                            echo '<br>' . $film->description;
                        }
                        if (($film->keywords ?? []) !== []) {
                            $keywords = [];
                            foreach ($film->keywords as $keyword) {
                                $keywords[] = $keyword->name;
                            }
                            if ($keywords !==[]) {
                                echo ((($film->description ?? '') === '') ? '' : ', ') . implode(', ', $keywords);
                            }
                        }

                    echo '</td>';
                echo '</tr>';
            }
        }
        echo "</table>";

        exit;

    }

    public function csv(Request $request, int $dayId): \Inertia\Response {

        $day = Days::where('id', $dayId)->first();

        if ($day === null) {
            echo "Tag ist unbekannt";
            exit;
        }

        $metas = Programblockmetas::where('days_id', $dayId)->get();
        $startTime = (int) $day->date->timestamp;
        $output = '';
        $output .= 'Tag;' . (new \DateTime($day->date->toString()))->format('l, d.m.Y');
        $output .= PHP_EOL;
        foreach ($metas as $meta) {
            $timestampNext = (new \DateTime($meta->start))->setDate(1970, 1 ,1)->getTimestamp() + $startTime;
            $output .= substr($meta->start ?? '', 0, -3) . ';' . $meta->location->name . ';Puffer pro Film;' . $meta->puffer_per_item . PHP_EOL;
            $output .= PHP_EOL;
            $output .= '"Start";"Dauer";"Film-Nr";"Titel";"Genre";"Keywords";"Beschreibung"' . PHP_EOL;
            foreach (
                Programblocks::where('programblockmetas_id', $meta->id)
                    ->orderBy('programblockmetas_id')->orderBy('id')
                    ->get() as $block
            ) {

                $film = Films::where('id', $block->films_id)->limit(1)->get()->first();

                if ($film === null) {
                    continue;
                }

                $timestampNow = $timestampNext;
                $timestampNext = (int)($timestampNow + $film->duration + ($meta->puffer_per_item * 60.0));

                $output .= (new \DateTime())->setTimestamp($timestampNow)->format('H:i')
                    . ';' . '"' . round($film->duration / 60, 2) . '"'
                    . ';' . '"' . $film->film_identifier . '"'
                    . ';' . '"' . $film->name . '"'
                    . ';';

                $genres = [];
                foreach ($film->genres ?? [] as $genre) {
                    $genres[] = $genre->name;
                }
                $output .= '"' . implode(', ', $genres) . '"';
                $output .= ';';

                $keywords = [];
                foreach ($film->keyword ?? [] as $keyword) {
                    $keywords[] = $keyword->name;
                }
                $output .= '"' . implode(', ', $keywords) . '"';
                $output .= ';';

                $output .= '"' . ($film->description ?? '') . '"';
                $output .= ';';

                $output .= PHP_EOL;
            }
        }

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . (new \DateTime($day->date->toString()))->format('l, d.m.Y') . '.csv');
        header('Content-Type: text/x-csv; charset=ISO-8859-1');
        header('Content-Transfer-Encoding: binary');
        header('Content-Description: File Transfer');

        echo chr(255) . chr(254).mb_convert_encoding($output, 'UTF-16LE', 'UTF-8');

        exit;

    }

    private function printStyle(string $media): void
    {
        echo "<style> " . $media . " {
                    body {
                        -webkit-print-color-adjust: exact !important;
                        print-color-adjust:exact !important;
                    }
                    td {
                        border: 1px solid;
                        padding: 5px;
                    }
                    table {
                        border: 1px solid black;
                        border-collapse: collapse;
                        border-spacing: 1px;
                    }
                    .genre {
                        padding: 3px;
                        font-size: 12px;
                    }";
        $bg = [];
        $fc = [];
        foreach (Genres::all() as $genre) {
            if (!in_array($genre->bgcolor, $bg, true)) {
                echo '.genre-bg-color-' . md5($genre->bgcolor) . '{background-color: ' . $genre->bgcolor . ' }';
                $bg[] = $genre->bgcolor;
            }
            if (!in_array($genre->bgcolor, $fc, true)) {
                echo '.genre-font-color-' . md5($genre->fontcolor) . '{color: ' . $genre->fontcolor . ' }';
                $fc[] = $genre->bgcolor;
            }
        }
        echo "}
            </style>";
    }

    public function rating(Request $request): \Inertia\Response {

        $viewerId = (new \App\Services\ReceiveCurrentViewerIdService())->receive();
        if ($viewerId === 0) {
            die('Du musst eingeloggt sein.');
        }

        $data = DB::table('films')
            ->selectRaw('
                fs.name AS source,
                films.id, film_identifier, films.name,
                CONCAT(g.value, g.trend) AS grade,
                duration/60 AS duration,
                fst.name AS status,
                count(rating_count.id) as rating_count,
                GROUP_CONCAT(viewers.initials SEPARATOR ", ") as rating_initials
            ')
            ->leftJoin(DB::raw('filmsources fs'), 'filmsources_id', '=', 'fs.id')
            ->leftJoin(
                DB::raw('ratings r'),
                 function($join) use ($viewerId) {
                     $join->on(DB::raw('r.films_id'), '=', DB::raw("films.id"));
                     $join->on(DB::raw('r.viewers_id'),'=', DB::raw($viewerId));
                 })
            ->leftJoin(DB::raw('grades g'), 'g.id', '=', 'r.grades_id')
            ->leftJoin(DB::raw('filmstatus fst'), 'fst.id', '=', 'films.filmstatus_id')
            ->leftJoin(DB::raw('ratings rating_count'), 'rating_count.films_id', '=', 'films.id')
            ->leftJoin(DB::raw('viewers'), 'rating_count.viewers_id', '=', 'viewers.id')
            ->groupBy("source", "id", "film_identifier", "name","grade","duration","status",)
            ->get();

        /*
            SELECT
                fs.name AS soruce, f.id, f.film_identifier, f.name,
                CONCAT(g.value, g.trend) AS grade,
                f.duration/60 AS duration,
                fst.name AS status
                count(rating_count.id) as rating_count,
                GROUP_CONCAT(viewers.initials SEPARATOR ", ") as rating_initials
            FROM films f
            LEFT JOIN filmsources fs ON f.filmsources_id = fs.id
            LEFT JOIN ratings r ON r.films_id = f.id AND r.viewers_id = 1
            LEFT JOIN grades g ON g.id = r.grades_id
            LEFT JOIN filmstatus fst ON fst.id = f.filmstatus_id
            LEFT JOIN ratings rating_count ON rating_count.films_id = f.id
            LEFT JOIN viewers ON rating_count.viewers_id = viewers.id
            GROUP BY source, id, film_identifier, name, grade, duration, status
        */

        $output = 'Quelle;id;FFW-ID;name;deine Note;dauer;status;Anzahl Bewertungen;Bewertung von';
        $output .= PHP_EOL;
        foreach ($data as $dataset) {
            $output .= '"' . $dataset->source . '"'
                . ';' . '"' . $dataset->id . '"'
                . ';' . '"' . $dataset->film_identifier . '"'
                . ';' . '"' . $dataset->name . '"'
                . ';' . '"' . $dataset->grade . '"'
                . ';' . '"' . round((float) $dataset->duration, 1) . '"'
                . ';' . '"' . $dataset->status . '"'
                . ';' . '"' . $dataset->rating_count . '"'
                . ';' . '"' . $dataset->rating_initials . '"'
                . ';' . PHP_EOL;
        }

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . (new \DateTime())->format('Y-m-d-H-i-s') . '_rating_export.csv');
        header('Content-Type: text/x-csv; charset=ISO-8859-1');
        header('Content-Transfer-Encoding: binary');
        header('Content-Description: File Transfer');

        echo chr(255) . chr(254) . mb_convert_encoding($output, 'UTF-16LE', 'UTF-8');

        exit;
    }

}
