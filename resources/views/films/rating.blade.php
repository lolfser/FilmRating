@extends('layout')
@section('pageTitle')
    Deine Bewertung
@endsection
@section('style')
    button {color: white; padding: 10px; background-color: darkgoldenrod}
@endsection

@section('content')

<?php
    /** @var \App\Models\Films $film */
    /** @var \App\Models\Languages[] $languages */
    /** @var \Illuminate\Database\Eloquent\Collection<App\Models\Grades> $grades */
    /** @var \Illuminate\Database\Eloquent\Collection<App\Models\Filmmodifications> $filmModifications */
    /** @var \Illuminate\Database\Eloquent\Collection<App\Models\Genres> $genres */
    /** @var \Illuminate\Database\Eloquent\Collection<App\Models\Keywords> $keywords */
    /** @var \App\Models\Ratings|null $rating */
?>

<h1>Deine Bewertung</h1>

<form action="/rating/update" method="POST">
    @csrf
    <input type="hidden" name="id" value="<?php echo $film->film_identifier; ?>" />
    <div>
        <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
            <h1>Allgemeine Informationen</h1><br>
            <table class="table">
                <tr>
                  <td>{{__('attributes.film_identifier') }}</td>
                  <td><?php echo $film->film_identifier; ?></td>
                </tr>
                <tr>
                  <td>{{__('attributes.sources_id') }}</td>
                  <td><?php echo $film->filmsource->name; ?></td>
                </tr>
                <tr>
                  <td>{{__('attributes.name') }}</td>
                  <td><?php echo $film->name; ?></td>
                </tr>
                <tr>
                  <td>{{__('attributes.description') }}</td>
                  <td><?php echo $film->description; ?></td>
                </tr>
                <tr>
                  <td>{{__('attributes.duration') }}</td>
                  <td><?php echo $film->duration / 60; ?> Minuten</td>
                </tr>
            </table>
            <br><br>
            <h1>Änderbare Einstellungen</h1>
            <br>
            <table>
                <tr>
                    <td>Beschreibung</td>
                    <td>
                        <textarea name="description"><?php echo $film->description; ?></textarea>
                    </td>
                </tr>
                <?php foreach ($languages as $type => $language) { ?>
                <tr>
                    <td>Sprache: <?php echo $type; ?></td>
                    <td>
                        <?php
                        foreach ($language as $lang) {
                            $checked = false;
                            foreach ($film->languages as $filmLang) {
                                if ($filmLang->id === $lang->id) {
                                    $checked = true;
                                    break;
                                }
                            }
                        ?>
                        <span>
                            <label>
                                <input
                                <?php if ($checked) {echo 'checked';} ?>
                                type="checkbox"
                                name="language_<?php echo $film->id . '_' . $lang->id; ?>"
                                value="<?php echo $lang->id; ?>" />
                                    <?php echo $lang->language?>
                            </label>
                            &nbsp;&nbsp;&nbsp;
                        </span>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
                <tr>
                    <td>Genres</td>
                    <td>
                        <?php foreach ($genres as $genre) {
                            $checked = false;
                            foreach ($film->genres as $filmGenre) {
                                if ($filmGenre->id === $genre->id) {
                                    $checked = true;
                                    break;
                                }
                            }
                        ?>
                        <span>
                            <label>
                                <input
                                    <?php if ($checked) {echo 'checked';} ?>
                                    type="checkbox"
                                    name="genres[]"
                                    <?php // name="genre_<?php echo $genre->id; ?>
                                    value="<?php echo $genre->id; ?>" />
                                        <?php echo $genre->name; ?>
                            </label>
                            &nbsp;&nbsp;&nbsp;
                        </span>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td>Film-Modifikationen</td>
                    <td>
                        <?php foreach ($filmModifications as $filmModification) {
                            $checked = false;
                            foreach ($film->filmModifications as $filmMod) {
                                if ($filmMod->id === $filmModification->id) {
                                    $checked = true;
                                    break;
                                }
                            }
                        ?>
                        <span>
                            <label>
                                <input
                                    <?php if ($checked) {echo 'checked';} ?>
                                    type="checkbox"
                                    name="filmModification_<?php echo $filmModification->id; ?>"
                                    value="true" />
                                        <?php echo $filmModification->name; ?>
                            </label>
                            &nbsp;&nbsp;&nbsp;
                        </span>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td>Stichworte</td>
                    <td>
                        <textarea name="keywords" rows="2"><?php
                                $sep = '';
                                foreach ($film->keywords as $keyword) {
                                    echo $sep . $keyword->name;
                                    $sep = ', ';
                                }
                            ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td>Dein persönlicher Kommentar</td>
                    <td>
                        <textarea name="comment"><?php echo $rating?->comment; ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td>Deine Bewertung</td>
                    <td>
                    <?php
                        foreach ($grades as $grade) {
                            $checked = $rating?->grade?->id === $grade->id
                    ?>
                    <span>
                        <label>
                            <input
                                <?php if ($checked) {echo 'checked';} ?>
                                type="radio"
                                name="grades_id"
                                value="<?php echo $grade->id; ?>" />
                                &nbsp;&nbsp;<?php echo $grade->value . $grade->trend; ?>
                        </label>
                        &nbsp;&nbsp;&nbsp;
                    </span>
                    <?php } ?>
                  </td>
                </tr>
            </table>
            <br>
            <button type="submit" class="btn btn-primary" style="display: inline-block; padding-left: 30px; padding-right: 30px;">
                Speichern
            </button>
        </div>
    </div>
</form>
@endsection
