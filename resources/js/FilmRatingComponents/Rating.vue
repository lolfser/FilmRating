<script setup>
import Headline from './Headline.vue';
import Footer from './Footer.vue';
import FilmRow from "@/FilmRatingComponents/RatingRow.vue";
import MultiSelect from "@/FilmRatingComponents/MultiSelect.vue";

</script>
<template>
    <Headline :headline="headline" />
    <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
        <form method="post" action="/rating/filter" name="filter-form">
            <input type="hidden" name="_token" :value="_token" />
            <input type="button" name="filter-button" value="Filtern"
                   :onClick="filterFunction"
                   class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
            />
            Seite: <input
                type="number" name="page"
                style="max-width: 90px"
                :value="currentPage"
                placeholder="Seite"
                @keyup.enter="filterInputs"
            > von {{ totalPages }}
            <MultiSelect :options="filmstatus" :optionLabel="getElementName" :optionValue="getElementId"
                placeholder="Nach Status filtern"
                name="fl_filmstatus"
                autoFilterFocus
                v-model="selectedFilmStatus"
                style="display: inline"
            />
            <MultiSelect :options="keywords" :optionLabel="getElementName" :optionValue="getElementId"
                placeholder="Nach Stichwörtern filtern"
                name="fl_keywords"
                autoFilterFocus
                v-model="selectedKeywords"
                style="display: inline"
            />
            <MultiSelect :options="filmModifications" :optionLabel="getElementName" :optionValue="getElementId"
                placeholder="Nach Modifikationen filtern"
                name="fl_filmmodifications"
                autoFilterFocus
                v-model="selectedFilmModifications"
                style="display: inline"
            />
            <MultiSelect :options="filterRateOptions" :optionLabel="getElementName" :optionValue="getElementId"
                placeholder="Nach Bewertung filtern"
                name="fl_rated"
                autoFilterFocus
                v-model="selectedRateOption"
                style="display: inline"
                :selectionLimit="1"
            />
            <MultiSelect :options="filmsources" :optionLabel="getElementName" :optionValue="getElementId"
                placeholder="Nach Film-Source filtern"
                name="fl_filmSource"
                autoFilterFocus
                v-model="selectedFilmSource"
                style="display: inline"
            />
            <label>
                <input type="text" name="fl_title_description"
                       placeholder="Nr. / Namen / Beschreibung filtern"
                       :value="selectedTitleDescription"
                       @keyup.enter="filterInputs"
                /></label>
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
                    :film-modifications="filmModifications"
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
        'headline',
        'footerLinks',
        'filmModifications',
        'filmsources',
        'keywords',
        'viewers',
        'user',
        '_token',
        'totalPages',
        'currentPage'
    ],
    data() {
        return {
            selectedFilmStatus: this.active_filter.fl_filmstatus,
            selectedKeywords: this.active_filter.fl_keywords,
            selectedFilmModifications: this.active_filter.fl_filmmodifications,
            selectedTitleDescription: this.active_filter.fl_title_description,
            selectedRateOption: this.active_filter.fl_rated,
            selectedFilmSource: this.active_filter.fl_filmSource
        }
    },
    methods: {
        getElementId: function (element) {
            return element.id;
        },
        getElementName: function (element) {
            return element.name;
        },
        filterInputs(event) {
            if (event.key === "Enter") {
                document.getElementsByName("filter-button")[0].click();
            }
        },
        filterFunction: function (event) {
            let data = new FormData();
            data.append('page', document.getElementsByName('page')[0].value);
            data.append('fl_filmstatus', this.selectedFilmStatus);
            data.append('fl_keywords', this.selectedKeywords);
            data.append('fl_rated', this.selectedRateOption);
            data.append('fl_filmSource', this.selectedFilmSource);
            if (typeof this.selectedFilmModifications !== "undefined") {
                data.append('fl_filmmodifications', this.selectedFilmModifications);
            }
            this.selectedTitleDescription = document.getElementsByName('fl_title_description')[0].value;
            data.append('fl_title_description', this.selectedTitleDescription);

            event.target.style.backgroundColor = "yellow";

            const queryString = new URLSearchParams(data).toString()
            window.location.href = window.location.pathname + '?' + queryString;
            return event;
        }
    }
}
</script>
