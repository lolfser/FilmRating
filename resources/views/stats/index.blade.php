@extends('layout')
@section('pageTitle')
    Statistiken
@endsection

@section('style')
@endsection

@section('content')
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
    Genres<br><br>
    <table class="table">
        <thead>
        <tr>
            <td>Genre</td>
            <td>Anzahl</td>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($genreStats as $key => $stat) { ?>
            <tr>
                <td><?php echo $stat['name']; ?></td>
                <td><?php echo $stat['counter']; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <br><br>
    Stichwort-Analyse<br><br>
    <table class="table">
        <thead>
            <tr>
                <td>Stichwort</td>
                <td>Anzahl</td>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($keywordStats as $key => $stat) { ?>
        <tr>
            <td><?php echo $stat['name']; ?></td>
            <td><?php echo $stat['counter']; ?></td>
        </tr>
        <?php } ?>
        </tbody>
    </table>
    <br><br>
    Keine Laufzeit<br><br>
    <table class="table">
        <?php foreach ($noDurationStats as $key => $stat) { ?>
        <tr>
            <td><?php echo $stat['film_identifier']; ?></td>
            <td><?php echo $stat['name']; ?></td>
        </tr>
        <?php } ?>
    </table>

</div>
@endsection
