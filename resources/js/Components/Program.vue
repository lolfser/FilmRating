<script setup>
import Headline from './Headline.vue';
import Footer from './Footer.vue';
import draggable from "vuedraggable"
</script>
<template>
    <Headline :headline="headline" />
    <div class="row" style="display: flex">
        <div>
            <div style="display:inline-block; min-width: 20%;">
                <div>
                    <span>Verfügbare Filme</span>
                </div>
                <div>
                      <draggable
                        class="dragArea list-group"
                        :list="availableFilms"
                        :group="{ name: 'filmgroup', pull: 'clone', put: false }"
                        :clone="cloneFilm"
                        @change="log"
                        item-key="element"
                        style="border:1px solid black; min-height: 60px; min-width: 60px;"
                      >
                        <template #item="{ element }">
                          <div class="list-group-item">
                              <span :title="element.description">
                                  {{ element.film_identifier }}: {{ element.name }} {{defineAudioString(element)}} {{defineGenreString(element)}}
                              </span>
                          </div>
                        </template>
                      </draggable>
                </div>
            </div>
        </div>
        <div>&nbsp;&nbsp;&nbsp;</div>
        <div style="
            white-space: nowrap;
            overflow-x: scroll;
            overflow-y: hidden;
            max-width: 78%;
        ">
            <div style="display: flex">
                <div style="
                    display:inline-block;
                    border:1px solid black;
                    min-width: 400px;
                    padding: 5px;" v-for="block in programmetas" :key="block.id"
                >
                    <div>Start: {{ block.start }} ({{block.location.name}})</div>
                    <div>
                        <img src="/svgs/floppy-disk.svg" style="height: 15px; cursor: pointer; display: inline;" title="Liste speichern" alt="Liste speichern"
                             v-on:click="saveProgramBlock($event, block.id);"
                        >
                        &nbsp;&nbsp;&nbsp;
                        <img src="/svgs/rotate.svg" style="height: 15px; cursor: pointer; display: inline;" title="Liste neu laden" alt="Liste neu laden"
                            v-on:click="loadProgramBlock($event, block.id);"
                        >
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
                            style="border:1px solid black; min-height: 60px; min-width: 60px;"
                        >
                            <template #item="{ element }">
                                <div class="list-group-item" style="display: flex; justify-content: space-between">
                                    <div>
                                        <span :title="element.description">
                                            {{ element.filmIdentifier }}: {{ element.name }}
                                        </span>
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

let idGlobal = 0;
export default {
    props: [
        'films', 'programmetas', 'footerLinks', '_token'
    ],
    computed: {
        headline: function () {return "Programm";},
    },
    order: 3,
    components: {
        draggable
    },
    data() {
        return {
            availableFilms: this.films,
            'lists': this.receiveLists()
        };
    },
    methods: {
        getList: function(id) {
            return this.lists[id];
        },
        defineAudioString: function (film) {
            let audioString = '';
            let subtitleString = '';
            let lang1 = film.languages[0]?.language;
            let type1 = film.languages[0]?.type;
            let lang2 = film.languages[1]?.language;
            let type2 = film.languages[1]?.type;
            if (typeof lang1 !== "undefined") {
                if (type1 === 'audio') {
                    audioString = lang1.toUpperCase();
                } else {
                    subtitleString = lang1.toLowerCase();
                }
            }
            if (typeof lang2 !== "undefined") {
                if (type2 === 'audio') {
                    audioString = lang2.toUpperCase();
                } else {
                    subtitleString = lang2.toLowerCase();
                }
            }
            if (audioString !== "" || subtitleString !== "") {
                if (subtitleString !== "") {
                    return " (" + audioString + "_" + subtitleString + ")";
                } else {
                    return " (" + audioString + ")";
                }
            }
            return ''
        },
        defineGenreString: function (film) {
            let genreString = '';
            for (let genre of film.genres) {
                if (genreString !== '') {
                    genreString += ' ';
                }
                genreString += genre.name;
            }
            if (genreString !== '') {
                return ' (' + genreString + ')';
            }
            return ''
        },
        receiveLists: function() {
            let ret = {};

            for (const meta of this.programmetas) {
                let listName = meta.id;
                ret[listName] = [];

                if (typeof meta.films !== "undefined") {
                    for (const film of meta.films) {

                        let audio = this.defineAudioString(film);
                        let genres = this.defineGenreString(film);

                        ret[listName].push(
                            {
                                id: idGlobal++,
                                name: film.name + audio + genres,
                                filmIdentifier: film.film_identifier,
                                description: film.description
                            }
                        );
                    }
                }
            }
            return ret;
        },
        log: function(evt) {
            // window.console.log(evt);
        },
        cloneFilm({ id }) {
            return {
                id: idGlobal++,
                name: `${this.films[id - 1].name}`,
                filmIdentifier: `${this.films[id - 1].film_identifier}`,
                description: `${this.films[id - 1].description}`
            };
        },
        saveProgramBlock: function (event, programmblockId) {
            let data = new FormData();
            data.append('_token', this.$props._token);
            data.append('programmblockId', programmblockId);

            let list = this.getList(programmblockId);
            let d = [];

            for (const k in list) {
                let item = list[k];
                d.push(item.filmIdentifier)
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
                    callBack(this, eventTarget, data, list, programmblockId, event);
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
        loadCallback: function (xhttp, eventTarget, data2, list, programmblockId, event) {
            let data = data2;

            try {
                data = JSON.parse(xhttp.response);
                this.reloadListFromData(data, programmblockId);
            } catch (e) {
                console.log(e);
                event.target.style.backgroundColor = "red";
                return;
            }

            event.target.style.backgroundColor = "";
        },
        reloadListFromData: function (data, programmblockId) {
            console.log()
            this.lists[programmblockId] = [];
            for (let x in data) {
                const film = data[x];
                this.lists[programmblockId].push(
                    {
                        name: film.name + this.defineAudioString(film) + this.defineGenreString(film),
                        filmIdentifier: film.film_identifier,
                        description: film.description
                    }
                );
            }
        }
    }
}
</script>
