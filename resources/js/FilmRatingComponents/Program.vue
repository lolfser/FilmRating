<script setup>
import Headline from './Headline.vue';
import Footer from './Footer.vue';
import MultiSelectTemplate from './MultiSelectTemplate.vue';
import ProgramDragableContent from './ProgramDragableContent.vue';
import draggable from "vuedraggable"
</script>
<template>
    <Headline :headline="headline" />
    <div style="display: flex;">
        <input type="button" :onClick="filterFunction" value="Filtern" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" />
        <MultiSelectTemplate
            name="year"
            :options="years"
            :selected-options="filter.years"
            placeholder="Nach Jahr filtern"
        />
        <MultiSelectTemplate
            name="filmstatus"
            :options="filmstatus"
            :selected-options="filter.filmstatus"
            placeholder="Nach Filmstatus filtern"
        />
        <MultiSelectTemplate
            name="genres"
            :options="genres"
            :selected-options="filter.genres"
            placeholder="Nach Genres filtern"
        />
        <MultiSelectTemplate
            name="keywords"
            :options="keywords"
            :selected-options="filter.keywords"
            placeholder="Nach Stichwörtern filtern"
        />
        <MultiSelectTemplate
            name="filmmodifications"
            :options="filmmodifications"
            :selected-options="filter.filmmodifications"
            placeholder="Nach Modifikationen filtern!"
        />
        <label><input type="text" name="title_description" placeholder="Nr. / Namen / Beschreibung filtern" :value="selectedTitleDescription"/></label>
        &nbsp;&nbsp;&nbsp;
        <label><input type="checkbox" :checked="onlyNotSet" name="only_not_set"> nur Filme, die noch in keinem Programm sind</label>
    </div>
    <br>
    <div style="height: 85vh; width: 25%; overflow-y:scroll; display: inline-block;">
        <div>
            <div>
                <span>Verfügbare Filme</span>
            </div>
            <div>
                  <draggable
                    class="dragArea list-group"
                    :list="availableFilms"
                    group="filmgroup"
                    @change="log"
                    item-key="id"
                    style="border:1px solid black;"
                  >
                    <template #item="{ element }">
                      <div class="list-group-item">
                          <ProgramDragableContent :element="element"/>
                      </div>
                    </template>
                  </draggable>
            </div>
        </div>
    </div>
    <div style="height: 85vh; width: 73%; overflow-y:scroll; display: inline-block;">
        <div style="
            overflow-x: scroll;
            overflow-y: hidden;
            left: 30%;
            height: 100%;
        ">
            <div style="display: flex; height: 100%">
                <div style="
                    display:flex;
                    flex-direction: column;
                    border:1px solid black;
                    min-width: 500px;
                    padding: 5px;" v-for="block in programmetas" :key="block.id"
                >
                    <div>
                        Start: {{ block.day?.dateString }} ({{ block.location.name }})
                    </div>
                    <div v-if="block.puffer_per_item">
                        Puffer pro Film: {{ block.puffer_per_item }}
                        <span v-if="block.puffer_per_item === 1"> Minute;</span>
                        <span v-else> Minuten;</span>
                        Länge: <span v-html="receiveBlockLength(block.id, block.puffer_per_item, block.total_length)"></span>
                    </div>
                    <div>
                        <img src="/svgs/floppy-disk.svg" style="height: 15px; cursor: pointer; display: inline;" title="Liste speichern" alt="Liste speichern"
                             v-on:click="saveProgramBlock($event, block.id);"
                        >
                        &nbsp;&nbsp;&nbsp;
                        <img src="/svgs/rotate.svg" style="height: 15px; cursor: pointer; display: inline;" title="Liste neu laden" alt="Liste neu laden"
                             v-on:click="loadProgramBlock($event, block.id);"
                        >
                        &nbsp;&nbsp;&nbsp;
                        <span v-html="generatePrintLink(block.day?.id)" />&nbsp;
                        &nbsp;&nbsp;&nbsp;
                        <span v-html="generateCsvLink(block.day?.id)" />&nbsp;
                        &nbsp;&nbsp;&nbsp;
                        <div style="display: inline-block" title="Um ein Element zu löschen, dieses per drag & drop hier drauf ziehen">
                            <draggable
                                class="dragArea list-group"
                                :group="{ name: 'trash', pull: false, put: true }"
                                item-key="id"
                                style="min-height: 15px; min-width: 15px; max-height: 15px; max-width: 15px;
                                background-image: url('/svgs/trash-can-regular.svg');
                                background-repeat: no-repeat;
                                background-size:  contain;
                                cursor:pointer;
                                "
                            >
                                <template #item="{ element }"></template>
                            </draggable>
                        </div>
                    </div>
                    <div>
                        <draggable
                            class="dragArea list-group"
                            :list="getList(block.id)"
                            group="filmgroup"
                            @change="log"
                            item-key="id"
                            style="border:1px solid black; min-height: 60px; min-width: 60px; overflow-y: scroll; height: 70vh"
                        >
                            <template #item="{ element }">
                                <div class="list-group-item" style="display: flex; justify-content: space-between">
                                    <div>
                                          <ProgramDragableContent :element="element"/>
                                    </div>
                                </div>
                            </template>
                        </draggable>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <Footer :footerLinks="footerLinks" />
