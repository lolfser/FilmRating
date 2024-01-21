<script setup>
import TextInput from './TextInput.vue';
import PrimaryButton from './PrimaryButton.vue';
import Headline from './Headline.vue';
import Footer from './Footer.vue';
import InputError from './InputError.vue';
import { translate } from './../trans';

import MultiSelect from "@/Components/MultiSelect.vue";
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
<Headline :headline="headline" />
<div class="p-6 lg:p-8 bg-white border-b border-gray-200">
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
</div>
<Footer :footerLinks="footerLinks" />
</template>
<script>
export default {
  props: ['stats'],
  computed: {
  },
  mounted() {
  },
  methods: {
  }
}
</script>
