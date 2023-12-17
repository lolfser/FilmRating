<script setup>
import Headline from './Headline.vue';
import Footer from './Footer.vue';
</script>
<template>
    <Headline :headline="headline" />
    <div>
        <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
            <p class="mt-6 text-gray-500 leading-relaxed">
                <table class="table">
                    <tr>
                      <th>Film-Identifikator</th>
                      <th>Name</th>
                      <th>Sprache</th>
                      <th>Genre</th>
                      <th>Actions</th>
                    </tr>
                    <tr v-for="film in films">
                        <td>{{film.film_identifier}}</td>
                        <td>{{film.name}}</td>
                        <td>{{calculateLanguage(film)}}</td>
                        <td>{{calculateGenres(film)}}</td>
                        <td>
                            <a v-bind:href="'/films/'+film.id+'/cu'" v-if="PERMISSION_ADD_FILMS"> edit </a>
                        </td>
                    </tr>
                </table>
            </p>
        </div>

        <Footer :PERMISSION_ADD_FILMS="PERMISSION_ADD_FILMS" />

    </div>
</template>
<script>
export default {
  props: [
    'films',
    'headline',
    'PERMISSION_ADD_FILMS'
  ],
  methods: {
      calculateGenres: function (film) {
        let returnValue = "";
        film.genres.every(function (genre) {
            if (returnValue !== '') {
                returnValue += ', ';
            }
            returnValue += genre.name;
            return true; // continue;
        });
        return returnValue;
    },
    calculateLanguage: function(film) {
        let result = '';
        film.languages.every(function(language) {
            if (result !== '') result = result + ' / ';
            result += language.type + ' ' + language.language;
            return true;
        });
        return result;
    }
  }
}
</script>
