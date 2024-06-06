<script setup>
import Headline from './Headline.vue';
import Footer from './Footer.vue';
</script>
<style>
    table {
        width: 100%;
    }
    table tr:first-child {
        font-weight: bold;
    }
    th, td {
        border: 1px solid black;
        padding: 12px;
    }
</style>
<template>
<Headline headline="Statistiken" />
<div class="p-6 lg:p-8 bg-white border-b border-gray-200">
    <pre>$('.stats').innerHTML </pre>
</div>
<div class="p-6 lg:p-8 bg-white border-b border-gray-200 stats">
    Angaben in Laufzeit in Stunden (Anzahl der Filme)<br><br>
    <table class="table">
        <tr v-for="(stat, key) in stats">
            <td>
               {{ key }}
            </td>
            <td  v-for="(grade, key2) in stat">
                {{ grade[1] }} <span v-if="grade[0] !==''">({{ grade[0] }})</span>
            </td>
        </tr>
    </table>
    <br><br>
    Anzahl der bewerteten Filme<br><br>
    <table class="table">
        <tr v-for="(stat, key) in statsGlobalRatingCount">
            <td>{{ stat.r }}</td>
            <td>{{ stat.c }}</td>
            <td>{{ stat.d }}</td>
        </tr>
    </table>
    <br><br>
    Genres<br><br>
    <table class="table">
        <tr>
            <td>Genre</td>
            <td>Anzahl</td>
        </tr>
        <tr v-for="(stat, key) in genreStats">
            <td>{{ stat.name }}</td>
            <td>{{ stat.counter }}</td>
        </tr>
    </table>
    <br><br>
    Keywords<br><br>
    <table class="table">
        <tr>
            <td>Stichwort</td>
            <td>Anzahl</td>
        </tr>
        <tr v-for="(stat, key) in keywordStats">
            <td>{{ stat.name }}</td>
            <td>{{ stat.counter }}</td>
        </tr>
    </table>
    <br><br>
    Keine Laufzeit<br><br>
    <table class="table">
        <tr v-for="(stat, key) in noDurationStats">
            <td>{{ stat.film_identifier }}</td>
            <td>{{ stat.name }}</td>
        </tr>
    </table>

</div>
<Footer :footerLinks="footerLinks" />
</template>
<script>
export default {
  props: [
    'stats',
    'noDurationStats',
    'statsGlobalRatingCount',
    'genreStats',
    'keywordStats',
    'footerLinks'
  ],
  computed: {
  },
  mounted() {
  },
  methods: {
  }
}
</script>
