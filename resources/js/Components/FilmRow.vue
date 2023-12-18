<script setup>
    import AutoComplete from './PrimeVueAutoComplete.vue';
    import MultiSelect from "@/Components/MultiSelect.vue";
</script>

<template>
    <tr>
        <td>{{ film.film_identifier }}</td>
        <td>{{ film.name }}</td>
        <td>
            <table>
                <tr v-for="(language, type) in languages">
                  <td>{{ type }}</td>
                  <td>
                    <span v-for="lang in language" style="white-space:nowrap">
                        <label><input :checked="isSelected(film.languages, lang.id)" type="radio" :name="film.id + '_language_' + type" :value="lang.id" />&nbsp;{{lang.language}}</label>
                        &nbsp;&nbsp;&nbsp;
                    </span>
                  </td>
                </tr>
            </table>
        </td>
        <td name="td_genres">
              <MultiSelect :options="genres" :optionLabel="genreLabels" :optionValue="genreValues"
                 placeholder="Genre wählen"
                 name="genres"
                 autoFilterFocus v-model="selectedGenres"
                />
        </td>
        <td>{{ otherGrade(film) }}</td>
        <td name="td_grades">
            <AutoComplete :grades="grades" :selectedValue="selectedGrade" />
        </td>
        <td name="td_comment">
            <textarea>{{ viewerComment(film) }}</textarea>
        </td>
        <td>
            <form method="post" action="/rating/update/">
                <span v-html="generateCULink(film)" />&nbsp;
                <input type="hidden" name="_token" :value="_token" />
                <input type="hidden" name="id" v-bind:value="film.film_identifier" />
                <input type="hidden" name="genres" />
                <input type="hidden" name="comment" />
                <input type="hidden" name="grades_id" />
                <input  v-for="(lang, type) in languages" type="hidden" :name="'language_' + type" />
                <img src="/svgs/floppy-disk.svg"
                     style="height: 20px; cursor: pointer; display: inline"
                     v-on:click="loadQuickSaveUrl($event, film, grades);"
                     title="Schnellspeichern"
                     />
            </form>
        </td>
    </tr>
</template>
<script>
export default {
    props: [
        'film',
        'grades',
        'languages',
        'viewerId',
        'ratings',
        'grades',
        'genres',
        '_token'
    ],
    mounted() {
    },
    data() {
        return {
            suggestions: this.grades,
        }
    },
    computed: {
        selectedGrade: function (grade) {
            return this.viewerGrade()
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
        genreValues: function (genre) {
            return genre.id;
        },
        genreLabels: function (genre) {
            return genre.name;
        },
        loadQuickSaveUrl: function (event, film, grades) {
            let tr = event.target.parentNode.parentNode.parentNode;
            let comment = tr.querySelector('[name="td_comment"] textarea').value;
            tr.querySelector('[name="comment"]').value = comment;
            let gradeInput = tr.querySelector('[name="td_grades"] input.p-autocomplete-input').value;
            grades.every(function(grade) {
                if (grade.value + grade.trend == gradeInput) {
                    let gradeInput = tr.querySelector('[name="td_grades"] input.p-autocomplete-input').value;
                    tr.querySelector('[name="grades_id"]').value = grade.id;
                    return false;
                }
                return true;
            });
            let genresInput = tr.querySelector('[name="td_genres"] [name="genres"]').value;
            tr.querySelector('form [name="genres"]').value = genresInput;
            let languages = tr.querySelectorAll('[name^="' + film.id + '_language"]:checked');
            languages.forEach(function(element) {
                let element2 = tr.querySelector('[name="' + element.name.split(film.id + '_')[1] + '"]');
                element2.value = element.value;
            });

            tr.querySelector('form').submit();
            return event;
        },
        dropdownGradeValue: function (grade) {
            return grade.id;
        },
        dropdownGrade: function (grade) {
            return grade.value + "" + grade.trend;
        },
        viewerComment: function (film) {
            const viewerId = this.viewerId
            let returnValue = "";
            this.ratings.every(function (rating) {
                if (rating.viewers_id == viewerId) {
                    returnValue = rating.comment;
                    return false; // break
                }
                return true; // continue;
            });
            return returnValue;
        },
        generateCULink: function (film) {
            const reviewId = this.getReviewId(film);
            return "<a href='/rating/" + film.film_identifier + "/cu'>"
            + (reviewId != 0
                ? "<img src='/svgs/pen.svg' style='height: 20px; cursor: pointer; display: inline' title='bearbeiten'>"
                : "<img src='/svgs/plus.svg' style='height: 20px; cursor: pointer; display: inline' title='neu'>"
              )
            + "</a>";
        },
        getReviewId: function (film) {
            const viewerId = this.viewerId;
            let returnValue = "";
            this.ratings.every(function (rating) {
                if (rating.viewers_id == viewerId) {
                    returnValue = rating.id;
                    return false; // break;
                }
                return true; // continue;
            });
            return returnValue;
        },
        viewerGrade: function () {
            let film = this.film;
            const viewerId = this.viewerId;
            const grades = this.grades;
            let returnValue = "";
            this.ratings.every(function (rating) {
                if (rating.viewers_id != viewerId) {
                    return true; // continue;
                }
                const gradeId = rating.grades_id;
                grades.every(function (gradeFromList) {
                    if (gradeId == gradeFromList.id) {
                        returnValue = gradeFromList.value + "" + gradeFromList.trend;
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
            this.ratings.every(function (rating) {
                if (rating.viewers_id == viewerId) {
                    return true; // continue;
                }
                const gradeId = rating.grades_id;
                grades.every(function (gradeFromList) {
                    if (gradeId == gradeFromList.id) {
                        if (returnValue !== "") returnValue = returnValue + " / ";
                        returnValue += gradeFromList.value + "" + gradeFromList.trend;
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
