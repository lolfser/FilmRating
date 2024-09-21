<?php
declare(strict_types=1);
namespace App\Http\Controllers;

use App\Models\Days;
use App\Models\Films;
use App\Models\Genres;
use App\Models\Programblockmetas;
use App\Models\Programblocks;
use Illuminate\Http\Request;
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

}
