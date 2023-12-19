<script setup>
import TextInput from './TextInput.vue';
import PrimaryButton from './PrimaryButton.vue';
import Headline from './Headline.vue';
import Footer from './Footer.vue';
import InputError from './InputError.vue';
import { translate } from './../trans';

import MultiSelect from "@/Components/MultiSelect.vue";
</script>
<template>
<Headline :headline="headline" />
<form action="/rating/update" method="POST">
    <input type="hidden" name="_token" v-model="token" />
    <input type="hidden" name="id" v-bind:value="film.film_identifier" />
    <div>
        <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
            <h1>Allgemeine Informationen</h1><br>
            <table class="table">
                <tr>
                  <td>{{ translate('attributes.film_identifier') }}</td>
                  <td>{{ film.film_identifier }}</td>
                </tr>
                <tr>
                  <td>{{ translate('attributes.sources_id') }}</td>
                  <td>{{ film.filmsource.name }}</td>
                </tr>
                <tr>
                  <td>{{ translate('attributes.name') }}</td>
                  <td>{{ film.name }}</td>
                </tr>
                <tr>
                  <td>{{ translate('attributes.description') }}</td>
                  <td>{{ film.description }}</td>
                </tr>
                <tr>
                  <td>{{ translate('attributes.duration') }}</td>
                  <td>{{ film.duration / 60 }} Minuten</td>
                </tr>
            </table>
            <br><br>
            <h1>Änderbare Einstellungen</h1>
            <br>
            <table>
                <tr v-for="(language, type) in languages">
                  <td>Sprache: {{ type }}</td>
                  <td>
                    <span v-for="lang in language">
                        <label><input :checked="isSelected(film.languages, lang.id)" type="radio" :name="'language_' + type" :value="lang.id" /> {{lang.language}}</label>
                        &nbsp;&nbsp;&nbsp;
                    </span>
                  </td>
                </tr>
                <tr>
                  <td>Generes</td>
                  <td>
                      <MultiSelect :options="genres" :optionLabel="genreLabels" :optionValue="genreValues"
                         placeholder="Genre wählen"
                         name="genres"
                         autoFilterFocus v-model="selectedGenres"
                        />
                  </td>
                </tr>
                <tr>
                  <td>Dein persönlicher Kommentar</td>
                  <td>
                    <textarea name="comment">{{rating?.comment}}</textarea>
                  </td>
                </tr>
                <tr>
                  <td>Deine Bewertung</td>
                  <td>
                    <span v-for="grade in grades">
                        <label><input :checked="isSelected([{id: grade.id}], rating.grades_id)" type="radio" name="grades_id" :value="grade.id" />&nbsp;&nbsp;{{grade.value}}{{grade.trend}}</label>
                            &nbsp;&nbsp;&nbsp;
                    </span>
                  </td>
                </tr>
            </table>
            <br>
            <PrimaryButton>Speichern</PrimaryButton>
        </div>

        <Footer />

    </div>
</form>
</template>
<script>
export default {
  props: ['film', 'rating', '_token', 'headline', 'errors', 'languages', 'grades', 'genres'],
  computed: {
    token: function () {
      return this._token
    },
  },
      mounted() {
        let genres = [];
        this.film.genres.every(function(genre) {
            genres.push(genre.id);
            return true;
        });
        this.selectedGenres = genres;
    },
        data() {
        return {
            'selectedGenres': []
        }
    },
  methods: {
      genreValues: function (genre) {
        return genre.id;
    },
      genreLabels: function (genre) {
        return genre.name;
    },
    isSelected: function (filmLanguages, currentLanguage) {
        let result = false;
        filmLanguages.every(function (l) {
            if (l.id == currentLanguage) {
                result = true;
                return false; // break
            }
            return true; // continue;
        })
        if (typeof this.film.id === "undefined") return 0;
        return result;
    },
  },
}
</script>
