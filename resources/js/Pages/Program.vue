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
    <div>
        <table style="display:inline-block">
            <tr>
                <td>
                    <span>Verfügbare Filme</span>
                </td>
            </tr>
            <tr>
                <td>
                  <draggable
                    class="dragArea list-group"
                    :list="list1"
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
        <table style="display:inline-block">
            <tr>
                <td>
                    <span>Programmblock 1</span>
                </td>
            </tr>
            <tr>
                <td>
                  <draggable
                    class="dragArea list-group"
                    :list="list2"
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
        <table style="display:inline-block">
            <tr>
                <td>
                    <span>Programmblock 2</span>
                </td>
            </tr>
            <tr>
                <td>
                  <draggable
                    class="dragArea list-group"
                    :list="list3"
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


    </AppLayout>
</template>
<script>
let idGlobal = 0;
export default {
  props: [
      'films', 'footerLinks'
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
      list1: this.films,
      list2: [],
      list3: []
    };
  },
  methods: {
    log: function(evt) {
      window.console.log(evt);
    },
    cloneFilm({ id }) {
      return {
        id: idGlobal++,
        name: `${this.films[id - 1].name}`,
        filmIdentifier: `${this.films[id - 1].film_identifier}`,
        description: `${this.films[id - 1].description}`
      };
    }
  }
}
</script>
