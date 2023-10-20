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
            // Dropdowns für attribute<br>
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
                <tr>
                  <td>{{ translate('attributes.audio_lang') }}</td>
                  <td>
                    <TextInput name="audio_lang" v-model="film.audio_lang" />
                    <InputError class="mt-2" :message="errors.audio_lang" />
                  </td>
                </tr>
                <tr>
                  <td>{{ translate('attributes.subtitle_lang') }}</td>
                  <td>
                    <TextInput name="subtitle_lang" v-model="film.subtitle_lang" />
                    <InputError class="mt-2" :message="errors.subtitle_lang" />
                  </td>
                </tr>
                <tr>
                  <td>{{ translate('attributes.sources_id') }}</td>
                  <td>
                      <select name="sources_id">
                          <option v-for="filmsource in filmsources" :value="filmsource.id">{{ filmsource.name }}</option>
                      </select>
                    <InputError class="mt-2" :message="errors.sources_id" />
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
  props: ['film', 'filmsources', '_token', 'headline', 'errors'],
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
