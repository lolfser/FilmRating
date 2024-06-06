<script setup>
// https://primevue.org/
import AutoComplete from 'primevue/autocomplete';
</script>
<template>
  <auto-complete
      v-model="vModel"
      :suggestions="suggestions"
      @complete="searchGrades"
      forceSelection
      completeOnFocus
      :field="(item) => item.value + item.trend"
      emptySearchMessage="Note auswählen (Tippe bspw. &quot;1&quot; ein)"
    ></auto-complete>
</template>
<style>
    .p-autocomplete-input.p-inputtext.p-component {
        max-width: 50px !important;
    }
</style>
<script>
export default {
    props: ['grades', 'selectedValue'],
  data() {
    return {
      vModel: this.selectedValue,
      suggestions: [],
    };
  },
  methods: {
    searchGrades() {
      let values = [];
      let vModel = this.vModel;
      this.grades.every(function (item) {
        if (vModel == null) {
            values.push(item);
            return true;
        }
        if ((item.value + item.trend).includes(vModel)) {
            values.push(item);
        }
        return true;
      });
      this.suggestions = values;
    },
  },
}
</script>
