<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import draggable from "vuedraggable"
</script>

<template>
    <AppLayout title="Filme">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Filme
            </h2>
        </template>
        <div class="row">
            <div style="display:inline-block">
                <table style="display:inline-block; min-width: 400px">
                    <tr>
                        <td>
                            <span>Verfügbare Filme</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                          <draggable
                            class="dragArea list-group"
                            :list="availableFilms"
                            :group="{ name: 'filmgroup', pull: 'clone', put: false }"
                            :clone="cloneFilm"
                            @change="log"
                            item-key="element"
                            style="border:1px solid black; min-height: 60px; min-width: 60px; display: inline-block"
                          >
                            <template #item="{ element }">
                              <div class="list-group-item">
                                {{ element.film_identifier }}: {{ element.name }}
                              </div>
                            </template>
                          </draggable>
                        </td>
                    </tr>
                </table>
            </div>
            <div style="
                display: inline-block;
                white-space: nowrap;
                overflow-x: scroll;
                overflow-y: hidden;
                max-width: 60%;
                position: absolute;
            ">
                <div style="display: flex">
                    <table style="
                        display:inline-block;
                        border:1px solid black;
                        min-width: 400px;
                        padding: 5px;" v-for="block in programmetas" :key="key">
                        <tr>
                            <td>
                                <span>Start: {{ block.start }} ({{block.location.name}})</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <!-- {{ block.films }} -->
                            </td>
                        </tr>
                        <tr>
                            <td>
                                &nbsp;
                                <img src="/svgs/floppy-disk.svg" style="height: 15px; cursor: pointer; display: inline;" title="speichern" alt="speichern"
                                     v-on:click="saveProgramBlock($event, block.id);"
                                >
                                &nbsp;&nbsp;&nbsp;
                                <img src="/svgs/rotate.svg" style="height: 15px; cursor: pointer; display: inline;" title="reload" alt="reload"
                                    v-on:click="loadProgramBlock($event, block.id);"
                                >
                            </td>
                        </tr>
                        <tr>
                            <td>
                              <draggable
                                class="dragArea list-group"
                                :list="getList(block.id)"
                                group="filmgroup"
                                @change="log"
                                item-key="id"
                                style="border:1px solid black; min-height: 60px; min-width: 60px; display: inline-block"
                              >
                                <template #item="{ element }">
                                  <div class="list-group-item">
                                    {{ element.filmIdentifier }}: {{ element.name }}
                                  </div>
                                </template>
                              </draggable>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
          </div>
    </AppLayout>
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
    receiveLists: function() {
        let ret = {};

        this.programmetas.every(function(meta) {
            let listName = meta.id;
            ret[listName] = [];
            meta.films?.every(function(film) {
                ret[listName].push(
                    {
                        id: idGlobal++,
                        name: film.name,
                        filmIdentifier: film.film_identifier,
                        description: film.description
                    }
                );
                return true; // continue
            })
            return true; // continue
        })

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

        function save(url, callBack, eventTarget, data) {
            const xhttp=new XMLHttpRequest();
            xhttp.onload = function() {
                callBack(this, eventTarget, data);
            }
            xhttp.open("POST", url);
            xhttp.send(data);
        }

        function saveCallback(xhttp, eventTarget, data2) {

            let data = data2;

            try {
                data = JSON.parse(xhttp.response);
            } catch (e) {
                event.target.style.backgroundColor = "red";
                return;
            }

            event.target.style.backgroundColor = "";
        }

        event.target.style.backgroundColor = "yellow";
        save(
            '/program/save',
             saveCallback,
             event.target,
             data,
        );

        return event;
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
            this.lists[programmblockId] = [];
            for (let x in data) {
               this.lists[programmblockId].push(
                    {
                        name: data[x].name,
                        filmIdentifier: data[x].film_identifier,
                        description: data[x].description
                    }
                );

            }
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
