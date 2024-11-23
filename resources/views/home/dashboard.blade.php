@extends('layout')
@section('pageTitle')
    Home
@endsection

@section('style')
    h1 {
        margin-bottom: 30px;
    }
    li {
        margin-left: 15px;
        padding: 5px;
    }
    li ul li {
        margin-left: 15px;
        padding: 0px;
    }
@endsection

@section('content')
<div>
    <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
        <h1 class="mt-8 text-2xl font-medium text-gray-900">
            Willkommen!
        </h1>
        <p class="mt-6 text-gray-500 leading-relaxed">
            Vielen Dank, dass du uns bei der Sichtung von Filmen bewerten möchtest. Für die Sichtung hast du verschiedene Möglichkeiten.
        </p>
        <ul>
            <?php if ($hasPermRating) { ?>
            <li>
                <a href="/rating/list" style="color: blue">Zu den Filmbewertungen</a><br>
                Du kannst jeden Film eine Note geben, dabei gilt:
                <ul>
                    <li>"Note 1" steht für "Super gut, der muss auf jeden Fall gezeigt werden!"</li>
                    <li>"Note 2" steht für "Sehr gut, der Film sollte laufen""</li>
                    <li>"Note 3" steht für "Weder gut noch schlecht, er kann gezeigt werden, muss aber nicht unbedingt"</li>
                    <li>"Note 4" steht für "Eher schlecht, der Film sollte nicht gezeigt werden"</li>
                    <li>"Note 5" steht für "Sehr schlecht, der Film ist absolut nicht geeignet für das Festival"</li>
                </ul>
            </li>
            <?php } ?>
            <li>
                <a href="/rating/export" style="color: blue">Deine Filmbewertungen exportieren</a>
            </li>
            <?php if ($hasPermProgram) { ?>
            <li>
                <a href="/program" style="color: blue">Zur Programmplanung</a>
            </li>
            <?php } ?>
        </ul>
    </div>
</div>
@endsection
