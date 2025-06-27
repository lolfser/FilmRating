@extends('layout')
@section('pageTitle')
    Statistiken
@endsection

@section('style')
@endsection

@section('content')
<?php
    /** @var \App\Services\Statistic\Model\TableResult $viewerStats */
    /** @var \App\Services\Statistic\Model\TableResult $statsGlobalRatingCount */
    /** @var \App\Services\Statistic\Model\TableResult $filmCountDurationGroupStats */
    /** @var \App\Services\Statistic\Model\TableResult $gradePlayTimeStatsOverAll */
    /** @var \App\Services\Statistic\Model\TableResult $gradePlayTimeStatsOpen */
    /** @var \App\Services\Statistic\Model\TableResult $gradePlayTimeStatsDabei */
    /** @var \App\Services\Statistic\Model\TableResult $gradePlayTimeStatsRaus */
    /** @var \App\Services\Statistic\Model\TableResult $gradePlayTimeStatsVielleicht */
    /** @var \App\Services\Statistic\Model\TableResult $gradePlayTimeStatsODV */
    /** @var \App\Services\Statistic\Model\TableResult $gradePlayTimeStatsKPJ */
    /** @var \App\Services\Statistic\Model\TableResult $possibleDuplicatesStats */
    /** @var \App\Services\Statistic\Model\TableResult $genreStats */
    /** @var \App\Services\Statistic\Model\TableResult $keywordStats */
    /** @var \App\Services\Statistic\Model\TableResult $noDurationStats */
    /** @var \App\Services\Statistic\Model\TableResult $duplicateRatedStats */
    /** @var \App\Services\Statistic\Model\TableResult $notUsedKeywordsStats */

?>
<h1>Statistiken</h1>
<div>
    @include('stats.tableResult', ['headline' => 'Filme 1 + 2-Noten (alle Status)', 'tableResult' => $gradePlayTimeStatsOverAll])
    @include('stats.tableResult', ['headline' => 'Filme 1 + 2-Noten (offen / dabei / vielleicht)', 'tableResult' => $gradePlayTimeStatsODV])
    @include('stats.tableResult', ['headline' => 'Filme 1 + 2-Noten (offen)', 'tableResult' => $gradePlayTimeStatsOpen])
    @include('stats.tableResult', ['headline' => 'Filme 1 + 2-Noten (dabei)', 'tableResult' => $gradePlayTimeStatsDabei])
    @include('stats.tableResult', ['headline' => 'Filme 1 + 2-Noten (vielleicht)', 'tableResult' => $gradePlayTimeStatsVielleicht])
	@include('stats.tableResult', ['headline' => 'Filme 1 + 2-Noten (nur kpj)', 'tableResult' => $gradePlayTimeStatsKPJ])
    @include('stats.tableResult', ['headline' => 'Filme 1 + 2-Noten (raus)', 'tableResult' => $gradePlayTimeStatsRaus])
    @include('stats.tableResult', ['headline' => 'Angaben der Laufzeit in Stunden (Anzahl der Filme)', 'tableResult' => $viewerStats])
    @include('stats.tableResult', ['headline' => 'Anzahl der bewerteten Filme', 'tableResult' => $statsGlobalRatingCount])
    @include('stats.tableResult', ['headline' => 'Filmdaueranalyse', 'tableResult' => $filmCountDurationGroupStats])
    @include('stats.tableResult', ['headline' => 'mögliche Duplikate', 'tableResult' => $possibleDuplicatesStats])
    @include('stats.tableResult', ['headline' => 'Genres', 'tableResult' => $genreStats])
    @include('stats.tableResult', ['headline' => 'Stichwort-Analyse', 'tableResult' => $keywordStats])
    @include('stats.tableResult', ['headline' => 'Keine Laufzeit', 'tableResult' => $noDurationStats])
    @include('stats.tableResult', ['headline' => 'Doppelte Bewertungen', 'tableResult' => $duplicateRatedStats])

    @include('stats.tableResult', ['headline' => 'nicht genutzte Stichworte', 'tableResult' => $notUsedKeywordsStats])
</div>
@endsection
