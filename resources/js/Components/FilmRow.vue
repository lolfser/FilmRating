<script setup>
    import AutoComplete from './PrimeVueAutoComplete.vue';
    import MultiSelect from "@/Components/MultiSelect.vue";
</script>
<style>
    th, td {
        border: 1px solid black;
        padding: 12px;
    }
</style>
<template>
    <tr>
        <td>{{ film.film_identifier }}</td>
        <td>{{ film.name }}</td>
        <td>
            <table>
                <tr v-for="(language, type) in languages">
                  <td>{{ type }}</td>
                  <td>
                    <span v-for="lang in language">
                        <label style="white-space:nowrap">
                            <input :checked="isSelected(film.languages, lang.id)"
                               type="radio"
                               :name="film.id + '_language_' + type"
                               :value="lang.id"
                            />&nbsp;{{lang.language}}
                        </label>
                        &nbsp;&nbsp;&nbsp;
                    </span>
                  </td>
                </tr>
                <tr>
                    <td name="td_genres" colspan="2">
                        <b>Genre:</b>
                        <MultiSelect :options="genres" :optionLabel="genreLabels" :optionValue="genreValues"
                             placeholder="Genre wählen"
                             name="genres"
                             autoFilterFocus v-model="selectedGenres"
                        />
                    </td>
                </tr>
            </table>
        </td>
        <td>{{ otherGrade(film) }}</td>
        <td name="td_grades">
            <AutoComplete :grades="grades" :selectedValue="selectedGrade" />
        </td>
        <td name="td_comment">
            <textarea>{{ viewerComment(film) }}</textarea>
        </td>
        <td>
            <form method="post" action="/rating/update/" submit="false">
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
                <br><br>
                <span v-html="generateCULink(film)" />&nbsp;
                <!--img src="/svgs/rotate.svg"
                     style="height: 15px; cursor: pointer; display: inline"
                     v-on:click="loadFilm($event, film, grades);"
                     title="Daten neu laden"
                 /-->
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
        loadFilm: function (event, film, grades) {
            return;
            let data = new FormData();
            data.append('filmId', film.id);

            let form = event.target.parentNode;
            data.append('_token', form.querySelector('input[name="_token"]').value);

            function load(url, callBack, eventTarget) {
                const xhttp=new XMLHttpRequest();
                xhttp.onload = function() {
                    callBack(this, eventTarget);
                }
                xhttp.open("POST", url);
                xhttp.send(data);
            }

            function loadCallback(xhttp, eventTarget) {

                console.log(xhttp);
                if (xhttp.response != "1") {
                    event.target.style.backgroundColor = "red";
                } else {
                    event.target.style.backgroundColor = "";
                }
            }

            event.target.style.backgroundColor = "yellow";
            load('/rating/load', loadCallback, event.target);

            return event;
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

            let form = event.target.parentNode;
            let data = new FormData();
            data.append('isAjax', true);

            (form.querySelectorAll('input')).forEach(function(input) {
                data.append(input.getAttribute('name'), input.value);
            });

            function update(url, xFunction, eventTarget) {
                const xhttp=new XMLHttpRequest();
                xhttp.onload = function() {
                    xFunction(this, eventTarget);
                }
                xhttp.open("POST", url);
                xhttp.send(data);
            }

            function myFunction(xhttp, eventTarget) {
                if (xhttp.response != "1") {
                    event.target.style.backgroundColor = "red";
                } else {
                    event.target.style.backgroundColor = "";
                }
            }

            let url = tr.querySelector('form').getAttribute('action');
            event.target.style.backgroundColor = "yellow";
            update(url, myFunction, event.target);

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
                ? "<img src='/svgs/pen.svg' style='height: 15px; cursor: pointer; display: inline' title='bearbeiten'>"
                : "<img src='/svgs/plus.svg' style='height: 15px; cursor: pointer; display: inline' title='neu'>"
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
