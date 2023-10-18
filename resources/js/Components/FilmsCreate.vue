<script setup>
import TextInput from './TextInput.vue';
import PrimaryButton from './PrimaryButton.vue';
import Headline from './Headline.vue';
</script>
<template>
<Headline :headline="headline" />
<form v-bind:action="computedAction" method="POST">
    <input type="hidden" name="_token" v-model="token" />
    <input type="hidden" name="id" v-model="film.id" />
    <div>
        <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
            // Fehlebehandlung funktioniert noch nicht<br>
            // Dropdowns für attribute<br>
            <p class="mt-6 text-gray-500 leading-relaxed">
                <table class="table">
                    <tr>
                      <td>Nr.</td>
                      <td><TextInput name="film_nr" v-model="film.film_nr" /></td>
                    </tr>
                    <tr>
                      <td>Name</td>
                      <td><TextInput name="name" v-model="film.name" /></td>
                    </tr>
                    <tr>
                      <td>Beschreibung</td>
                      <td><TextInput name="description" v-model="film.description" /></td>
                    </tr>
                    <tr>
                      <td>Audio</td>
                      <td><TextInput name="audio_lang" v-model="film.audio_lang" /></td>
                    </tr>
                    <tr>
                      <td>Subtitle</td>
                      <td><TextInput name="subtitle_lang" v-model="film.subtitle_lang" /></td>
                    </tr>
                    <tr>
                      <td>sources_id</td>
                      <td><TextInput name="sources_id" v-model="film.sources_id" /></td>
                    </tr>
                    <tr>
                      <td>year</td>
                      <td><TextInput name="year" v-model="film.year" /></td>
                    </tr>
                    <tr>
                      <td>Dauer (in sek)</td>
                      <td><TextInput name="duration" v-model="film.duration" /></td>
                    </tr>
                    <tr>
                      <td>Filmstatus</td>
                      <td><TextInput name="filmstatus_id" v-model="film.filmstatus_id" /></td>
                    </tr>
                </table>
                <br>
                <PrimaryButton>Speichern</PrimaryButton>
            </p>
        </div>

        <div class="bg-gray-200 bg-opacity-25 grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 p-6 lg:p-8">
            <div>
                <p class="mt-4 text-gray-500 text-sm leading-relaxed">
                    <a href="/dashboard" class="inline-flex items-center font-semibold text-indigo-700">
                        Zurück zum Dashboard
                    </a>
                </p>
            </div>
        </div>

    </div>
</form>
</template>
<script>
export default {
  props: ['film', '_token', 'headline'],
  computed: {
    token: function () {
      return this._token
    },
    computedAction: function () {
        if (typeof this.film.id === "undefined") return '/films/';
        return '/films/' + this.film.id;
    },
  }
}
</script>
