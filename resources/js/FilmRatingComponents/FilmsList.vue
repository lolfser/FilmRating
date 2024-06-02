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
                      <th>Genre</th>
                      <th>Actions</th>
                    </tr>
                    <tr v-for="film in films">
                        <td>{{film.film_identifier}}</td>
                        <td>
                            {{film.name}}
                            <br>{{film.description}}
                        </td>
                        <td>{{calculateGenres(film)}}</td>
                        <td>
                            <span v-for="userAction in film.userActions">
                                <a :href="userAction.href">
                                    <img :src="userAction.icon" style='height: 15px; cursor: pointer; display: inline' :title='userAction.title'>
                                </a>
                            </span>
                        </td>
                    </tr>
                </table>
            </p>
        </div>
        <Footer :footerLinks="footerLinks" />
    </div>
</template>
<script>
export default {
  props: [
    'films',
    'headline',
    'footerLinks'
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
    }
  }
}
</script>
