@extends('layout')
@section('pageTitle')
    Statistiken
@endsection

@section('style')
@endsection

@section('content')
<?php
    /** @var \App\Services\Statistic\Model\TableResult $keywordStats */
    /** @var \App\Services\Statistic\Model\TableResult $genreStats */
    /** @var \App\Services\Statistic\Model\TableResult $noDurationStats */
    /** @var \App\Services\Statistic\Model\TableResult $notUsedKeywordsStats */
    /** @var \App\Services\Statistic\Model\TableResult $filmCountDurationGroupStats */
?>
<h1>Statistiken</h1>
<div>
    Angaben der Laufzeit in Stunden (Anzahl der Filme)<br><br>
    <?php
        $firstKey = array_key_first($stats);
        $firstRow = array_merge([0 => [1 => $firstKey]], array_shift($stats));
    ?>
    <table class="table">
        <thead>
            <tr>
            <?php foreach ($firstRow as $key => $stat) { ?>
                <td>
                   <?php echo $stat[1]; ?>
                </td>
            <?php } ?>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($stats as $key => $stat) { ?>
        <tr>
            <td>
               <?php echo $key; ?>
            </td>
            <?php foreach ($stat as $key2 => $grade) { ?>
            <td>
                <?php
                    echo $grade[1];
                    if ($grade[0] !== "") {
                        echo ' (' . $grade[0] . ')';
                    }
                ?>
            </td>
            <?php } ?>
        </tr>
        <?php } ?>
        </tbody>
    </table>
    <br><br>
    Anzahl der bewerteten Filme<br><br>
    <table class="table">
        <thead>
        <tr>
            <td>Anzahl Bewertung</td>
            <td>Anzahl Film</td>
            <td>Laufzeit in Stunden</td>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($statsGlobalRatingCount as $key => $stat) { ?>
        <tr>
            <td><?php echo $stat['r']; ?></td>
            <td><?php echo $stat['c']; ?></td>
            <td><?php echo $stat['d']; ?></td>
        </tr>
        <?php } ?>
        </tbody>
    </table>
    <br><br>
    @include('stats.tableResult', ['headline' => 'Filmdaueranalyse', 'tableResult' => $filmCountDurationGroupStats])
    Genres<br><br>
    <table class="table">
        <thead>
        <tr>
            <?php foreach ($genreStats->getHeader() as $column) { ?>
            <td><?php echo $column; ?></td>
            <?php } ?>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($genreStats->getResult() as $row) { ?>
            <tr>
                <?php foreach ($row as $value) { ?>
                <td><?php echo $value; ?></td>
                <?php } ?>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <br><br>
    Stichwort-Analyse<br><br>
    <table class="table">
        <thead>
            <tr>
                <?php foreach ($keywordStats->getHeader() as $column) { ?>
                <td><?php echo $column; ?></td>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($keywordStats->getResult() as $row) { ?>
        <tr>
            <?php foreach ($row as $value) { ?>
            <td><?php echo $value; ?></td>
            <?php } ?>
        </tr>
        <?php } ?>
        </tbody>
    </table>
    <br><br>
    Keine Laufzeit<br><br>
    <table class="table">
        <thead>
            <tr>
                <?php foreach ($noDurationStats->getHeader() as $column) { ?>
                <td><?php echo $column; ?></td>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($noDurationStats->getResult() as $row) { ?>
            <tr>
                <?php foreach ($row as $value) { ?>
                <td><?php echo $value; ?></td>
                <?php } ?>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <br><br>
    nicht genutzte Stichworte<br><br>
    <table class="table">
        <thead>
            <tr>
                <?php foreach ($notUsedKeywordsStats->getHeader() as $column) { ?>
                <td><?php echo $column; ?></td>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($notUsedKeywordsStats->getResult() as $row) { ?>
            <tr>
                <?php foreach ($row as $value) { ?>
                <td><?php echo $value; ?></td>
                <?php } ?>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
@endsection