</template>
<script>

export default {
    props: [
        'films', 'programmetas', 'footerLinks', '_token', 'years', 'filmstatus', 'headline', 'filter', 'keywords', 'genres', 'filmmodifications'
    ],
    components: {
        draggable
    },
    data() {
        return {
            availableFilms: this.prepareAvailableFilms(this.films),
            'lists': this.receiveLists(),
            selectedTitleDescription: this.filter.title_description,
            onlyNotSet: this.filter.only_not_set
        };
    },
    methods: {
        generatePrintLink(id) {
            return '<a href="/print/' + id + '" target="blank">' +
                '<img src="/svgs/print.svg" style="height: 15px; cursor: pointer; display: inline;" title="Druckansicht" alt="Druckansicht" />'+
                '</a>';
        },
        generateCsvLink(id) {
            return '<a href="/csv/' + id + '" target="blank">' +
                '<img src="/svgs/csv.svg" style="height: 15px; cursor: pointer; display: inline;" title="CSV-Export" alt="CSV-Export" />'+
                '</a>';
        },
        prepareAvailableFilms(films) {
            for (const film of films) {
                film.filmstatusName = this.receiveFilmStatusName(film.filmstatus.id)
            }
            return films;
        },
        getList: function(id) {
            if (id === 0) {
                return this.availableFilms
            }
            return this.lists[id];
        },
        generateDragableElement(film) {
            return {
                id: film.id,
                name: film.name,
                film_identifier: film.film_identifier,
                description: film.description,
                duration: film.duration,
                languages: film.languages,
                genres: film.genres,
                filmmodifications: film.filmmodifications,
                filmstatusName: this.receiveFilmStatusName(film.filmstatus.id)
            }
        },
        receiveFilmStatusName(id) {
            for (const status of this.filmstatus) {
                if (status.id === id) {
                    return status.name;
                }
            }
            return 'unbekannter Status';
        },
        receiveLists: function() {
            let ret = {};

            for (const meta of this.programmetas) {
                const listName = meta.id;
                ret[listName] = [];

                if (typeof meta.films !== "undefined") {
                    for (const film of meta.films) {
                        ret[listName].push(this.generateDragableElement(film));
                    }
                }
            }
            return ret;
        },
        log: function(evt) {
            // window.console.log(evt);
        },
        saveProgramBlock: function (event, programmblockId) {
            let data = new FormData();
            data.append('_token', this.$props._token);
            data.append('programmblockId', programmblockId);

            let list = this.getList(programmblockId);
            let d = [];

            for (const k in list) {
                let item = list[k];
                d.push(item.film_identifier)
            }

            data.append('films', d);

            function save(url, callBack, eventTarget, data, programblockId) {
                const xhttp=new XMLHttpRequest();
                xhttp.onload = function() {
                    callBack(event, this, eventTarget, data, programblockId);
                }
                xhttp.open("POST", url);
                xhttp.send(data);
            }

            event.target.style.backgroundColor = "yellow";
            save(
                '/program/save',
                 this.saveCallback,
                 event.target,
                 data,
                 programmblockId
            );

            return event;
        },
        saveCallback: function(event, xhttp, eventTarget, data, programblockId) {
            try {
                data = JSON.parse(xhttp.response);
                this.reloadListFromData(data, programblockId);
            } catch (e) {
                console.log(e.message)
                event.target.style.backgroundColor = "red";
                return;
            }
            event.target.style.backgroundColor = "";
        },
        loadProgramBlock: function (event, programmblockId) {
            let data = new FormData();
            data.append('_token', this.$props._token);
            data.append('programmblockId', programmblockId);

            let list = this.getList(programmblockId);

            function load(url, callBack, eventTarget, data, list, programmblockId, event) {
                const xhttp=new XMLHttpRequest();
                xhttp.onload = function() {
                    callBack(this, eventTarget, list, programmblockId, event);
                }
                xhttp.open("POST", url);
                xhttp.send(data);
            }

            event.target.style.backgroundColor = "yellow";
            load(
                '/program/load',
                 this.loadCallback,
                 event.target,
                 data,
                 list,
                 programmblockId,
                 event
            );

            return event;
        },
        loadCallback: function (xhttp, eventTarget, list, programmblockId, event) {
            try {
                let data = JSON.parse(xhttp.response);
                this.reloadListFromData(data, programmblockId);
            } catch (e) {
                console.log(e);
                event.target.style.backgroundColor = "red";
                return;
            }

            event.target.style.backgroundColor = "";
        },
        reloadListFromData: function (data, programmblockId) {
            this.lists[programmblockId] = [];
            for (const x in data) {
                const film = data[x];
                this.lists[programmblockId].push(this.generateDragableElement(film));
            }
        },
        receiveLength: function(metaId, pufferPerItem) {
            const puffer = parseInt(pufferPerItem === null ? 0 : pufferPerItem) * 60;
            let filmLength = 0;
            for (const film of this.lists[metaId]) {
                filmLength += parseInt(film.duration);
            }
            const totalLength = this.receiveDurationFromSecs(filmLength + (this.lists[metaId].length * puffer));
            return parseInt(totalLength + "");
        },
        receiveDuration: function(film) {
            return this.receiveDurationFromSecs(film.duration);
        },
        receiveDurationFromSecs: function(seconds) {
            return (seconds / 60).toFixed(2);
        },
        receiveBlockLength: function(metaId, puffer_per_item, totalLength) {
            const currentLength = this.receiveLength(metaId, puffer_per_item);
            const className = totalLength < currentLength
                ? 'red'
                : (totalLength * 0.9 < currentLength ? 'green' : '')
            return "<span class='" + className + "'>"
                    + currentLength + " / " + totalLength + " Minuten"
                 + "</span>";
        },
        getElementId: function (element) {
            return element.id;
        },
        getElementName: function (element) {
            return element.name;
        },
        filterFunction: function (event) {
            let data = new FormData();
            let filmStatus = [];
            document.querySelectorAll('.checkbox-option-filmstatus:checked').forEach(function(element) {
                filmStatus.push(element.value);
            });
            data.append('filmstatus', filmStatus.join(','));
            let keywords = [];
            document.querySelectorAll('.checkbox-option-keywords:checked').forEach(function(element) {
                keywords.push(element.value);
            });
            data.append('keywords', keywords.join(','));
            let years = [];
            document.querySelectorAll('.checkbox-option-year:checked').forEach(function(element) {
                years.push(element.value);
            });
            data.append('years', years.join(','));
            let genres = [];
            document.querySelectorAll('.checkbox-option-genres:checked').forEach(function(element) {
                genres.push(element.value);
            });
            data.append('genres', genres.join(','));
            let filmmodifications = [];
            document.querySelectorAll('.checkbox-option-filmmodifications:checked').forEach(function(element) {
                filmmodifications.push(element.value);
            });
            data.append('filmmodifications', filmmodifications.join(','));
            data.append('only_not_set', document.getElementsByName('only_not_set')[0].checked);
            this.selectedTitleDescription = document.getElementsByName('title_description')[0].value;
            data.append('title_description', this.selectedTitleDescription);

            function filterRequest(url, callBack, eventTarget, data, event, _token) {
                let tokenData = new FormData();
                tokenData.append('_token', _token);

                const xhttp=new XMLHttpRequest();

                xhttp.onload = function() {
                    callBack(this, eventTarget, event);
                }

                const queryString = new URLSearchParams(data).toString()
                xhttp.open("POST", url + '?' +queryString);
                window.history.replaceState(
                    {},
                    '',
                    window.location.origin + window.location.pathname + '?' + queryString
                );
                xhttp.send(tokenData);
            }

            event.target.style.backgroundColor = "yellow";
            filterRequest(
                '/program/filter',
                this.filterCallback,
                event.target,
                data,
                event,
                this.$props._token
            );

            return event;
        },
        filterCallback: function (xhttp, eventTarget, event) {

            try {
                this.availableFilms = this.prepareAvailableFilms(JSON.parse(xhttp.response));
            } catch (e) {
                console.log(e);
                event.target.style.backgroundColor = "red";
                return;
            }
            event.target.style.backgroundColor = "";
        }
    }
}
</script>
<style>
    .green {
        background-color: green;
    }
    .red {
        background-color: red;
    }
    .list-group-item:nth-child(2n +2) {
        background-color: lightgray;
    }
    .list-group-item:nth-child(2n +1) {
        background-color: peachpuff;
    }
</style>
