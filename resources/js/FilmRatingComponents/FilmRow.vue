<script setup>
    import AutoComplete from './PrimeVueAutoComplete.vue';
    import MultiSelect from "@/FilmRatingComponents/MultiSelect.vue";
    import Checkbox from 'primevue/checkbox';
</script>
<style>
    th, td {
        border: 1px solid black;
        padding: 12px;
    }
    .p-checkbox-box {
        background-color: #DDD;
    }
    .p-highlight {
        background-color: unset;
    }
    .p-highlight .p-checkbox-box {
        background-color: #797;
    }
    .td_filmstatus {
        padding-top: 0;
        padding-bottom: 0;
    }
    .td_genres > div,
    .td_filmstatus > div {
        display: inline-block;
    }
</style>
<template>
    <tr :class="'film-row film-row-' + film.id">
        <td>{{ film.film_identifier }}</td>
        <td style="max-width: 550px">
            {{ film.name }}
            <br>
            Dauer: {{ parseInt(film.duration / 60) }} min.
            <span>
                <br><br>
                Beschreibung:
                <textarea cols="45" rows="3" name="description" v-model="film.description"></textarea>
            </span>
            <span>
                <br><br>
                Stichworte:
                <textarea cols="45" rows="2" name="keywords" :value="keywordsConcat(film.keywords)"></textarea>
            </span>
        </td>
        <td>
            <table>
              <tbody>
                <tr v-for="(language, type) in languages">
                  <td>{{ type }}</td>
                  <td>
                    <span v-for="lang in language">
                        <label style="white-space:nowrap">
                            <input :checked="isSelected(film.languages, lang.id)"
                               type="radio"
                               :name="film.id + '_language_' + type"
                               :value="lang.id"
                               @click="triggerSave()"
                            />&nbsp;{{lang.language}}
                        </label>
                        &nbsp;&nbsp;&nbsp;
                    </span>
                  </td>
                </tr>
                <tr>
                    <td class="td_filmnotifications" colspan="2">
                        <b>Modifikaitonen: </b>
                        <label v-for="fmod of filmModifications" style="padding-right: 10px">
                            <Checkbox v-model="selectedModifications"
                                  :name="'filmModification_' + fmod.id"
                                  :value="fmod.id"
                                  @click="triggerSave()"
                            />
                            {{ fmod.name }}
                        </label>
                    </td>
                </tr>
                <tr>
                    <td class="td_genres" colspan="2">
                        <b>Genre:</b>
                        <MultiSelect :options="genres" :optionLabel="elementName" :optionValue="elementId"
                                 placeholder="Genre wählen"
                                 name="genres"
                                 :autoFilterFocus="true" v-model="selectedGenres"
                        />
                    </td>
                </tr>
                <tr>
                    <td class="td_filmstatus" colspan="2">
                        <b>Status:</b>
                        <MultiSelect :options="filmstatus" :optionLabel="elementName" :optionValue="elementId"
                                    placeholder="Status wählen"
                                    name="filmstatus"
                                    :selectionLimit="1"
                                    :autoFilterFocus="true" v-model="selectedFilmstatus"
                        />
                    </td>
                </tr>
              </tbody>
            </table>
        </td>
        <td>
            Noten anderer:
            <div v-html="otherGrade(film)"></div>
            <br><br>
            Deine Note<br>
            <div class="td_grades">
                <AutoComplete :grades="grades" :selectedValue="selectedGrade" />
            </div>
            <br><br>
            <div class="td_comment">
                <textarea name="viewerComment" rows="4" placeholder="Dein persönlicher Kommentar">{{ viewerComment(film) }}</textarea>
            </div>
        </td>
        <td>
            <form method="post" action="/rating/update/" submit="false">
                <input type="hidden" name="_token" :value="_token" />
                <input type="hidden" name="id" v-bind:value="film.film_identifier" />
                <input type="hidden" name="genres" />
                <input type="hidden" name="filmstatus" />
                <input type="hidden" name="comment" />
                <input type="hidden" name="grades_id" />
                <input  v-for="(lang, type) in languages" type="hidden" :name="'language_' + type" />
                <img src="/svgs/floppy-disk.svg"
                     style="height: 20px; cursor: pointer; display: inline"
                     v-on:click="loadQuickSaveUrl($event, film, grades, 'all');"
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
        'filmstatus',
        'viewerId',
        'ratings',
        'grades',
        'genres',
        'filmModifications',
        'keywords',
        'viewers',
        'user',
        '_token'
    ],
    data() {
        return {
            'selectedGenres': [],
            'selectedModifications': [],
            'selectedFilmstatus': []
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

        if (this.film.filmstatus_id !== null) {
            this.selectedFilmstatus = [this.film.filmstatus_id];
        }

        let modifications = [];
        this.film.filmmodifications.every(function(modification) {
            modifications.push(modification.id);
            return true;
        });
        this.selectedModifications = modifications;
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
        callbackUpdateFilmData: function(film) {
            if (film.filmstatus_id !== null) {
                this.selectedFilmstatus = [film.filmstatus_id];
            }
            let data = [];
            this.film.filmmodifications.every(function(element) {
                data.push(element.id);
                return true;
            });
            this.selectedModifications = data;
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
                if (l.id === currentLanguage) {
                    result = true;
                    return false; // break
                }
                return true; // continue;
            })
            if (typeof this.film.id === "undefined") return 0;
            return result;
        },
        elementId: function (element) {
            return element.id;
        },
        elementName: function (genre) {
            return genre.name;
        },
        loadFilm: function (event, film, grades) {
            let data = new FormData();
            data.append('filmId', film.id);

            let form = event.target.parentNode;
            data.append('_token', form.querySelector('input[name="_token"]').value);

            function load(url, callBack, eventTarget, film, callbackUpdateGenres, otherGrade, callbackUpdateFilmData) {
                const xhttp=new XMLHttpRequest();
                xhttp.onload = function() {
                    callBack(this, eventTarget, film, callbackUpdateGenres, otherGrade, callbackUpdateFilmData);
                }
                xhttp.open("POST", url);
                xhttp.send(data);
            }

            function loadCallback(xhttp, eventTarget, film, callbackUpdateGenres, otherGrade, callbackUpdateFilmData) {

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
                film.filmstatus_id = data.filmstatus_id;

                callbackUpdateFilmData(film);
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
                 this.otherGrade,
                 this.callbackUpdateFilmData
            );

            return event;
        },
        loadQuickSaveUrl: function (event, film, grades, autoSaveColumn) {

            if (typeof autoSaveColumn === "undefined") {
                autoSaveColumn = "all";
            }

            let tr = event.target.parentNode.parentNode.parentNode;
            let form = event.target.parentNode;
            let data = new FormData();

            data.append('isAjax', true);
            data.append('id', film.film_identifier);

            let token = (form.querySelectorAll('[name="_token"]'))[0];
            data.append(token.name, token.value);

            if (autoSaveColumn === "all" || autoSaveColumn === 'modifications') {

                let filmNotifications = tr.querySelectorAll('.td_filmnotifications input');
                filmNotifications.forEach(function (element) {
                    data.append(element.name, element.checked ? 'true' : 'false');
                });

            }

            if (autoSaveColumn === "all" || autoSaveColumn === 'description') {
                let element = tr.querySelectorAll('[name="description"]')[0];
                data.append(element.name, element.value);
            }

            if (autoSaveColumn === "all" || autoSaveColumn === 'keywords') {
                let element = tr.querySelectorAll('[name="keywords"]')[0];
                data.append(element.name, element.value);
            }

            if (autoSaveColumn === "all" || autoSaveColumn === 'genres') {
                let element = tr.querySelectorAll('.td_genres [name="genres"]')[0];
                data.append(element.name, element.value);
            }

            if (autoSaveColumn === "all" || autoSaveColumn === 'filmstatus') {
                let filmstatusInput = tr.querySelector('.td_filmstatus [name="filmstatus"]')?.value;
                data.append('filmstatus', filmstatusInput);
            }

            if (autoSaveColumn === "all" || autoSaveColumn === 'languages') {

                let languages = tr.querySelectorAll('[name^="' + film.id + '_language"]:checked');
                languages.forEach(function(element) {
                    let language = tr.querySelector('[name="' + element.name.split(film.id + '_')[1] + '"]');
                    data.append(language.name, element.value);
                });

            }

            // personal attributes

            if (autoSaveColumn === "all" || autoSaveColumn === 'comment') {
                let viewerComment = tr.querySelector('.td_comment textarea').value;
                data.append('comment', viewerComment);
            }

            if (autoSaveColumn === "all" || autoSaveColumn === 'grade') {
                let gradeInput = tr.querySelector('.td_grades input.p-autocomplete-input').value;
                grades.every(function(grade) {
                    if (grade.value + grade.trend == gradeInput) {
                        data.append('grades_id', grade.id);
                        return false; // break
                    }
                    return true; // continue
                });
            }

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
            let viewersInit = [];
            for (const viewer of this.viewers) {
                viewersInit[viewer.id] = viewer.initials;
            }
            for (const rating of this.ratings) {
                if (rating.viewers_id == viewerId) {
                    continue;
                }
                const gradeId = rating.grades_id;
                const init = viewersInit[rating.viewers_id];
                for (let gradeFromList of grades) {
                    if (gradeId == gradeFromList.id) {
                        if (returnValue !== "") {
                            returnValue = returnValue + " / ";
                        }
                        returnValue += '<span title="' + init + '">' + gradeFromList.value + "" + gradeFromList.trend + "</span>";
                        break;
                    }
                }
            }
            return returnValue;
        },
    }
}
</script>
