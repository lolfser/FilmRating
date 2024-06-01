<script setup>
import Headline from './Headline.vue';
import Footer from './Footer.vue';
import draggable from "vuedraggable"
</script>
<template>
    <Headline headline="Programm" />
    <div style="height: 85vh; width: 25%; overflow-y:scroll; display: inline-block;">
        <div>
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
                    style="border:1px solid black;"
                  >
                    <template #item="{ element }">
                      <div class="list-group-item">
                          <span :title="receiveTitle(element.description)">
                              {{ element.film_identifier }}: {{ element.name }} {{defineAudioString(element)}} {{defineGenreString(element)}} ({{ receiveDuration(element) }}min.)
                          </span>
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
                    min-width: 400px;
                    padding: 5px;" v-for="block in programmetas" :key="block.id"
                >
                    <div>
                        Start: {{ block.start }} ({{ block.location.name }})
                        Länge: <span v-html="receiveBlockLength(block.id, block.puffer_per_item, block.total_length)"></span>
                    </div>
                    <div v-if="block.puffer_per_item">
                        Puffer pro Film: {{ block.puffer_per_item }}
                        <span v-if="block.puffer_per_item === 1"> Minute</span>
                        <span v-else> Minuten</span>
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
                                        <span :title="receiveTitle(element.description)">
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
                const listName = meta.id;
                ret[listName] = [];

                if (typeof meta.films !== "undefined") {
                    for (const film of meta.films) {

                        const audio = this.defineAudioString(film);
                        const genres = this.defineGenreString(film);

                        ret[listName].push(
                            {
                                id: idGlobal++,
                                name: film.name + audio + genres + " (" + this.receiveDuration(film) + "min.)",
                                filmIdentifier: film.film_identifier,
                                description: film.description,
                                duration: film.duration
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
            const film = this.films[id - 1];
            return {
                id: idGlobal++,
                name: film.name + this.defineAudioString(film) + this.defineGenreString(film) + " (" + this.receiveDuration(film) + "min.)",
                filmIdentifier: film.film_identifier,
                description: film.description,
                duration: film.duration
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
            this.lists[programmblockId] = [];
            for (const x in data) {
                const film = data[x];
                this.lists[programmblockId].push(
                    {
                        name: film.name + this.defineAudioString(film) + this.defineGenreString(film) + " (" + this.receiveDuration(film) + "min.)",
                        filmIdentifier: film.film_identifier,
                        description: film.description,
                        duration: film.duration
                    }
                );
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
        receiveTitle(description){
            console.log(description)
            return description == '' ? 'keine Beschreibung' : description
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
