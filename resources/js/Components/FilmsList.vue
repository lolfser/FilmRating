<script setup>
import Headline from './Headline.vue';
</script>
<template>
    <Headline :headline="headline" />
    <div>
        <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
            <p class="mt-6 text-gray-500 leading-relaxed">
                <table class="table">
                    <tr>
                      <th>Nr.</th>
                      <th>Name</th>
                      <th>audio / subtitle</th>
                      <th>Genre</th>
                      <th>edit</th>
                    </tr>
                    <tr v-for="film in films">
                        <td>{{film.film_nr}}</td>
                        <td>{{film.name}}</td>
                        <td>{{film.audio_lang}} / {{film.subtitle_lang}}</td>
                        <td>{{film.genre}}</td>
                        <td><a v-bind:href="'/films/'+film.id+'/cu'"> Edit </a></td>
                    </tr>
                </table>
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
</template>
<script>
export default {
  props: [
    'films',
    'headline'
  ],
}
</script>
