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
        <td style="max-width: 380px">
            {{ film.name }}
            <span v-if="film.description">
                <br><br>
                Beschreibung:
                <textarea cols="30" rows="4" name="description">{{ film.description }}</textarea>
            </span>
            <span v-if="film.description">
                <br><br>
                Stichworte:
                <textarea cols="30" rows="4" name="keywords">{{ keywordsConcat(film.keywords) }}</textarea>
            </span>
        </td>
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
                    <td name="td_filmnotifications" colspan="2">
                        <b>Modifikaitonen: </b>
                        <span v-for="mod in filmModifications">
                            <label><input :checked="isSelected(film.filmmodifications, mod.id)" type="checkbox" :name="'filmModification_' + mod.id" :value="mod.id" /> {{mod.name}}</label>
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
            <textarea name="viewerComment">{{ viewerComment(film) }}</textarea>
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
                <img src="/svgs/rotate.svg"
                     style="height: 15px; cursor: pointer; display: inline"
                     v-on:click="loadFilm($event, film, grades);"
                     title="Globale Daten neu laden"
                 >
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
        'filmModifications',
        '_token'
    ],
    data() {
        return {
            'selectedGenres': []
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
    methods: {
        callbackUpdateGenres: function(genres) {
            let genresResult = [];
            this.film.genres.every(function(genre) {
                genresResult.push(genre.id);
                return true;
            });
            this.selectedGenres = genresResult;
        },
        keywordsConcat: function(keywords) {
            let result = '';
            keywords.every(function(keyw) {
                result += ', ' + keyw.name;
                return true;
            });
            return result.substring(2);
        },
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
            let data = new FormData();
            data.append('filmId', film.id);

            let form = event.target.parentNode;
            data.append('_token', form.querySelector('input[name="_token"]').value);

            function load(url, callBack, eventTarget, film, callbackUpdateGenres, otherGrade) {
                const xhttp=new XMLHttpRequest();
                xhttp.onload = function() {
                    callBack(this, eventTarget, film, callbackUpdateGenres, otherGrade);
                }
                xhttp.open("POST", url);
                xhttp.send(data);
            }

            function loadCallback(xhttp, eventTarget, film, callbackUpdateGenres, otherGrade) {

                let data = "";

                try {
                    data = JSON.parse(xhttp.response);
                } catch (e) {
                    event.target.style.backgroundColor = "red";
                    return;
                }

                film.name = data.name;
                film.genres = data.genres;
                film.languages = data.languages;
                film.description = data.description;
                film.keywords = data.keywords;
                film.filmmodifications = data.filmmodifications;
                film.ratings = data.ratings;

                callbackUpdateGenres(data.genres);
                otherGrade(film);

                event.target.style.backgroundColor = "";
            }

            event.target.style.backgroundColor = "yellow";
            load(
                '/rating/load',
                 loadCallback,
                 event.target,
                 film,
                 this.callbackUpdateGenres,
                 this.otherGrade
            );

            return event;
        },
        loadQuickSaveUrl: function (event, film, grades) {
            let tr = event.target.parentNode.parentNode.parentNode;
            let viewerComment = tr.querySelector('[name="td_comment"] textarea').value;
            tr.querySelector('[name="comment"]').value = viewerComment;
            let gradeInput = tr.querySelector('[name="td_grades"] input.p-autocomplete-input').value;
            grades.every(function(grade) {
                if (grade.value + grade.trend == gradeInput) {
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

            let filmNotifications = tr.querySelectorAll('[name="td_filmnotifications"] :checked');
            filmNotifications.forEach(function(element) {
                data.append(element.name, true);
            });

            let textArea = tr.querySelectorAll('[name="description"], [name="keywords"]');
            textArea.forEach(function(element) {
                data.append(element.name, element.value);
            });

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
