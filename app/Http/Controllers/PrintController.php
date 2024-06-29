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

class PrintController extends Controller {

    public function index(Request $request, int $dayId): \Inertia\Response {

        $day = Days::where('id', $dayId)->first();
        $metas = Programblockmetas::where('days_id', $dayId)->get();
        $startTime = $day->date->timestamp;
        $this->printStyle('@media screen');
        $this->printStyle('@media print');
        echo "<table>";
        echo '<tr><td>Tag: ' . (new \DateTime($day->date->toString()))->format('l, d.m.Y') . '</td></tr>';
        foreach ($metas as $meta) {
            $timestampNext = (new \DateTime($meta->start))->setDate(1970, 1 ,1)->getTimestamp() + $startTime;
            echo '<tr>
                      <td>' . substr($meta->start, 0, -3) . ' ' . $meta->location->name . '</td>
                      <td colspan="2">Puffer pro Film: ' . $meta->puffer_per_item . ' Minuten</td>
                  </tr>';
            foreach (
                Programblocks::where('programblockmetas_id', $meta->id)
                    ->orderBy('programblockmetas_id')->orderBy('id')
                    ->get() as $block
            ) {

                $film = Films::where('id', $block->films_id)->limit(1)->get()->first();

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

        return Inertia::render('Stats', [
            'stats' => $arr,
            'statsGlobalRatingCount' => $globalRating,
            'genreStats' => (new \App\Services\Stats\GenresService())->receive(),
            'keywordStats' => (new \App\Services\Stats\KeywordsService())->receive(),
            'noDurationStats' => $this->receiveFilmsWithoutDuration(),
            'headerLinks' => (new \App\Services\HeaderLinkService())->receive(),
            'footerLinks' => (new \App\Services\FooterLinkService())->receive(),
        ]);
    }

    /**
     * @return Genres|mixed
     */
    public function printStyle(string $media): void
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
