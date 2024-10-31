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
            <strong>{{ film.name }}</strong>
            <br>
            Dauer: {{ parseInt(film.duration / 60) }} min.
            <span class="span_description">
                <br><br>
                Beschreibung:
                <textarea name="description" v-model="film.description"
                    cols="45" rows="3"
                    v-on:change="triggerSave('description', film.id, film.film_identifier)"></textarea>
            </span>
            <span class="span_keywords">
                <br><br>
                Stichworte:
                <textarea name="keywords" :value="keywordsConcat(film.keywords)"
                    cols="45" rows="2"
                    v-on:change="triggerSave('keywords', film.id, film.film_identifier)"></textarea>
            </span>
        </td>
        <td>
            <table>
            <tbody>
                <tr>
                    <td style="padding: 0 0 0 0; border: none;">
                        <table style="width: 100%; margin-bottom: 10px;" class="tb_languages" >
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
                                                   v-on:click="triggerSave('languages', film.id, film.film_identifier)"
                                                />&nbsp;{{lang.language}}
                                            </label>
                                            &nbsp;&nbsp;&nbsp;
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="td_filmnotifications" colspan="2">
                        <b>Modifikaitonen: </b>
                        <label v-for="fmod of filmModifications" style="padding-right: 10px">
                            <Checkbox v-model="selectedModifications"
                                  :name="'filmModification_' + fmod.id"
                                  :value="fmod.id"
                                  v-on:change="triggerSave('modifications', film.id, film.film_identifier)"
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
                            v-on:focusout="triggerSave('genres', film.id, film.film_identifier)"
                        />
                    </td>
                </tr>
                <tr>
                    <td class="td_filmstatus" colspan="2" v-if="film.filmstatus_id !== 0">
                        <b>Status:</b>
                        <MultiSelect :options="filmstatus" :optionLabel="elementName" :optionValue="elementId"
                            placeholder="Status wählen"
                            name="filmstatus"
                            :selectionLimit="1"
                            :autoFilterFocus="true" v-model="selectedFilmstatus"
                            v-on:focusout="triggerSave('filmstatus', film.id, film.film_identifier)"
                        />
                    </td>
                </tr>
            </tbody>
            </table>
        </td>
        <td>
            Noten anderer:
            <div v-html="otherGrade(film)"></div>
            <br>
            <div class="div_grades">
                Deine Note
                <div class="td_grades">
                    <AutoComplete
                        :grades="grades"
                        :selectedValue="selectedGrade"
                        v-on:focusout="triggerSave('grade', film.id, film.film_identifier)"
                    />
                </div>
            </div>
            <div class="td_comment">
                <br>
                <textarea name="viewerComment"
                    rows="4"
                    placeholder="Dein persönlicher Kommentar"
                    v-on:change="triggerSave('comment', film.id, film.film_identifier)">{{ viewerComment(film) }}</textarea>
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
                <input v-for="(lang, type) in languages" type="hidden" :name="'language_' + type" />
                <img src="/svgs/floppy-disk.svg"
                     class="save"
                     style="height: 20px; cursor: pointer; display: inline"
                     v-on:click="loadQuickSaveUrl($event, film, 'all');"
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
            let result = this.viewerGrade();
            return result;
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
        triggerSave: function (autoSaveColumn, filmId, filmIdentifier) {
            let tr = document.getElementsByClassName('film-row-' + filmId)[0];
            if (typeof tr === "undefined") {
                return 1;
            }
            let form = tr.querySelectorAll('.save')[0].parentNode;
            if (typeof tr !== "undefined") {
                this.save(
                    filmIdentifier,
                    filmId,
                    tr,
                    form,
                    autoSaveColumn,
                    this.saveCallBackStartPartial,
                    this.saveCallBackSuccessPartial,
                    this.saveCallBackFailPartial
                )
            }
        },
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
        receiveTarget(filmId, autoSaveColumn) {
            let x = document.getElementsByClassName('film-row-' + filmId)[0];
            let cssClass = '.save';
            switch (autoSaveColumn) {
                case 'languages':
                    cssClass = '.tb_languages';
                    break;
                case 'description':
                    cssClass = '.span_description';
                    break;
                case 'keywords':
                    cssClass = '.span_keywords';
                    break;
                case 'modifications':
                    cssClass = '.td_filmnotifications';
                    break;
                case 'genres':
                    cssClass = '.td_genres';
                    break;
                case 'filmstatus':
                    cssClass = '.td_filmstatus';
                    break;
                case 'grade':
                    cssClass = '.div_grades';
                    break;
                case 'comment':
                    cssClass = '.td_comment';
                    break;
            }
            return x.querySelectorAll(cssClass)[0];
        },
        saveCallBackStartPartial: function(filmId, autoSaveColumn) {
            let x = this.receiveTarget(filmId, autoSaveColumn);
            x.style.backgroundColor = "yellow";
        },
        saveCallBackSuccessPartial: function(filmId, autoSaveColumn) {
            let x = this.receiveTarget(filmId, autoSaveColumn);
            x.style.backgroundColor = "";
        },
        saveCallBackFailPartial: function(filmId, autoSaveColumn) {
            let x = this.receiveTarget(filmId, autoSaveColumn);
            x.style.backgroundColor = "red";
        },
        saveCallBackStart: function(filmId) {
            let x = document.getElementsByClassName('film-row-' + filmId)[0];
            x = x.querySelectorAll('.save')[0];
            x.style.backgroundColor = "yellow";
        },
        saveCallBackSuccess: function(filmId) {
            let x = document.getElementsByClassName('film-row-' + filmId)[0];
            x = x.querySelectorAll('.save')[0];
            x.style.backgroundColor = "";
        },
        saveCallBackFail: function(filmId) {
            let x = document.getElementsByClassName('film-row-' + filmId)[0];
            x = x.querySelectorAll('.save')[0];
            x.style.backgroundColor = "red";
        },
        loadQuickSaveUrl: function (event, film, autoSaveColumn) {
            this.save(
                film.film_identifier,
                film.id,
                event.target.parentNode.parentNode.parentNode,
                event.target.parentNode,
                autoSaveColumn,
                this.saveCallBackStart,
                this.saveCallBackSuccess,
                this.saveCallBackFail
            )
            return event;
        },
        save: function (
            film_identifier,
            film_id,
            tr,
            form,
            autoSaveColumn,
            saveCallBackStart,
            saveCallBackSuccess,
            saveCallBackFail
        ) {
            if (typeof autoSaveColumn === "undefined") {
                autoSaveColumn = "all";
            }

            let data = new FormData();

            data.append('isAjax', true);
            data.append('id', film_identifier);

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

                let languages = tr.querySelectorAll('[name^="' + film_id + '_language"]:checked');
                languages.forEach(function(element) {
                    let language = tr.querySelector('[name="' + element.name.split(film_id + '_')[1] + '"]');
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
                this.grades.every(function(grade) {
                    if (grade.value + grade.trend == gradeInput) {
                        data.append('grades_id', grade.id);
                        return false; // break
                    }
                    return true; // continue
                });
            }

            function update(url, xFunction, film_id, saveCallBackSuccess, saveCallBackFail, autoSaveColumn) {
                const xhttp = new XMLHttpRequest();
                xhttp.onload = function() {
                    xFunction(this, film_id, saveCallBackSuccess, saveCallBackFail, autoSaveColumn);
                }
                xhttp.open("POST", url);
                xhttp.send(data);
            }

            function myFunction(xhttp, film_id, saveCallBackSuccess, saveCallBackFail, autoSaveColumn) {
                if (xhttp.response != "1") {
                    saveCallBackFail(film_id, autoSaveColumn);
                } else {
                    saveCallBackSuccess(film_id, autoSaveColumn);
                }
            }

            let url = tr.querySelector('form').getAttribute('action');
            saveCallBackStart(film_id, autoSaveColumn);
            update(url, myFunction, film_id, saveCallBackSuccess, saveCallBackFail, autoSaveColumn);
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
