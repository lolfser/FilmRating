<script setup>
// https://primevue.org/multiselect/
import MultiSelect from 'primevue/multiselect';
</script>
<template>
  <div>
    <MultiSelect
      v-model="myModel"
      filter
      :options="options"
      :optionLabel="optionLabel"
      :optionValue="optionValue"
      :placeholder="placeholder"
      :autoFilterFocus="autoFilterFocus"
      :name="name"
    />
  </div>
</template><script>
export default {
  props: ['options', 'optionLabel', 'optionValue', 'placeholder', 'name', 'autoFilterFocus'],
  data() {
    return {
      myModel: null
    };
  },
  methods: {},
};
</script>
