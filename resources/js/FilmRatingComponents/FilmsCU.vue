<script setup>
import TextInput from '../Components/TextInput.vue';
import InputNumber from 'primevue/inputnumber';
import PrimaryButton from '../Components/PrimaryButton.vue';
import Headline from './Headline.vue';
import Footer from './Footer.vue';
import InputError from '../Components/InputError.vue';
import { translate } from '../trans.js';
</script>
<template>
<Headline :headline="headline" />
<form v-bind:action="computedAction" method="POST">
    <input type="hidden" name="_token" v-model="token" />
    <input type="hidden" name="id" v-bind:value="computedId" />
    <div>
        <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
            <table class="table">
                <tbody>
                <tr>
                  <td>{{ translate('attributes.film_identifier') }}</td>
                  <td>
                    <TextInput name="film_identifier" v-model="film.film_identifier" />
                    <InputError class="mt-2" :message="errors.film_identifier" />
                  </td>
                </tr>
                <tr>
                  <td>{{ translate('attributes.name') }}</td>
                  <td>
                    <TextInput name="name" v-model="film.name" />
                    <InputError class="mt-2" :message="errors.name" />
                  </td>
                </tr>
                <tr>
                    <td>{{ translate('attributes.description') }}</td>
                  <td>
                    <textarea cols="30" rows="3" name="description" v-model="film.description"></textarea>
                    <InputError class="mt-2" :message="errors.description" />
                  </td>
                </tr>
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
                  <td>{{ translate('attributes.sources_id') }}</td>
                  <td>
                    <span v-for="filmsource in filmsources">
                        <label><input :checked="isSelected([{id: filmsource.id}], film.filmsources_id)" type="radio" name="filmsources_id" :value="filmsource.id" /> {{filmsource.name}}</label>
                        &nbsp;&nbsp;&nbsp;
                    </span>
                    <InputError class="mt-2" :message="errors.filmsources_id" />
                  </td>
                </tr>
                <tr>
                  <td>{{ translate('attributes.year') }}</td>
                  <td>
                    <InputNumber :inputProps="{name: 'year'}" v-model="film.year" />
                    <InputError class="mt-2" :message="errors.year" />
                  </td>
                </tr>
                <tr>
                  <td>{{ translate('attributes.duration') }}</td>
                  <td>
                    <InputNumber :inputProps="{name: 'duration'}"  v-model="film.duration" />
                    <InputError class="mt-2" :message="errors.duration" />
                  </td>
                </tr>
                <tr><td>&nbsp;</td></tr>
                <tr>
                  <td>{{ translate('attributes.filmstatus_id') }}</td>
                  <td>
                    <span v-for="filmstatus in filmstatus">
                        <label><input :checked="isSelectedStatus(filmstatus.id, film.filmstatus_id)" type="radio" name="filmstatus_id" :value="filmstatus.id" /> {{filmstatus.name}}</label>
                        &nbsp;&nbsp;&nbsp;
                    </span>
                  </td>
                </tr>
                <tr><td>&nbsp;</td></tr>
                <tr>
                  <td>{{ translate('attributes.genre') }}</td>
                  <td>
                    <span v-for="genre in genres">
                        <label><input :checked="isSelectedGenre(genre.id, film.genres)" type="checkbox" name="genres[]" :value="genre.id" /> {{genre.name}}</label>
                        &nbsp;&nbsp;&nbsp;
                    </span>
                  </td>
                </tr>
                </tbody>
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
  props: ['film', 'filmsources', 'genres', 'languages', 'filmstatus', '_token', 'headline', 'errors'],
  methods: {
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
    isSelectedStatus: function (filmLanguages, currentLanguage) {
        return filmLanguages === currentLanguage;
    },
    isSelectedGenre: function (genreId, filmGenres) {
        let result = false;
        filmGenres.every(function (genre) {
            if (genre.id === genreId) {
                result = true;
                return false; // break
            }
            return true; // continue;
        });
        return result;
    }
  },
  computed: {
    token: function () {
      return this._token
    },
    computedAction: function () {
        return '/films/update';
    },
    computedId: function () {
        if (typeof this.film.id === "undefined") return 0;
        return this.film.id;
    }
  }
}
</script>
