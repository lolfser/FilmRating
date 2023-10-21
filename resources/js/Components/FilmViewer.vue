<script setup>
import Headline from './Headline.vue';
import Footer from './Footer.vue';
</script>
<template>
    <Headline :headline="headline" />
    <div>
        <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
            <table class="table">
                <tr>
                  <th>Nr.</th>
                  <th>Name</th>
                  <th>Sprache</th>
                  <th>Genre</th>
                  <th>Wertungen von anderen</th>
                  <th>deine Wertung</th>
                  <th>dein Kommentar</th>
                  <th>Actions</th>
                </tr>
                <tr v-for="film in films">
                    <td>{{film.film_identifier}}</td>
                    <td>{{film.name}}</td>
                    <td>{{calculateLanguage(film)}}</td>
                    <td>{{film.genre}}</td>
                    <td>{{ otherGrade(film) }}</td>
                    <td>{{ viewerGrade(film) }}</td>
                    <td>{{ viewerComment(film) }}</td>
                    <td>
                        <a v-bind:href="'/rating/'+film.id+'/cu'"> edit - demnächst</a>
                    </td>
                </tr>
            </table>
        </div>
        <Footer />
    </div>
</template>
<script>
export default {
  props: [
    'films',
    'grades',
    'viewerId',
    'headline'
  ],
  methods: {
    calculateLanguage: function(film) {
        let result = '';
        film.languages.every(function(language) {
            if (result !== '') result = result + ' / ';
            result += language.type + ' ' + language.language;
            return true;
        });
        return result;
    },
    viewerComment: function (film) {
      const viewerId = this.viewerId
      let returnValue = "";
      film.viewers.every(function (viewer) {
        if (viewer.id == viewerId) {
            returnValue = viewer.pivot.comment;
            return false; // break
        }
        return true; // continue;
      });
      return returnValue;
    },
    viewerGrade: function (film) {
        const viewerId = this.viewerId;
        const grades = this.grades;
        let returnValue = "";
        film.viewers.every(function (viewer) {
            if (viewer.id != viewerId) {
                return true; // continue;
            }
            const gradeId = viewer.pivot.grades_id;
            grades.every(function (gradeFromList) {
                if (gradeId == gradeFromList.id) {
                    returnValue = gradeFromList.value + "" +  gradeFromList.trend;
                    return false; // break;
                }
                return true; // continue;
            });
            if (returnValue != "") {
                return false; // break;
            }
            return true; // continue;
        });
        return returnValue;
    },
    otherGrade: function (film) {
        const viewerId = this.viewerId;
        const grades = this.grades;
        let returnValue = "";

        film.viewers.every(function (viewer) {
            if (viewer.id == viewerId) {
                return true; // continue;
            }

            const gradeId = viewer.pivot.grades_id;
            grades.every(function (gradeFromList) {

                if (gradeId == gradeFromList.id) {
                    if (returnValue !== "") returnValue = returnValue + " / ";
                    returnValue += gradeFromList.value + "" +  gradeFromList.trend;
                    return false; // break;
                }
                return true; // break;
            });
            return true; // continue;
        });
        return returnValue;
    },
  }
}
</script>
