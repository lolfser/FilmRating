<script setup>
import TextInput from './TextInput.vue';
import PrimaryButton from './PrimaryButton.vue';
import Headline from './Headline.vue';
import Footer from './Footer.vue';
import InputError from './InputError.vue';
import { translate } from './../trans';

</script>
<template>
<Headline :headline="headline" />
<form v-bind:action="computedAction" method="POST">
    <input type="hidden" name="_token" v-model="token" />
    <input type="hidden" name="id" v-bind:value="computedId" />
    <div>
        <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
            <table class="table">
                <tr>
                  <td>{{ translate('attributes.film_nr') }}</td>
                  <td>
                    <TextInput name="film_nr" v-model="film.film_nr" />
                    <InputError class="mt-2" :message="errors.film_nr" />
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
                    <TextInput name="description" v-model="film.description" />
                    <InputError class="mt-2" :message="errors.description" />
                  </td>
                </tr>
                <tr v-for="(language, type) in languages">
                  <td>Sprache: {{ type }}</td>
                  <td>
                    <span v-for="lang in language" value="a">
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
                    <TextInput name="year" v-model="film.year" />
                    <InputError class="mt-2" :message="errors.year" />
                  </td>
                </tr>
                <tr>
                  <td>{{ translate('attributes.duration') }}</td>
                  <td>
                    <TextInput name="duration" v-model="film.duration" />
                    <InputError class="mt-2" :message="errors.duration" />
                  </td>
                </tr>
                <tr>
                  <td>{{ translate('attributes.filmstatus_id') }}</td>
                  <td>
                    <TextInput name="filmstatus_id" v-model="film.filmstatus_id" />
                    <InputError class="mt-2" :message="errors.filmstatus_id" />
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
  props: ['film', 'filmsources', 'languages', '_token', 'headline', 'errors'],
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
    },
    headline: function() {
        if (typeof this.film.id === "undefined") return 'Film erstellen';
         return 'Film bearbeiten';
    }
  }
}
</script>
