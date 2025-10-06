<script setup>
import Headline from './Headline.vue';
import Footer from './Footer.vue';
import FilmRow from "@/FilmRatingComponents/RatingRow.vue";
import MultiSelectTemplate from './MultiSelectTemplate.vue';

</script>
<template>
    <Headline :headline="headline" />
    <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
        <form method="post" action="/rating/filter" name="filter-form">
            <input type="hidden" name="_token" :value="_token" />
            <div class="filter-item">
                <input type="button" name="filter-button" value="Filtern"
                       :onClick="filterFunction"
                       class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                />
                <div class="selected-list">&nbsp;</div>
            </div>
            <div class="filter-item">
                Seite: <input
                    type="number" name="page"
                    style="max-width: 90px"
                    :value="currentPage"
                    placeholder="Seite"
                    @keyup.enter="filterInputs"
                >
                <br>
                <div class="selected-list" style="text-align: right;">von {{ totalPages }}</div>
            </div>
            <MultiSelectTemplate
                name="year"
                :options="years"
                :selected-options="active_filter.years"
                placeholder="Jahr filtern"
            />
            <MultiSelectTemplate
                name="filmstatus"
                :options="filmstatus"
                :selected-options="active_filter.filmstatus"
                placeholder="Filmstatus filtern"
            />
            <MultiSelectTemplate
                name="filmsources"
                :options="filmsources"
                :selected-options="active_filter.filmsources"
                placeholder="Filmsource filtern"
            />
            <MultiSelectTemplate
                name="keywords"
                :options="keywords"
                :selected-options="active_filter.keywords"
                placeholder="Stichwörtern filtern"
            />
            <MultiSelectTemplate
                name="genres"
                :options="genres"
                :selected-options="active_filter.genres"
                placeholder="Genres filtern"
            />
            <MultiSelectTemplate
                name="filmmodifications"
                :options="filmmodifications"
                :selected-options="active_filter.filmmodifications"
                placeholder="Modifikationen filtern"
            />
            <MultiSelectTemplate
                name="rated"
                :options="rated"
                :selected-options="active_filter.rated"
                placeholder="eigene Bewertung filtern"
            />
            <MultiSelectTemplate
                name="ratedCount"
                :options="ratedCount"
                :selected-options="active_filter.ratedCount"
                placeholder="Anzahl Bewertungen filtern"
            />
            <div class="filter-item">
                <label>
                    <input type="text" name="title_description"
                           placeholder="Nr. / Namen / Beschreibung filtern"
                           :value="selectedTitleDescription"
                           @keyup.enter="filterInputs"
                    />
                </label>
                <div class="selected-list">&nbsp;</div>
            </div>
        </form>
    </div>
    <div>
        <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
            <table class="table">
              <thead>
                <tr>
                  <th>Nr.</th>
                  <th>Name</th>
                  <th>Globale Einstellungen</th>
                  <th>Wertungen & dein Kommentar</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <FilmRow v-for="film in films" :film="film"
                    :ratings="film.ratings"
                    :grades="grades"
                    :genres="genres"
                    :languages="languages"
                    :filmstatus="filmstatus"
                    :viewerId="viewerId"
                    :filmmodifications="filmmodifications"
                    :viewers="viewers"
                    :keywords="keywords"
                    :user="user"
                    :_token="_token" />
              </tbody>
            </table>

        </div>
        <Footer :footerLinks="footerLinks" />
    </div>
</template>
<script>

export default {
    props: [
        'films',
        'grades',
        'genres',
        'languages',
        'filmstatus',
        'viewerId',
        'active_filter',
        'filterRateOptions',
        'filterRateCountOptions',
        'filterYears',
        'headline',
        'footerLinks',
        'filmmodifications',
        'filmsources',
        'keywords',
        'viewers',
        'user',
        'years',
        'rated',
        'ratedCount',
        '_token',
        'totalPages',
        'currentPage'
    ],
    data() {
        return {
            selectedFilmModifications: this.active_filter.filmmodifications,
            selectedTitleDescription: this.active_filter.title_description,
        }
    },
    methods: {
        filterInputs(event) {
            if (event.key === "Enter") {
                document.getElementsByName("filter-button")[0].click();
            }
        },
        filterFunction: function (event) {
            let data = new FormData();
            data.append('page', document.getElementsByName('page')[0].value);

            let years = [];
            document.querySelectorAll('.checkbox-option-year:checked').forEach(function(element) {
                years.push(element.value);
            });
            data.append('years', years.join(','));

            let keywords = [];
            document.querySelectorAll('.checkbox-option-keywords:checked').forEach(function(element) {
                keywords.push(element.value);
            });
            data.append('keywords', keywords.join(','));

            let filmstatus = [];
            document.querySelectorAll('.checkbox-option-filmstatus:checked').forEach(function(element) {
                filmstatus.push(element.value);
            });
            data.append('filmstatus', filmstatus.join(','));

            let filmsources = [];
            document.querySelectorAll('.checkbox-option-filmsources:checked').forEach(function(element) {
                filmsources.push(element.value);
            });
            data.append('filmsources', filmsources.join(','));

            let filmmodifications = [];
            document.querySelectorAll('.checkbox-option-filmmodifications:checked').forEach(function(element) {
                filmmodifications.push(element.value);
            });
            data.append('filmmodifications', filmmodifications.join(','));

            let genres = [];
            document.querySelectorAll('.checkbox-option-genres:checked').forEach(function(element) {
                genres.push(element.value);
            });
            data.append('genres', genres.join(','));

            let rated = [];
            document.querySelectorAll('.checkbox-option-rated:checked').forEach(function(element) {
                rated.push(element.value);
            });
            data.append('rated', rated.join(','));

            let ratedCount = [];
            document.querySelectorAll('.checkbox-option-ratedCount:checked').forEach(function(element) {
                ratedCount.push(element.value);
            });
            data.append('ratedCount', ratedCount.join(','));

            this.selectedTitleDescription = document.getElementsByName('title_description')[0].value;
            data.append('title_description', this.selectedTitleDescription);

            event.target.style.backgroundColor = "yellow";

            const queryString = new URLSearchParams(data).toString()
            window.location.href = window.location.pathname + '?' + queryString;
            return event;
        }
    }
}
</script>
